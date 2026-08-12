<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminHotel;
use Illuminate\Support\Facades\Session;

class AdminhotelController extends Controller
{
    // GET /admin/Hotel - 取得全部景點（純 API，不分頁不篩選，維持原本用途）
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

    // GET /admin/hotel/{id} - 取得單筆景點
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

    // GET /admin/hotel/list - 後台景點列表頁（含篩選、排序、分頁，供 Blade 頁面渲染）
    public function list(Request $req)
    {
        $query = AdminHotel::query();

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
            case '景點編號（小到大）':
                $query->orderBy('hotelid');
                break;
            case '名稱 (筆劃少到多)':
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


        // 把 $list 傳到 hotel.blade.php
        return view('admin.hotel', compact('list'));
    }

    // POST /api/hotel - 新增景點
    public function store(Request $request)
    {
        $hotel = AdminHotel::create($request->all());

        return response()->json([
            "success" => true,
            "msg" => "已新增"
        ]);
    }

    // PUT /api/hotel/{id} - 更新景點
    public function update(Request $request, $id)
    {
        $hotel = AdminHotel::find($id);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $hotel->update($request->all());

        return response()->json([
            'success' => true,
            'message' => '已更新',
        ]);
    }

    // DELETE /api/hotel/{id} - 刪除景點
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
