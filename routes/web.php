<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminAttractionController;
use App\Http\Controllers\Admin\AdminAttractionClassController;
use App\Http\Controllers\Admin\AdminHotelController;
use App\Http\Controllers\Admin\AdminHotelClassController;
use App\Http\Controllers\Admin\AdminRestaurantController;
use App\Http\Controllers\Admin\AdminRestaurantClassController;


// 給瀏覽器直接訪問的網頁(回傳通常是 HTML 頁面(view())
// 走 web middleware,自動有 Session、CSRF 保護
Route::get('/', function () {
    return view('index');
});

Route::get('/home', function () {
    return view('home');
});
// 抓event資料用
// 註冊一條 GET 路由，網址為 /data/eventlist.json
// 當前端 fetch('/data/eventlist.json') 時，會觸發下面這個匿名函式
Route::get('/data/eventlist.json', function () {
    // resource_path('data/eventlist.json')
    // → 組出伺服器實體檔案的完整路徑，即 resources/data/eventlist.json
    // response()->file(檔案路徑, [Headers])
    // → 讀取該檔案內容，並包裝成 HTTP 回應直接回傳給瀏覽器
    return response()->file(resource_path('data/eventlist.json'), [
        // 手動指定回應標頭 Content-Type 為 application/json
        // → 讓瀏覽器 / fetch().json() 正確把回傳內容解析成 JSON
        //   （若不指定，可能被當成純文字，前端解析會失敗）
        'Content-Type' => 'application/json'
    ]);
    // 函式結束，回傳值就是這個 HTTP 回應
});
Route::get('/api/weather', [AdminController::class, 'weather']);

Route::get('/about', function () {
    return view('about');
});

Route::get('/attraction', function () {
    return view('/attraction');
});

Route::get('/hotel', function () {
    return view('/hotel');
});
Route::get('/restaurant', function () {
    return view('/restaurant');
});


//登入
Route::get('/admin/login', [AdminController::class, 'login']);
//登入
Route::post('/admin/login', [AdminController::class, 'doLogin']);
//登出
Route::post('/admin/logout', [AdminController::class, 'logout']);
//後臺首頁
Route::get('/admin/adminhome', [AdminController::class, 'adminhome']);

Route::get('/admin/attraction', [AdminAttractionController::class, 'list']);
Route::get('/admin/attractionClass', [AdminAttractionClassController::class, 'list']);


Route::get('/admin/hotel', [AdminHotelController::class, 'list']);
Route::get('/admin/hotelClass', [AdminHotelClassController::class, 'list']);

Route::get('/admin/restaurant', [AdminRestaurantController::class, 'list']);
Route::get('/admin/restaurantClass', [AdminRestaurantClassController::class, 'list']);
