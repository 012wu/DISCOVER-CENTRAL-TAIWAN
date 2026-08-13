<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class AdminController extends Controller
{
    public function home()
    {
        return view("admin.home");
    }
    // 顯示登入頁
    public function login()
    {
        return view("admin.login");
    }

    // 登入驗證
    public function doLogin(Request $req)
    {
        // 取得 Vue 傳過來的帳號密碼
        $account = $req->account;
        $pwd = $req->pwd;

        // 到 staff 資料表找帳號
        //參數化查詢
        $staff = DB::table('staff')
            ->where('account', $account)
            ->first();

        // 找不到帳號
        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => '帳號不存在'
            ]);
        }

        // 比對密碼
        if ($staff->pwd != $pwd) {
            return response()->json([
                'success' => false,
                'message' => '密碼錯誤'
            ]);
        }

        // 帳號密碼都正確，儲存帳號密碼資料
        session([
            'account' => $staff->account,
            'pwd' => $staff->pwd,

        ]);
        return response()->json([
            'success' => true,
            'message' => '登入成功'
        ]);
    }
    //登出頁面
    public function logout()
    {
        Session::forget("account");
        Session::forget("pwd");
        return response()->json([
            "success" => true
        ]);
    }


    public function adminhome()
    {
        return view("admin.adminhome");
    }
    public function weather()
    {
        $apiKey = env('CWA_API_KEY');

        $response = Http::get(
            'https://opendata.cwa.gov.tw/api/v1/rest/datastore/F-C0032-001',
            [
                'Authorization' => $apiKey,
                'format' => 'JSON'
            ]
        );

        return $response->json();
    }
}
