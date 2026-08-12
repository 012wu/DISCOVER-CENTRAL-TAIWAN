<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminHotelClass;
use Illuminate\Support\Facades\Session;

class AdminHotelClassController extends Controller
{
    // GET /admin/hotel - 取得全部分類（純 API，不分頁不篩選，維持原本用途）
    public function index(Request $req)
    {
        $list = AdminHotelClass::all();
        return response()->json([
            "success" => true,
            "msg" => [
                "data" => $list
            ]
        ]);
    }

    // GET /admin/hotelClass/{id} - 取得單筆分類
    public function show($id)
    {
        $item = AdminHotelClass::find($id);

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

    // GET /admin/hotelClass/list - 後台分類列表頁（含篩選、排序、分頁，供 Blade 頁面渲染）
    public function list(Request $req)
    {
        $query = AdminHotelClass::query();

        // 關鍵字篩選：比對名稱與詳細描述
        $keyword = $req->input('keyword');

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('hotelClassNo', 'like', "%{$keyword}%")
                    ->orWhere('hotelClassName', 'like', "%{$keyword}%")
                    ->orWhere('hotelClassName2', 'like', "%{$keyword}%");
            });
        }
        // 排序方式
        $rank = $req->input('rank');
        switch ($rank) {
            case '分類代碼（小到大）':
                $query->orderBy('hotelClassNo');
                break;
            case '名稱 (筆劃少到多)':
                $query->orderBy('hotelClassName');
                break;
            case '名稱 (筆劃多到少)':
                $query->orderByDesc('hotelClassName');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        // 每頁幾筆
        $pageSize = $req->input('pageSize', 15);

        // 分頁
        $list = $query->paginate($pageSize)->withQueryString();
        // 把 $list 傳到 hotelClass.blade.php
        return view('admin.hotelClass', compact('list'));
    }


    // POST /api/hotelClass - 新增分類
    public function store(Request $request)
    {
        $hotel = AdminHotelClass::create($request->all());


        return response()->json([
            "success" => true,
            "msg" => "已新增"
        ]);
    }

    // PUT /api/hotelClass/{id} - 更新分類
    public function update(Request $request, $id)
    {
        $hotelClass = AdminHotelClass::find($id);

        if (!$hotelClass) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $hotelClass->update($request->all());

        return response()->json([
            "success" => true,
            "msg" => "已修改"
        ]);
    }

    // DELETE /api/hotel/{id} - 刪除分類
    public function destroy($id)
    {
        $hotelClass = AdminhotelClass::find($id);

        if (!$hotelClass) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $hotelClass->delete();

        return response()->json([
            "success" => true,
            "msg" => "已刪除"
        ]);
    }
}
