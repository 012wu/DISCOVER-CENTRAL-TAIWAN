<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminAttraction;
use App\Models\Admin\AdminAttractionClass;
use Illuminate\Support\Facades\Session;

class AdminAttractionController extends Controller
{
    // GET /admin/attraction - 取得全部景點（純 API，不分頁不篩選，維持原本用途）
    public function index(Request $req)
    {
        $list = AdminAttraction::all();
        return response()->json([
            "success" => true,
            "msg" => [
                "data" => $list
            ]
        ]);
    }

    // GET /admin/attraction/{id} - 取得單筆景點
    public function show($id)
    {
        $item = AdminAttraction::find($id);

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

    // GET /admin/attraction/list - 後台景點列表頁（含篩選、排序、分頁，供 Blade 頁面渲染）
    public function list(Request $req)
    {
        $query = AdminAttraction::query();
        // 取得所有分類
        $attractionClasses = AdminAttractionClass::all();

        // 縣市篩選
        $city = $req->input('city');
        if ($city && $city !== '全部縣市') {
            $query->where('city', $city);
        }

        // 分類篩選
        $attractionClass = $req->input('attractionClass');
        if ($attractionClass && $attractionClass !== '熱門分類') {
            $query->where('attractionClassName2', $attractionClass);
        }

        // 關鍵字篩選：比對名稱與詳細描述
        $keyword = $req->input('keyword');

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('attractionName', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('town', 'like', "%{$keyword}%")
                    ->orWhere('streetAddress', 'like', "%{$keyword}%");
            });
        }
        // 排序方式
        $rank = $req->input('rank');
        switch ($rank) {
            case '景點編號（小->大）':
                $query->orderByRaw('CAST(attractionid AS UNSIGNED) ASC');
                break;
            case '名稱 (筆劃少->多)':
                $query->orderBy('attractionName', 'asc');
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


        // 把 $list 傳到 attraction.blade.php
        return view('admin.attraction', compact('list', 'attractionClasses'));
    }

    // POST /api/attraction - 新增景點
    public function store(Request $request)
    {
        try {
            // 檢查名稱是否重複
            $exists = AdminAttraction::where('attractionName', $request->attractionName)->exists();
            if ($exists) {
                return response()->json([
                    "success" => false,
                    "message" => '景點名稱已存在，請勿重複新增'
                ], 422);
            }

            $data = $request->all();

            $classNos = explode(',', $request->attractionClassNo);
            $classNos = array_map('trim', $classNos);

            $classes = AdminAttractionClass::whereIn('attractionClassNo', $classNos)->get();

            $data['attractionClassName'] = $classes->pluck('attractionClassName')->implode(',');
            $data['attractionClassName2'] = $classes->pluck('attractionClassName2')->implode(',');
            $data['createTime'] = now();
            $data['updateTime'] = now();

            $attraction = AdminAttraction::create($data);

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

    // PUT /api/attraction/{id} - 更新景點
    public function update(Request $request, $id)
    {
        try {
            $attraction = AdminAttraction::find($id);

            if (!$attraction) {
                return response()->json([
                    'success' => false,
                    'message' => '查無資料'
                ], 404);
            }

            // 檢查名稱是否重複（排除自己）
            $exists = AdminAttraction::where('attractionName', $request->attractionName)
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => '景點名稱已存在，請勿重複使用'
                ], 422);
            }

            $data = $request->all();

            $classNos = explode(',', $request->attractionClassNo);
            $classNos = array_map('trim', $classNos);

            $classes = AdminAttractionClass::whereIn('attractionClassNo', $classNos)->get();

            $data['attractionClassName'] = $classes->pluck('attractionClassName')->implode(',');
            $data['attractionClassName2'] = $classes->pluck('attractionClassName2')->implode(',');
            $data['updateTime'] = now();

            $attraction->update($data);

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

    // DELETE /api/attraction/{id} - 刪除景點
    public function destroy($id)
    {
        $attraction = AdminAttraction::find($id);

        if (!$attraction) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $attraction->delete();

        return response()->json([
            "success" => true,
            "msg" => "已刪除"
        ]);
    }
}
