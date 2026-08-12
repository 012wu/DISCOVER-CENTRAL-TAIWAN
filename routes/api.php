<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAttractionController;
use App\Http\Controllers\Admin\AdminAttractionClassController;
use App\Http\Controllers\Admin\AdminHotelController;
use App\Http\Controllers\Admin\AdminHotelClassController;
use App\Http\Controllers\Admin\AdminRestaurantController;
use App\Http\Controllers\Admin\AdminRestaurantClassController;

// 景點 CRUD
Route::get('/attraction', [AdminAttractionController::class, 'index']); // 查全部
Route::get('/attraction/{id}', [AdminAttractionController::class, 'show']);  // 查單筆
Route::post('/attraction', [AdminAttractionController::class, 'store']);  // 新增
Route::put('/attraction/{id}', [AdminAttractionController::class, 'update']); // 修改
Route::delete('/attraction/{id}', [AdminAttractionController::class, 'destroy']); // 刪除

// 景點分類 CRUD
Route::get('/attractionClass', [AdminAttractionClassController::class, 'index']);
Route::get('/attractionClass/{id}', [AdminAttractionClassController::class, 'show']);
Route::post('/attractionClass', [AdminAttractionClassController::class, 'store']);
Route::put('/attractionClass/{id}', [AdminAttractionClassController::class, 'update']);
Route::delete('/attractionClass/{id}', [AdminAttractionClassController::class, 'destroy']);


// 旅宿 CRUD
Route::get('/hotel', [AdminHotelController::class, 'index']); // 查全部
Route::get('/hotel/{id}', [AdminHotelController::class, 'show']);  // 查單筆
Route::post('/hotel', [AdminHotelController::class, 'store']);  // 新增
Route::put('/hotel/{id}', [AdminHotelController::class, 'update']); // 修改
Route::delete('/hotel/{id}', [AdminHotelController::class, 'destroy']); // 刪除



// 旅宿分類 CRUD
Route::get('/hotelClass', [AdminHotelClassController::class, 'index']); // 查全部
Route::get('/hotelClass/{id}', [AdminHotelClassController::class, 'show']);  // 查單筆
Route::post('/hotelClass', [AdminHotelClassController::class, 'store']);  // 新增
Route::put('/hotelClass/{id}', [AdminHotelClassController::class, 'update']); // 修改
Route::delete('/hotelClass/{id}', [AdminHotelClassController::class, 'destroy']); // 刪除



// 餐廳 CRUD
Route::get('/restaurant', [AdminRestaurantController::class, 'index']); // 查全部
Route::get('/restaurant/{id}', [AdminRestaurantController::class, 'show']);  // 查單筆
Route::post('/restaurant', [AdminRestaurantController::class, 'store']);  // 新增
Route::put('/restaurant/{id}', [AdminRestaurantController::class, 'update']); // 修改
Route::delete('/restaurant/{id}', [AdminRestaurantController::class, 'destroy']); // 刪除



// 餐廳分類 CRUD
Route::get('/restaurantClass', [AdminRestaurantClassController::class, 'index']); // 查全部
Route::get('/restaurantClass/{id}', [AdminRestaurantClassController::class, 'show']);  // 查單筆
Route::post('/restaurantClass', [AdminRestaurantClassController::class, 'store']);  // 新增
Route::put('/restaurantClass/{id}', [AdminRestaurantClassController::class, 'update']); // 修改
Route::delete('/restaurantClass/{id}', [AdminRestaurantClassController::class, 'destroy']); // 刪除