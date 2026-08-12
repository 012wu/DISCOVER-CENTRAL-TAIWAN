<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\AdminAttractionClass;
use Illuminate\Support\Facades\Session;

class AdminAttractionClassController extends Controller
{
    // GET /admin/attraction - 取得全部分類（純 API，不分頁不篩選，維持原本用途）
    public function index(Request $req)
    {
        $list = AdminAttractionClass::all();
        return response()->json([
            "success" => true,
            "msg" => [
                "data" => $list
            ]
        ]);
    }

    // GET /admin/attractionClass/{id} - 取得單筆分類
    public function show($id)
    {
        $item = AdminAttractionClass::find($id);

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

    // GET /admin/attractionClass/list - 後台分類列表頁（含篩選、排序、分頁，供 Blade 頁面渲染）
    public function list(Request $req)
    {
        $query = AdminAttractionClass::query();

        // 關鍵字篩選：比對名稱與詳細描述
        $keyword = $req->input('keyword');
        
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
            $q->where('attractionClassNo', 'like', "%{$keyword}%")
                ->orWhere('attractionClassName', 'like', "%{$keyword}%")
                ->orWhere('attractionClassName2', 'like', "%{$keyword}%");
            });
        }
        // 排序方式
        $rank = $req->input('rank');
        switch ($rank) {
            case '分類代碼（小->大）':
                $query->orderBy('attractionClassNo');
                break;
            case '名稱 (筆劃少->多)':
                $query->orderBy('attractionClassName');
                break;
            case '名稱 (筆劃多->少)':
                $query->orderByDesc('attractionClassName');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        // 每頁幾筆
        $pageSize = $req->input('pageSize', 15);

        // 分頁
        $list = $query->paginate($pageSize)->withQueryString();
        // 把 $list 傳-> attractionClass.blade.php
        return view('admin.attractionClass', compact('list'));
        }


    // POST /api/attractionClass - 新增分類
    public function store(Request $request)
    {
        $attraction = AdminAttractionClass::create($request->all());

        
        return response()->json([
            "success" => true,
            "msg" => "已新增"
        ]);

    }

    // PUT /api/attractionClass/{id} - 更新分類
    public function update(Request $request, $id)
    {
        $attractionClass = AdminAttractionClass::find($id);
       
        if (!$attractionClass) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $attractionClass->update($request->all());

        return response()->json([
            "success" => true,
            "msg" => "已修改"
        ]);
    }

    // DELETE /api/attraction/{id} - 刪除分類
    public function destroy($id)
    {
        $attractionClass = AdminAttractionClass::find($id);

        if (!$attractionClass) {
            return response()->json([
                'success' => false,
                'message' => '查無資料'
            ], 404);
        }

        $attractionClass->delete();

        return response()->json([
            "success" => true,
            "msg" => "已刪除"
        ]);

    }
}
