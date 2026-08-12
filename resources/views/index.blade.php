<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>中彰投生活圈</title>
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="css/all.min.css">
    {{-- bootstrap --}}
    <link rel="stylesheet" href="css/bootstrap.min.css">
    {{-- google font(Noto+Sans+TC) --}}
    <link rel="stylesheet" href="css/front.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    {{-- 三張照片輪播用，疊圖 --}}
    <div class="landing-slider">
        {{-- 南投：晨曦 --}}
        <div class="landing-banner">
            {{-- 背景照片 --}}
            <div class="landing-image" style="background-image: url('img/nt-canva0801-2.jpg');"></div>
            {{-- Logo --}}
            <img src="img/logo-landingpage.svg" class="logo" alt="中彰投生活圈">
            {{-- 文字內容 --}}
            <div class="landing-content">
                <div class="landing-tag">
                    <span>南投縣 | 湖光山色</span>
                </div>
                <h1 class="landing-title">
                    晨曦
                    <span>SERENITY</span>
                </h1>
                <p class="landing-subtitle">
                    當曙光喚醒平靜湖面，遇見身心最純粹的平靜
                </p>
                <div class="lableBottom">
                    <span>📍日月潭</span>
                </div>
                {{-- 開始探索 --}}
                <a href="/home" class="btn-landing">
                    開始探索
                </a>
                {{-- 下方箭頭 --}}
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
        {{-- 彰化：尋味 --}}
        <div class="landing-banner">
            {{-- 背景照片 --}}
            <div class="landing-image" style="background-image: url('img/ch-canva0801-2.jpg');"></div>
            {{-- Logo --}}
            <img src="img/logo-landingpage.svg" class="logo" alt="中彰投生活圈">
            {{-- 文字內容 --}}
            <div class="landing-content">
                <div class="landing-tag">
                    <span>彰化縣 | 百年風華</span>
                </div>
                <h1 class="landing-title">
                    尋味
                    <span>HERITAGE</span>
                </h1>
                <p class="landing-subtitle">
                    漫步紅磚巷弄，聽磚瓦細語昔日的繁華故事
                </p>
                <div class="lableBottom">
                    <span>📍鹿港老街</span>
                </div>
                {{-- 開始探索 --}}
                <a href="/home" class="btn-landing">
                    開始探索
                </a>
                {{-- 下方箭頭 --}}
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
        {{-- 臺中：沉醉 --}}
        <div class="landing-banner">
            {{-- 背景照片 --}}
            <div class="landing-image" style="background-image: url('img/tc-canva0801.jpg');"></div>
            {{-- Logo --}}
            <img src="img/logo-landingpage.svg" class="logo" alt="中彰投生活圈">
            {{-- 文字內容 --}}
            <div class="landing-content">
                <div class="landing-tag">
                    <span>臺中市 | 現代美學</span>
                </div>
                <h1 class="landing-title">
                    沉醉
                    <span>INSPIRATION</span>
                </h1>
                <p class="landing-subtitle">
                    穿梭於光影曲牆，在城市的夜晚與藝術悄然相遇
                </p>
                <div class="lableBottom">
                    <span>📍國家歌劇院</span>
                </div>
                {{-- 開始探索 --}}
                <a href="/home" class="btn-landing">
                    開始探索
                </a>
                {{-- 下方箭頭 --}}
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
    </div>


    {{-- Font Awesome --}}
    <script src="js/all.min.js"></script>
    {{-- bootstrap --}}
    <script src="js/bootstrap.min.js"></script>
    {{-- jquery --}}
    <script src="js/jquery-4.0.0.min.js"></script>
    {{-- vue --}}
    <script src="js/vue.global.min.js"></script>
    {{-- sweetalert2 --}}
    <script src="js/sweetalert2.all.min.js"></script>

    <script>
        // 取得所有輪播圖片
        let banners = document.querySelectorAll(".landing-banner");
        // 目前顯示第幾張
        let current = 0;
        setInterval(function() {
            // 隱藏目前圖片
            banners[current].style.opacity = "0";
            banners[current].style.visibility = "hidden";
            // 換下一張
            current++;
            // 如果到最後一張，就回到第一張
            if (current >= banners.length) {
                current = 0;
            }
            // 顯示下一張
            banners[current].style.opacity = "1";
            banners[current].style.visibility = "visible";
            // 取得目前圖片的背景
            let image = banners[current].querySelector(".landing-image");

            // 重新播放縮放動畫
            image.style.animation = "none";
            image.offsetHeight;
            image.style.animation = "landingZoom 5s ease-out forwards";
        }, 8000);
    </script>
</body>



</html>