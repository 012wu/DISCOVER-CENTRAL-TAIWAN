<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminHotel;
use App\Models\Admin\AdminHotelClass;
use Illuminate\Support\Facades\Session;

class AdminhotelController extends Controller
{
    // GET /admin/Hotel - 取得全部旅宿（純 API，不分頁不篩選，維持原本用途）
    public function index(Request $req)
    {
        $list = AdminHotel::all();
        return response()->json([
            "success" => true,
            "msg" => [
                "data" => $list
            ]
        ]);
    }

    // GET /admin/hotel/{id} - 取得單筆旅宿
    public function show($id)
    {
        $item = AdminHotel::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    // GET /admin/hotel/list - 後台旅宿列表頁（含篩選、排序、分頁，供 Blade 頁面渲染）
    public function list(Request $req)
    {
        $query = AdminHotel::query();
        $hotelClasses = AdminHotelClass::all();

        // 縣市篩選
        $city = $req->input('city');
        if ($city && $city !== '全部縣市') {
            $query->where('city', $city);
        }

        // 分類篩選
        $hotelClass = $req->input('hotelClass');
        if ($hotelClass && $hotelClass !== '熱門分類') {
            $query->where('hotelClassName2', $hotelClass);
        }

        // 關鍵字篩選：比對名稱與詳細描述
        $keyword = $req->input('keyword');

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('hotelName', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('town', 'like', "%{$keyword}%")
                    ->orWhere('streetAddress', 'like', "%{$keyword}%");
            });
        }
        // 排序方式
        $rank = $req->input('rank');
        switch ($rank) {
            case '旅宿編號（小->大）':
                $query->orderBy('hotelid');
                break;
            case '名稱 (筆劃少->多)':
                $query->orderBy('hotelName');
                break;
            case '最新上架':
                $query->orderByDesc('createTime');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        // 每頁幾筆
        $pageSize = $req->input('pageSize', 15);

        // 分頁
        $list = $query->paginate($pageSize)->withQueryString();


        // 把 $list 傳-> hotel.blade.php
        return view('admin.hotel', compact('list', 'hotelClasses'));
    }

    // POST /api/hotel - 新增旅宿
    public function store(Request $request)
    {
        try {
            // 檢查名稱是否重複
            $exists = AdminHotel::where('hotelName', $request->hotelName)->exists();
            if ($exists) {
                return response()->json([
                    "success" => false,
                    "message" => '景點名稱已存在，請勿重複新增'
                ], 422);
            }
            // 取得使用者輸入的所有資料
            $data = $request->all();

            // 例如 A01,A02
            $classNos = explode(',', $request->hotelClassNo);

            // 去掉空白
            $classNos = array_map('trim', $classNos);

            // 到分類表查詢
            $classes = AdminHotelClass::whereIn('hotelClassNo', $classNos)->get();

            // 組合分類名稱，存回 $data
            $data['hotelClassName'] = $classes->pluck('hotelClassName')->implode(',');
            $data['hotelClassName2'] = $classes->pluck('hotelClassName2')->implode(',');

            $hotel = AdminHotel::create($data);

            return response()->json([
                "success" => true,
                "msg" => "已新增"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '操作失敗，請稍後再試。'
            ], 500);
        }
    }


    // PUT /api/hotel/{id} - 更新旅宿
    public function update(Request $request, $id)
    {
        try {
            $hotel = AdminHotel::find($id);

            if (!$hotel) {
                return response()->json([
                    'success' => false,
                    'message' => '查無資料'
                ], 404);
            }

            // 檢查名稱是否重複（排除自己）
            $exists = AdminHotel::where('hotelName', $request->hotelName)
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => '景點名稱已存在，請勿重複使用'
                ], 422);
            }

            $data = $request->all();

            $classNos = explode(',', $request->hotelClassNo);
            $classNos = array_map('trim', $classNos);

            $classes = AdminHotelClass::whereIn('hotelClassNo', $classNos)->get();

            $data['hotelClassName'] = $classes->pluck('hotelClassName')->implode(',');
            $data['hotelClassName2'] = $classes->pluck('hotelClassName2')->implode(',');

            $hotel->update($data);

            return response()->json([
                'success' => true,
                'message' => '已更新',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '操作失敗，請稍後再試。'
            ], 500);
        }
    }

    // DELETE /api/hotel/{id} - 刪除旅宿
    public function destroy($id)
    {
        $hotel = AdminHotel::find($id);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $hotel->delete();

        return response()->json([
            "success" => true,
            "msg" => "已刪除"
        ]);
    }
}
