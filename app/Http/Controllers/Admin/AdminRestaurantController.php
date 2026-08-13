<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminRestaurant;
use App\Models\Admin\AdminRestaurantClass;
use Illuminate\Support\Facades\Session;

class AdminRestaurantController extends Controller
{
    // GET /admin/Restaurant - 取得全部餐飲（純 API，不分頁不篩選，維持原本用途）
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

    // GET /admin/Restaurant/{id} - 取得單筆餐飲
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

    // GET /admin/Restaurant/list - 後台餐飲列表頁（含篩選、排序、分頁，供 Blade 頁面渲染）
    public function list(Request $req)
    {
        $query = AdminRestaurant::query();
        $restaurantClasses = AdminRestaurantClass::all();

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
            case '餐飲編號（小->大）':
                $query->orderBy('Restaurantid');
                break;
            case '名稱 (筆劃少->多)':
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


        // 把 $list 傳-> Restaurant.blade.php
        return view('admin.Restaurant', compact('list', 'restaurantClasses'));
    }

    // POST /api/Restaurant - 新增餐飲
    public function store(Request $request)
    {
        try {
            // 檢查名稱是否重複
            $exists = AdminRestaurant::where('restaurantName', $request->restaurantName)->exists();
            if ($exists) {
                return response()->json([
                    "success" => false,
                    "message" => '景點名稱已存在，請勿重複新增'
                ], 422);
            }
            // 取得使用者輸入的所有資料
            $data = $request->all();

            // 例如 A01,A02
            $classNos = explode(',', $request->cuisineClassNo);

            // 去掉空白
            $classNos = array_map('trim', $classNos);

            // 到分類表查詢
            $classes = AdminRestaurantClass::whereIn('cuisineClassNo', $classNos)->get();

            // 組合分類名稱，存回 $data
            $data['cuisineClassName'] = $classes->pluck('cuisineClassName')->implode(',');
            $data['cuisineClassName2'] = $classes->pluck('cuisineClassName2')->implode(',');

            $cuisine = AdminRestaurant::create($data);

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

    // PUT /api/Restaurant/{id} - 更新餐飲
    public function update(Request $request, $id)
    {
        try {
            $Restaurant = AdminRestaurant::find($id);

            if (!$Restaurant) {
                return response()->json([
                    'success' => false,
                    'message' => '查無資料'
                ], 404);
            }
            // 檢查名稱是否重複（排除自己）
            $exists = AdminRestaurant::where('restaurantName', $request->restaurantName)
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => '景點名稱已存在，請勿重複使用'
                ], 422);
            }

            $data = $request->all();

            $classNos = explode(',', $request->cuisineClassNo);
            $classNos = array_map('trim', $classNos);

            $classes = AdminRestaurantClass::whereIn('cuisineClassNo', $classNos)->get();

            $data['cuisineClassName'] = $classes->pluck('cuisineClassName')->implode(',');
            $data['cuisineClassName2'] = $classes->pluck('cuisineClassName2')->implode(',');

            $Restaurant->update($data);

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

    // DELETE /api/Restaurant/{id} - 刪除餐飲
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
