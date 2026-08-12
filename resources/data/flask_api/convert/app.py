from flask import Flask, request, jsonify
from datetime import datetime
import json
import mysql.connector

app = Flask(__name__)
cityFil = ["臺中市", "彰化縣", "南投縣"]


def readjson(filePath, listName, cityFil):
    # 開啟 JSON 檔案並讀取資料
    try:
        with open(filePath, "r", encoding="utf-8-sig") as file:
            data = json.load(file)
        # print(data.keys())
        # 儲存符合條件的資料
        result = []

        # 逐筆讀取 JSON 裡指定的資料列表
        for item in data.get(listName, []):

            # 取得資料中的城市名稱
            # 如果沒有 PostalAddress 或 city，預設為空值
            city = item.get("PostalAddress", {}).get("city")

            # 判斷城市是否在指定篩選範圍內
            # 符合條件的資料加入結果列表
            if city in cityFil:
                result.append(item)

        # 回傳篩選後的資料
        return result

    # 找不到 JSON 檔案時處理
    except FileNotFoundError:
        print("找不到JSON檔案")
        return []

    # JSON 格式錯誤，無法解析時處理
    except json.JSONDecodeError:
        print("JSON格式錯誤")
        return []


def sqlimport(sql, values, tableName):
    # 初始化資料庫連線與游標
    conn = None
    cursor = None

    # 建立 MySQL 連線並寫入資料
    try:
        conn = mysql.connector.connect(
            host="localhost", user="root", password="", database="travel"
        )

        # 建立游標，用來執行 SQL 指令
        cursor = conn.cursor()

        # 設定每次寫入資料筆數
        # 避免一次寫入大量資料造成資料庫負擔
        batch_size = 100

        # 記錄目前已成功寫入的資料數量
        count = 0

        # 將資料分批寫入資料庫
        for i in range(0, len(values), batch_size):

            # 取得目前這一批要寫入的資料
            batch = values[i : i + batch_size]

            # 執行多筆新增 SQL
            cursor.executemany(sql, batch)

            # 確認寫入資料庫
            conn.commit()

            # 累加已寫入筆數
            count += cursor.rowcount

            # 計算目前上傳進度
            progress = count / len(values) * 100
            # progress:.2f 是 Python 的格式化輸出寫法
            # : 開始指定格式 .2 保留小數點後 2 位
            # f 表示使用浮點數（float）格式
            print(
                f"{tableName} 上傳進度: "
                f"已完成{count}筆/共{len(values)}筆資料 "
                f"({progress:.2f}%)"
            )

        # 顯示完成訊息
        print(f"{tableName} 已新增完成 {count} 筆")

        # 回傳成功寫入筆數
        return count

    # 處理 MySQL 錯誤
    except mysql.connector.Error as err:
        print(f"{tableName} MYSQL錯誤:", err)
        return 0

    # 無論成功或失敗，都關閉資料庫資源
    finally:

        # 關閉游標
        if cursor:
            cursor.close()

        # 關閉資料庫連線
        if conn and conn.is_connected():
            conn.close()


# 景點資料json轉mysql
def import_attraction():

    # 讀取 JSON 檔案，並篩選指定縣市的景點資料
    data = readjson("../../attractionList.json", "attractionList", cityFil)

    # 儲存整理後準備寫入資料庫的資料
    values = []

    # 逐筆處理景點資料
    for attraction in data:

        # 取得景點基本資料
        attractionID = attraction.get("attractionID")
        attractionName = attraction.get("attractionName")

        # 缺少必要資料則跳過
        if not attractionID or not attractionName:
            continue

        # 景點分類可能有多個，轉成字串存入資料庫
        attractionClassNo = ",".join(map(str, attraction.get("attractionClassNo", [])))

        # 取得景點介紹與座標資料
        description = attraction.get("description")
        positionLat = attraction.get("positionLat")
        positionLon = attraction.get("positionLon")

        # 取得地址資料
        PostalAddress = attraction.get("PostalAddress", {})
        zipCode = PostalAddress.get("zipCode")
        city = PostalAddress.get("city")
        town = PostalAddress.get("town")
        streetAddress = PostalAddress.get("streetAddress")

        # 組合完整地址
        fullAddress = str(zipCode) + str(city) + str(town) + str(streetAddress)

        # 取得交通、停車、網站及地圖資訊
        trafficInfo = attraction.get("trafficInfo")
        parkingInfo = attraction.get("parkingInfo")
        websiteURL = attraction.get("websiteURL")

        # mapURLs 是陣列，轉成字串存入資料庫
        mapURLs = ",".join(attraction.get("mapURLs", []))

        # 建立一筆符合資料表欄位順序的資料
        value = (
            attractionID,
            attractionName,
            attractionClassNo,
            description,
            positionLat,
            positionLon,
            zipCode,
            city,
            town,
            streetAddress,
            fullAddress,
            trafficInfo,
            parkingInfo,
            websiteURL,
            mapURLs,
        )

        # 加入批次寫入清單
        values.append(value)

    # 新增景點資料 SQL
    sql = """
    INSERT IGNORE INTO attraction
    (
        attractionID,
        attractionName,
        attractionClassNo,
        description,
        positionLat,
        positionLon,
        zipCode,
        city,
        town,
        streetAddress,
        fullAddress,
        trafficInfo,
        parkingInfo,
        websiteURL,
        mapURLs
    )
    VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
    """

    # 沒有資料時停止寫入
    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    # 呼叫共用 SQL 匯入函式寫入資料庫
    count = sqlimport(sql, values, "attraction")

    # 回傳匯入結果
    return jsonify({"message": "資料已上傳", "count": count}), 201


# 景點開放時間 JSON 匯入 MySQL
def import_attractionServiceTime():
    # 取得指定縣市的景點ID
    attractionData = readjson("../../attractionList.json", "attractionList", cityFil)

    attractionIDList = []

    for attraction in attractionData:
        attractionID = attraction.get("attractionID")

        if attractionID:
            attractionIDList.append(attractionID)

    # 讀取 JSON 檔案
    # 使用 utf-8-sig 可避免 JSON 檔案含有 BOM 時造成解析錯誤
    try:
        with open(
            "../../attractionServiceTimeList.json", "r", encoding="utf-8-sig"
        ) as file:
            data = json.load(file)

    # JSON 檔案不存在
    except FileNotFoundError:
        return jsonify({"error": "找不到JSON檔案"}), 404

    # JSON 格式錯誤，無法解析
    except json.JSONDecodeError:
        return jsonify({"error": "JSON格式錯誤"}), 400

    # 儲存整理後準備寫入資料庫的資料
    values = []

    # 逐筆讀取景點開放時間資料
    # 使用 get 避免 JSON 缺少欄位時造成錯誤
    for attractionserviceTimes in data.get("attractionserviceTimes", []):

        # 取得景點基本資料
        attractionID = attractionserviceTimes.get("attractionID")
        if attractionID not in attractionIDList:
            continue
        attractionName = attractionserviceTimes.get("attractionName")

        # 一個景點可能有多組開放時間
        for serviceTimes in attractionserviceTimes.get("serviceTimes", []):

            # 取得開始與結束時間
            startTime = serviceTimes.get("startTime")
            endTime = serviceTimes.get("endTime")

            # 缺少開放時間資料則跳過，不寫入資料庫
            if not startTime or not endTime:
                continue

            # serviceDays 是陣列
            # 需要拆成單筆資料存入資料庫
            # 例如：
            # ["Monday", "Tuesday"]
            # 會轉成兩筆資料
            for serviceDays in serviceTimes.get("serviceDays", []):

                # 建立一筆資料
                value = (attractionID, attractionName, serviceDays, startTime, endTime)

                # 加入批次新增清單
                values.append(value)

    # SQL 新增語法
    sql = """
    INSERT IGNORE INTO attraction_servicetime
    (
        attractionID,
        attractionName,
        serviceDays,
        startTime,
        endTime
    )
    VALUES (%s, %s, %s, %s, %s)
    """

    # 沒有資料時停止執行
    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    # 連線資料庫並新增資料
    count = sqlimport(sql, values, "attraction_servicetime")
    return jsonify({"message": "資料已上傳", "count": count}), 201


# 景點門票json轉mysql
def import_attraction_fee():

    # 取得指定縣市的景點ID
    attractionData = readjson("../../attractionList.json", "attractionList", cityFil)

    attractionIDList = set()

    for attraction in attractionData:
        attractionID = attraction.get("attractionID")

        if attractionID:
            attractionIDList.add(attractionID)

    # 讀取 JSON
    try:
        with open("../../attractionFeeList.json", "r", encoding="utf-8-sig") as file:
            data = json.load(file)

    except FileNotFoundError:
        return jsonify({"error": "找不到JSON檔案"}), 404

    except json.JSONDecodeError:
        return jsonify({"error": "JSON格式錯誤"}), 400

    # 整理資料
    values = []

    for attraction in data.get("attractionFeeList", []):

        # 取得景點資料
        attractionID = attraction.get("attractionID")
        attractionName = attraction.get("attractionName")

        # 不在指定縣市的景點跳過
        if attractionID not in attractionIDList:
            continue

        for fee in attraction.get("fee", []):

            feeName = fee.get("Name")
            price = fee.get("price")
            description = fee.get("description")
            feeURL = fee.get("URL")

            # 缺少必要資料則跳過
            if not attractionID or not attractionName or not feeName:
                continue

            value = (attractionID, attractionName, feeName, price, description, feeURL)

            values.append(value)

    # SQL
    sql = """
    INSERT IGNORE INTO attraction_fee
    (
        attractionID,
        attractionName,
        feeName,
        price,
        description,
        feeURL
    )
    VALUES (%s,%s,%s,%s,%s,%s)
    """

    # 沒有資料時停止
    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    # 使用共用 SQL 匯入
    count = sqlimport(sql, values, "attraction_fee")

    return jsonify({"message": "資料已上傳", "count": count}), 201


# 景點照片json轉mysql
def import_attraction_photo():

    # 讀取指定縣市的景點資料
    data = readjson("../../attractionList.json", "attractionList", cityFil)

    # 整理資料
    values = []

    for attraction in data:

        attractionID = attraction.get("attractionID")
        attractionName = attraction.get("attractionName")

        for photo_data in attraction.get("photo", []):
            photo = photo_data.get("URL")
            photoName = photo_data.get("Name")
            description = photo_data.get("description")
            photoProvider = None
            if not attractionID or not photo:
                continue
            if description:
                if "｜" in description:
                    photoProvider = description.split("｜", 1)[1]
                elif "|" in description:
                    photoProvider = description.split("|", 1)[1]
            # 缺少必要資料則跳過
            value = (attractionID, attractionName, photo, photoName, photoProvider)
            values.append(value)

    # SQL
    sql = """
    INSERT IGNORE INTO attraction_photo
    (
        attractionID, attractionName, photo, photoName, photoProvider
    )
    VALUES (%s, %s, %s, %s , %s)
    """

    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    count = sqlimport(sql, values, "attraction_photo")
    return jsonify({"message": "資料已上傳", "count": count}), 201


# 景點門票json轉mysql
def import_attraction_tel():
    # 讀取 JSON
    data = readjson("../../attractionList.json", "attractionList", cityFil)

    # 整理資料
    values = []

    for attraction in data:

        attractionID = attraction.get("attractionID")
        attractionName = attraction.get("attractionName")

        for telephones in attraction.get("telephones", []):
            tel = telephones.get("tel")
            # 缺少必要資料則跳過
            if not attractionID or not attractionName or not tel:
                continue
            value = (attractionID, attractionName, tel)
            values.append(value)

    # SQL
    sql = """
    INSERT IGNORE INTO attraction_tel
    (
        attractionID,
        attractionName,
        tel
    )
    VALUES (%s, %s, %s)
    """

    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    conn = None
    cursor = None

    # 連線 MySQL
    count = sqlimport(sql, values, "attraction_tel")
    return jsonify({"message": "資料已上傳", "count": count}), 201


# 旅宿資料json轉mysql
def import_hotel():

    data = readjson("../../hotellist.json", "hotels", cityFil)

    # 整理資料
    values = []
    for hotels in data:
        hotelID = hotels.get("hotelID")
        hotelLicenseNumber = hotels.get("hotelLicenseNumber")
        hotelName = hotels.get("hotelName")
        hotelClassNo = ",".join(map(str, hotels.get("hotelClassNo", [])))
        description = hotels.get("description")
        positionLat = hotels.get("positionLat")
        positionLon = hotels.get("positionLon")
        PostalAddress = hotels.get("PostalAddress", {})
        zipCode = PostalAddress.get("zipCode")
        city = PostalAddress.get("city")
        town = PostalAddress.get("town")
        streetAddress = PostalAddress.get("streetAddress")
        fullAddress = str(zipCode) + str(city) + str(town) + str(streetAddress)
        parkingInfo = hotels.get("parkingInfo")
        websiteURL = hotels.get("websiteURL")
        roomInfo = hotels.get("roomInfo")
        totalRooms = hotels.get("totalRooms")
        lowestPrice = hotels.get("lowestPrice")
        ceilingPrice = hotels.get("ceilingPrice")
        if not hotelID or not hotelLicenseNumber or not hotelName:
            continue
        value = (
            hotelID,
            hotelLicenseNumber,
            hotelName,
            hotelClassNo,
            description,
            positionLat,
            positionLon,
            zipCode,
            city,
            town,
            streetAddress,
            fullAddress,
            parkingInfo,
            websiteURL,
            roomInfo,
            totalRooms,
            lowestPrice,
            ceilingPrice,
        )
        values.append(value)

    # sql
    sql = """
    INSERT IGNORE INTO hotel(
                hotelID,
                hotelLicenseNumber,
                hotelName,
                hotelClassNo,
                description,
                positionLat,
                positionLon,
                zipCode,
                city,
                town,
                streetAddress,
                fullAddress,
                parkingInfo,
                websiteURL,
                roomInfo,
                totalRooms,
                lowestPrice,
                ceilingPrice)
    VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
    """
    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    count = sqlimport(sql, values, "hotel")
    return jsonify({"message": "資料已上傳", "count": count}), 201


# 旅宿照片json轉mysql
def import_hotel_photo():

    # 讀取 JSON
    data = readjson("../../hotellist.json", "hotels", cityFil)

    # 整理資料
    values = []

    for hotels in data:

        hotelID = hotels.get("hotelID")
        hotelName = hotels.get("hotelName")

        for photo_data in hotels.get("photo", []):
            photo = photo_data.get("URL")
            photoName = photo_data.get("Name")

            if not hotelID or not photo:
                continue
            value = (hotelID, hotelName, photo, photoName)
            values.append(value)

    # SQL
    sql = """
    INSERT IGNORE INTO hotel_photo
    (
        hotelID, hotelName, photo, photoName
    )
    VALUES (%s, %s, %s, %s)
    """

    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    # 連線 MySQL
    count = sqlimport(sql, values, "hotel_photo")

    print("hotel_photo 全部上傳完成")
    return jsonify({"message": "資料已上傳", "count": count}), 201


# 旅宿電話json轉mysql
def import_hotel_tel():

    # 讀取指定縣市的飯店資料
    data = readjson("../../hotellist.json", "hotels", cityFil)

    # 儲存整理後準備寫入資料庫的資料
    values = []

    # 逐筆讀取飯店資料
    for hotels in data:

        # 取得飯店基本資料
        hotelID = hotels.get("hotelID")
        hotelName = hotels.get("hotelName")

        # 一間飯店可能有多組電話
        for telephones in hotels.get("telephones", []):

            # 取得電話資料
            tel = telephones.get("tel")

            # 缺少必要資料則跳過
            if not hotelID or not hotelName or not tel:
                continue

            # 建立一筆資料
            value = (hotelID, hotelName, tel)

            # 加入批次寫入清單
            values.append(value)

    # SQL 新增語法
    sql = """
    INSERT IGNORE INTO hotel_tel
    (
        hotelID,
        hotelName,
        tel
    )
    VALUES (%s, %s, %s)
    """

    # 沒有資料時停止執行
    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    # 執行資料庫寫入
    count = sqlimport(sql, values, "hotel_tel")

    return jsonify({"message": "資料已上傳", "count": count}), 201


# 餐飲資料json轉mysql
def import_restaurant():

    data = readjson("../../restaurantList.json", "restaurants", cityFil)

    # 整理資料
    values = []
    for restaurants in data:
        restaurantID = restaurants.get("restaurantID")
        restaurantName = restaurants.get("restaurantName")
        cuisineClassNo = ",".join(map(str, restaurants.get("cuisineClassNo", [])))
        description = restaurants.get("description")
        positionLat = restaurants.get("positionLat")
        positionLon = restaurants.get("positionLon")
        PostalAddress = restaurants.get("PostalAddress", {})
        zipCode = PostalAddress.get("zipCode")
        city = PostalAddress.get("city")
        town = PostalAddress.get("town")
        streetAddress = PostalAddress.get("streetAddress")
        fullAddress = str(zipCode) + str(city) + str(town) + str(streetAddress)
        trafficInfo = restaurants.get("trafficInfo")
        parkingInfo = restaurants.get("parkingInfo")
        websiteURL = restaurants.get("websiteURL")
        mapURLs = ",".join(restaurants.get("mapURLs", []))
        telephones = restaurants.get("telephones", [])
        tel = None
        if telephones:
            tel = telephones[0].get("tel")
            Ext = telephones[0].get("Ext")
            if Ext:
                tel = f"{tel} Ext:{Ext}"
        if not restaurantID or not restaurantName:
            continue
        value = (
            restaurantID,
            restaurantName,
            cuisineClassNo,
            description,
            positionLat,
            positionLon,
            zipCode,
            city,
            town,
            streetAddress,
            fullAddress,
            tel,
            trafficInfo,
            parkingInfo,
            websiteURL,
            mapURLs,
        )
        values.append(value)
    # sql
    sql = """
    INSERT  IGNORE INTO restaurant(
                restaurantID,
                            restaurantName,
                            cuisineClassNo,
                            description,
                            positionLat,
                            positionLon,
                            zipCode,
                            city,
                            town,
                            streetAddress,
                            fullAddress,
                            tel,
                            trafficInfo,
                            parkingInfo,
                            websiteURL,
                            mapURLs)
    VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
    """
    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    # 連接資料庫
    count = sqlimport(sql, values, "restaurant")
    return jsonify({"message": "資料已上傳", "count": count}), 201


# 餐飲開放時間 JSON 匯入 MySQL
def import_restaurantServiceTime():

    # 取得指定縣市的餐廳ID
    # restaurantServiceTimeList 沒有地址資料，因此先從餐廳主資料取得符合城市的ID
    restaurantData = readjson("../../restaurantList.json", "restaurants", cityFil)

    # 使用 set 儲存餐廳ID，提高後續查詢速度
    restaurantIDList = []

    for restaurant in restaurantData:
        restaurantID = restaurant.get("restaurantID")

        if restaurantID:
            restaurantIDList.append(restaurantID)

    # 讀取 JSON 檔案
    # 使用 utf-8-sig 可避免 JSON 檔案含有 BOM 時造成解析錯誤
    try:
        with open(
            "../../restaurantServiceTimeList.json", "r", encoding="utf-8-sig"
        ) as file:
            data = json.load(file)

    # JSON 檔案不存在
    except FileNotFoundError:
        return jsonify({"error": "找不到JSON檔案"}), 404

    # JSON 格式錯誤，無法解析
    except json.JSONDecodeError:
        return jsonify({"error": "JSON格式錯誤"}), 400

    # 儲存整理後準備寫入資料庫的資料
    values = []

    # 逐筆讀取餐廳開放時間資料
    for restaurantserviceTimes in data.get("restaurantserviceTimes", []):

        # 取得餐廳基本資料
        restaurantID = restaurantserviceTimes.get("restaurantID")

        # 不屬於指定縣市的餐廳跳過
        if restaurantID not in restaurantIDList:
            continue

        restaurantName = restaurantserviceTimes.get("restaurantName")

        # 一間餐廳可能有多組開放時間
        for serviceTimes in restaurantserviceTimes.get("serviceTimes", []):

            # 取得開始與結束時間
            startTime = serviceTimes.get("startTime")
            endTime = serviceTimes.get("endTime")

            # 缺少開放時間資料則跳過
            if not startTime or not endTime:
                continue

            # serviceDays 是陣列，需要拆成單筆資料
            for serviceDays in serviceTimes.get("serviceDays", []):

                # 建立一筆資料
                value = (restaurantID, restaurantName, serviceDays, startTime, endTime)

                # 加入批次寫入清單
                values.append(value)

    # SQL 新增語法
    sql = """
    INSERT IGNORE INTO restaurant_servicetime
    (
        restaurantID,
        restaurantName,
        serviceDays,
        startTime,
        endTime
    )
    VALUES (%s, %s, %s, %s, %s)
    """

    # 沒有資料時停止執行
    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    # 執行資料庫寫入
    count = sqlimport(sql, values, "restaurant_servicetime")

    return jsonify({"message": "資料已上傳", "count": count}), 201


# 餐飲照片json轉mysql
# 餐飲照片 JSON 匯入 MySQL
def import_restaurant_photo():

    # 讀取指定縣市的餐廳資料
    data = readjson("../../restaurantList.json", "restaurants", cityFil)

    # 儲存整理後準備寫入資料庫的資料
    values = []

    # 逐筆讀取餐廳資料
    for restaurants in data:

        # 取得餐廳基本資料
        restaurantID = restaurants.get("restaurantID")
        restaurantName = restaurants.get("restaurantName")

        # 一間餐廳可能有多張照片
        for photo_data in restaurants.get("photo", []):

            # 取得照片資料
            photo = photo_data.get("URL")
            photoName = photo_data.get("Name")

            # 缺少必要資料則跳過
            if not restaurantID or not photo:
                continue

            # 建立一筆資料
            value = (restaurantID, restaurantName, photo, photoName)

            # 加入批次寫入清單
            values.append(value)

    # SQL 新增語法
    sql = """
    INSERT IGNORE INTO restaurant_photo
    (
        restaurantID,
        restaurantName,
        photo,
        photoName
    )
    VALUES (%s, %s, %s, %s)
    """

    # 沒有資料時停止執行
    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    # 執行資料庫寫入
    count = sqlimport(sql, values, "restaurant_photo")

    return jsonify({"message": "資料已上傳", "count": count}), 201


# 旅宿資料json轉mysql
def import_events():

    data = readjson("../../eventlist.json", "events", cityFil)

    # 整理資料
    values = []
    for events in data:
        eventID = events.get("eventID")
        eventName = events.get("eventName")
        description = events.get("description")
        positionLat = events.get("positionLat")
        positionLon = events.get("positionLon")
        PostalAddress = events.get("PostalAddress", {})
        zipCode = PostalAddress.get("zipCode")
        city = PostalAddress.get("city")
        town = PostalAddress.get("town")
        streetAddress = PostalAddress.get("streetAddress")
        fullAddress = str(zipCode) + str(city) + str(town) + str(streetAddress)
        startDateTime = events.get("startDateTime")
        websiteURL = events.get("websiteURL")
        if startDateTime:
            startDateTime = startDateTime.split("T")[0]
        else:
            # 如果允許空值可改為 None，若一定要有日期則保留 continue
            startDateTime = None 
        
        for telephones in events.get("telephones", []):
            tel = telephones.get("tel")
        for photo in events.get("photo", []):
            img1 = photo.get("uRL")
        
        
        if not eventID or not eventName:
            continue
        value = (
            eventID,
            eventName,
            description,
            positionLat,
            positionLon,
            zipCode,
            city,
            town,
            streetAddress,
            fullAddress,
            websiteURL,
            startDateTime,
            tel,
            img1,
        )
        values.append(value)

    # sql
    sql = """
    INSERT  INTO events(
                eventID,
                eventName,
                description,
                positionLat,
                positionLon,
                zipCode,
                city,
                town,
                streetAddress,
                fullAddress,
                websiteURL,
                startDateTime,
                tel,
                img1
                )
    VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
    """
    if not values:
        return jsonify({"message": "無可上傳資料"}), 400

    

    count = sqlimport(sql, values, "events")
    return jsonify({"message": "資料已上傳", "count": count}), 201
    

# 將景點分類名稱、電話、第一張照片更新回 attraction 主表
def update_attraction_data():

    conn = None
    cursor = None

    try:
        # 連線 MySQL 資料庫
        conn = mysql.connector.connect(
            host="localhost", user="root", password="", database="travel"
        )

        cursor = conn.cursor()

        # 更新景點分類名稱
        # attractionClassNo 儲存多個分類編號，例如 "1,3,5"
        # 使用 FIND_IN_SET 比對分類編號
        # 使用 GROUP_CONCAT 將多個分類名稱合併成字串
        sql_class = """
            UPDATE attraction AS a
            SET a.attractionClassName =
            (
                SELECT GROUP_CONCAT(
                ac.attractionClassName
                ORDER BY ac.attractionClassNo
                SEPARATOR ','
                )
                FROM attraction_class AS ac
                WHERE FIND_IN_SET(
                ac.attractionClassNo,
                a.attractionClassNo
                )
            )
        """
        cursor.execute(sql_class)
        # 更新景點分類名稱(下拉式選單用)
        # attractionClassNo 儲存多個分類編號，例如 "1,3,5"
        # 使用 FIND_IN_SET 比對分類編號
        # 使用 GROUP_CONCAT 將多個分類名稱合併成字串
        sql_class2 = """
                    UPDATE attraction AS a
                    SET a.attractionClassName2 =
                    (
                        SELECT GROUP_CONCAT(
                        ac.attractionClassName2
                        ORDER BY ac.attractionClassNo
                        SEPARATOR ','
                        )
                        FROM attraction_class AS ac
                        WHERE FIND_IN_SET(
                        ac.attractionClassNo,
                        a.attractionClassNo
                        )
                    )
                """

        # 執行分類名稱更新
        cursor.execute(sql_class2)

        print("分類更新:", cursor.rowcount)

        # 更新景點電話
        # 使用 attractionID 與 attractionName 對應景點資料
        # 從 attraction_tel 表取得電話並寫入 attraction.tel
        sql_tel = """
        UPDATE attraction AS a
        INNER JOIN attraction_tel AS b
        ON a.attractionID = b.attractionID
        AND a.attractionName = b.attractionName
        SET a.tel = b.tel
        """

        # 執行電話更新
        cursor.execute(sql_tel)
        print("電話更新:", cursor.rowcount)

        # 更新景點第一張照片
        # 從 attraction_photo 取得每個景點的照片資料
        # 將照片網址存入 attraction.img1
        sql_photo = """
        UPDATE attraction AS a
        JOIN
        (
            SELECT
                attractionID,
                attractionName,
                MIN(photo) AS photo
            FROM attraction_photo
            GROUP BY attractionID, attractionName
        ) AS b
        ON a.attractionID = b.attractionID
        AND a.attractionName = b.attractionName
        SET a.img1 = b.photo
        """

        # 執行照片更新
        cursor.execute(sql_photo)
        print("照片更新:", cursor.rowcount)

        # 儲存所有更新結果
        conn.commit()

        print("景點分類名稱、電話及照片更新完成")

    # 捕捉 MySQL 錯誤
    except mysql.connector.Error as err:
        print("資料庫錯誤:", err)

    # 關閉資料庫連線
    finally:
        if cursor:
            cursor.close()

        if conn and conn.is_connected():
            conn.close()


# 將電話、第一張照片更新到hotel表
def update_hotel_data():

    conn = None
    cursor = None

    try:
        # 連線資料庫
        conn = mysql.connector.connect(
            host="localhost", user="root", password="", database="travel"
        )

        cursor = conn.cursor()
        # 更新旅宿分類名稱
        # hotelClassNo 儲存多個分類編號，例如 "1,3,5"
        # 使用 FIND_IN_SET 比對分類編號
        # 使用 GROUP_CONCAT 將多個分類名稱合併成字串
        sql_class = """
                    UPDATE hotel  AS a
                    SET a.hotelClassName =
                    (
                        SELECT GROUP_CONCAT(
                        ac.hotelClassName
                        ORDER BY ac.hotelClassNo
                        SEPARATOR ','
                        )
                        FROM hotel_class AS ac
                        WHERE FIND_IN_SET(
                        ac.hotelClassNo,
                        a.hotelClassNo
                        )
                    )
                """
        cursor.execute(sql_class)

        # 更新旅宿分類名稱(下拉式選單用)
        # hotelClassNo 儲存多個分類編號，例如 "1,3,5"
        # 使用 FIND_IN_SET 比對分類編號
        # 使用 GROUP_CONCAT 將多個分類名稱合併成字串
        sql_class2 = """
                            UPDATE hotel  AS a
                            SET a.hotelClassName2 =
                            (
                                SELECT GROUP_CONCAT(
                                ac.hotelClassName2
                                ORDER BY ac.hotelClassNo
                                SEPARATOR ','
                                )
                                FROM hotel_class AS ac
                                WHERE FIND_IN_SET(
                                ac.hotelClassNo,
                                a.hotelClassNo
                                )
                            )
                        """
        cursor.execute(sql_class2)
        print("分類更新:", cursor.rowcount)
        # 更新旅宿電話
        # 使用hotelID及hotelName比對
        # 將電話存入hotel.tel
        sql_tel = """
        UPDATE hotel AS a
        LEFT JOIN hotel_tel AS b
        ON a.hotelID=b.hotelID
        AND a.hotelName=b.hotelName
        SET a.tel=b.tel
        """

        cursor.execute(sql_tel)

        # 更新旅宿第一張照片
        # 取attraction_photo最早的一筆資料
        # 存入attraction.img1
        sql_photo = """
        UPDATE hotel AS a
        LEFT JOIN
        (
            SELECT
                p.hotelID,
                p.hotelName,
                p.photo
            FROM hotel_photo AS p
            WHERE p.id =
            (
                SELECT MIN(id)
                FROM hotel_photo
                WHERE hotelID=p.hotelID
                AND hotelName=p.hotelName
            )
        ) AS b
        ON a.hotelID=b.hotelID
        AND a.hotelName=b.hotelName
        SET a.img1=b.photo
        """

        cursor.execute(sql_photo)

        # 儲存更新結果
        conn.commit()

        print("旅宿資電話及照片已匯入完成")

    except mysql.connector.Error as err:
        print("資料庫錯誤:", err)

    finally:
        if cursor:
            cursor.close()

        if conn and conn.is_connected():
            conn.close()


# 將第一張照片更新到restaurant表
def update_restaurant_data():

    conn = None
    cursor = None

    try:
        # 連線資料庫
        conn = mysql.connector.connect(
            host="localhost", user="root", password="", database="travel"
        )

        cursor = conn.cursor()

        # 更新餐廳分類名稱
        # hotelClassNo 儲存多個分類編號，例如 "1,3,5"
        # 使用 FIND_IN_SET 比對分類編號
        # 使用 GROUP_CONCAT 將多個分類名稱合併成字串
        sql_class = """
                    UPDATE restaurant AS a
                    SET a.cuisineClassName =
                    (
                        SELECT GROUP_CONCAT(
                        ac.cuisineClassName
                        ORDER BY ac.cuisineClassNo
                        SEPARATOR ','
                        )
                        FROM restaurant_class AS ac
                        WHERE FIND_IN_SET(
                        ac.cuisineClassNo,
                        a.cuisineClassNo
                        )
                    )
                """
        cursor.execute(sql_class)

        # 更新餐廳分類名稱
        # hotelClassNo 儲存多個分類編號，例如 "1,3,5"
        # 使用 FIND_IN_SET 比對分類編號
        # 使用 GROUP_CONCAT 將多個分類名稱合併成字串
        sql_class2 = """
                            UPDATE restaurant AS a
                            SET a.cuisineClassName2 =
                            (
                                SELECT GROUP_CONCAT(
                                ac.cuisineClassName2
                                ORDER BY ac.cuisineClassNo
                                SEPARATOR ','
                                )
                                FROM restaurant_class AS ac
                                WHERE FIND_IN_SET(
                                ac.cuisineClassNo,
                                a.cuisineClassNo
                                )
                            )
                        """
        cursor.execute(sql_class2)
        print("分類更新:", cursor.rowcount)
        # 更新餐廳第一張照片
        # 取得restaurant_photo中每間餐廳最早建立的一筆照片資料
        # 將照片網址更新到restaurant.img1欄位
        sql_photo = """
        UPDATE restaurant AS a
        LEFT JOIN
        (
            SELECT
                p.restaurantID,
                p.restaurantName,
                p.photo
            FROM restaurant_photo AS p
            WHERE p.id =
            (
                SELECT MIN(id)
                FROM restaurant_photo
                WHERE restaurantID=p.restaurantID
                AND restaurantName=p.restaurantName
            )
        ) AS b
        ON a.restaurantID=b.restaurantID
        AND a.restaurantName=b.restaurantName
        SET a.img1=b.photo
        """

        cursor.execute(sql_photo)
        print("照片更新:", cursor.rowcount)

        # 儲存更新結果
        conn.commit()

        print("餐廳照片已匯入完成")

    except mysql.connector.Error as err:
        print("資料庫錯誤:", err)

    finally:
        # 關閉游標
        if cursor:
            cursor.close()

        # 關閉資料庫連線
        if conn and conn.is_connected():
            conn.close()


if __name__ == "__main__":

    with app.app_context():

        print("開始匯入 attraction")
        print(import_attraction())
        print("attraction 上傳完成")

        print("開始匯入 attractionServiceTime")
        print(import_attractionServiceTime())
        print("attractionServiceTime 上傳完成")

        print("開始匯入 attraction_fee")
        print(import_attraction_fee())
        print("attraction_fee 上傳完成")

        print("開始匯入 attraction_photo")
        print(import_attraction_photo())
        print("attraction_photo 上傳完成")

        print("開始匯入 attraction_tel")
        print(import_attraction_tel())
        print("attraction_tel 上傳完成")

        print("開始匯入 hotel")
        print(import_hotel())
        print("hotel 上傳完成")

        print("開始匯入 hotel_photo")
        print(import_hotel_photo())
        print("hotel_photo 上傳完成")

        print("開始匯入 hotel_tel")
        print(import_hotel_tel())
        print("hotel_tel 上傳完成")

        print("開始匯入 restaurant")
        print(import_restaurant())
        print("restaurant 上傳完成")

        print("開始匯入 restaurantServiceTime")
        print(import_restaurantServiceTime())
        print("restaurantServiceTime 上傳完成")

        print("開始匯入 restaurant_photo")
        print(import_restaurant_photo())
        print("restaurant_photo 上傳完成")


        print("開始匯入 events")
        print(import_events())
        print("events 上傳完成")

        print("開始更新 attraction")
        update_attraction_data()
        print("attraction 更新完成")

        print("開始更新 hotel")
        update_hotel_data()
        print("hotel 更新完成")

        print("開始更新 restaurant")
        update_restaurant_data()
        print("restaurant 更新完成")

        print("所有資料全部完成")

    app.run(debug=True, use_reloader=False)
