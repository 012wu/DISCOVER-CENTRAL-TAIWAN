<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminRestaurantClass;
use Illuminate\Support\Facades\Session;

class AdminRestaurantClassController extends Controller
{
    // GET /admin/restaurant - 取得全部分類（純 API，不分頁不篩選，維持原本用途）
    public function index(Request $req)
    {
        $list = AdminRestaurantClass::all();
        return response()->json([
            "success" => true,
            "msg" => [
                "data" => $list
            ]
        ]);
    }

    // GET /admin/restaurantClass/{id} - 取得單筆分類
    public function show($id)
    {
        $item = AdminRestaurantClass::find($id);

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

    // GET /admin/restaurantClass/list - 後台分類列表頁（含篩選、排序、分頁，供 Blade 頁面渲染）
    public function list(Request $req)
    {
        $query = AdminRestaurantClass::query();

        // 關鍵字篩選：比對名稱與詳細描述
        $keyword = $req->input('keyword');

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('restaurantClassNo', 'like', "%{$keyword}%")
                    ->orWhere('restaurantClassName', 'like', "%{$keyword}%")
                    ->orWhere('restaurantClassName2', 'like', "%{$keyword}%");
            });
        }
        // 排序方式
        $rank = $req->input('rank');
        switch ($rank) {
            case '分類代碼（小->大）':
                $query->orderBy('restaurantClassNo');
                break;
            case '名稱 (筆劃少->多)':
                $query->orderBy('restaurantClassName');
                break;
            case '名稱 (筆劃多->少)':
                $query->orderByDesc('restaurantClassName');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        // 每頁幾筆
        $pageSize = $req->input('pageSize', 15);

        // 分頁
        $list = $query->paginate($pageSize)->withQueryString();
        // 把 $list 傳-> restaurantClass.blade.php
        return view('admin.restaurantClass', compact('list'));
    }


    // POST /api/restaurantClass - 新增分類
    public function store(Request $request)
    {
        $restaurant = AdminRestaurantClass::create($request->all());


        return response()->json([
            "success" => true,
            "msg" => "已新增"
        ]);
    }

    // PUT /api/restaurantClass/{id} - 更新分類
    public function update(Request $request, $id)
    {
        $restaurantClass = AdminRestaurantClass::find($id);

        if (!$restaurantClass) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $restaurantClass->update($request->all());

        return response()->json([
            "success" => true,
            "msg" => "已修改"
        ]);
    }

    // DELETE /api/restaurant/{id} - 刪除分類
    public function destroy($id)
    {
        $restaurantClass = AdminRestaurantClass::find($id);

        if (!$restaurantClass) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $restaurantClass->delete();

        return response()->json([
            "success" => true,
            "msg" => "已刪除"
        ]);
    }
}
