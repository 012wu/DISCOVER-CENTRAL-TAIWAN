<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminAttraction;
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
            case '景點編號（小到大）':
                $query->orderBy('attractionid');
                break;
            case '名稱 (筆劃少到多)':
                $query->orderBy('attractionName');
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
        return view('admin.attraction', compact('list'));
    }

    // POST /api/attraction - 新增景點
    public function store(Request $request)
    {
        $attraction = AdminAttraction::create($request->all());

        return response()->json([
            "success" => true,
            "msg" => "已新增"
        ]);
    }

    // PUT /api/attraction/{id} - 更新景點
    public function update(Request $request, $id)
    {
        $attraction = AdminAttraction::find($id);

        if (!$attraction) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $attraction->update($request->all());

        return response()->json([
            'success' => true,
            'message' => '已更新',
        ]);
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
