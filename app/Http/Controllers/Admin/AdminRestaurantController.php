<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminRestaurant;
use Illuminate\Support\Facades\Session;

class AdminRestaurantController extends Controller
{
    // GET /admin/Restaurant - 取得全部景點（純 API，不分頁不篩選，維持原本用途）
    public function index(Request $req)
    {
        $list = AdminRestaurant::all();
        return response()->json([
            "success" => true,
            "msg" => [
                "data" => $list
            ]
        ]);
    }

    // GET /admin/Restaurant/{id} - 取得單筆景點
    public function show($id)
    {
        $item = AdminRestaurant::find($id);

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

    // GET /admin/Restaurant/list - 後台景點列表頁（含篩選、排序、分頁，供 Blade 頁面渲染）
    public function list(Request $req)
    {
        $query = AdminRestaurant::query();

        // 縣市篩選
        $city = $req->input('city');
        if ($city && $city !== '全部縣市') {
            $query->where('city', $city);
        }

        // 分類篩選
        $RestaurantClass = $req->input('RestaurantClass');
        if ($RestaurantClass && $RestaurantClass !== '熱門分類') {
            $query->where('RestaurantClassName2', $RestaurantClass);
        }

        // 關鍵字篩選：比對名稱與詳細描述
        $keyword = $req->input('keyword');

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('RestaurantName', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('town', 'like', "%{$keyword}%")
                    ->orWhere('streetAddress', 'like', "%{$keyword}%");
            });
        }
        // 排序方式
        $rank = $req->input('rank');
        switch ($rank) {
            case '景點編號（小到大）':
                $query->orderBy('Restaurantid');
                break;
            case '名稱 (筆劃少到多)':
                $query->orderBy('RestaurantName');
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


        // 把 $list 傳到 Restaurant.blade.php
        return view('admin.Restaurant', compact('list'));
    }

    // POST /api/Restaurant - 新增景點
    public function store(Request $request)
    {
        $Restaurant = AdminRestaurant::create($request->all());

        return response()->json([
            "success" => true,
            "msg" => "已新增"
        ]);
    }

    // PUT /api/Restaurant/{id} - 更新景點
    public function update(Request $request, $id)
    {
        $Restaurant = AdminRestaurant::find($id);

        if (!$Restaurant) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $Restaurant->update($request->all());

        return response()->json([
            'success' => true,
            'message' => '已更新',
        ]);
    }

    // DELETE /api/Restaurant/{id} - 刪除景點
    public function destroy($id)
    {
        $Restaurant = AdminRestaurant::find($id);

        if (!$Restaurant) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $Restaurant->delete();

        return response()->json([
            "success" => true,
            "msg" => "已刪除"
        ]);
    }
}
