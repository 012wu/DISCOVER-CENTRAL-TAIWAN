<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Adminrestaurant extends Model
{
    public $timestamps = false;
    protected $table = 'restaurant';
    protected $fillable = [
        'id',
        'restaurantID',
        'restaurantName',
        'cuisineClassNo',
        'cuisineClassName',
        'cuisineClassName2',
        'description',
        'positionLat',
        'positionLon',
        'zipCode',
        'city',
        'town',
        'streetAddress',
        'fullAddress',
        'tel',
        'img1',
        'createTime',
        'updateTime'
    ];
    // 單一 attractionClassNo 時使用
    public function restaurantClass()
    {
        return $this->belongsTo(
            AdminRestaurantClass::class, // 要連哪個 Model
            'cuisineClassNo',         // attraction 表的欄位
            'cuisineClassNo'          // attractionClass 表的欄位
        );
    }
    // 多個 attractionClassNo 時使用
    public function getAttractionClassList()
    {
        $classNos = explode(',', $this->cuisineClassNo);

        return AdminRestaurantClass::whereIn(
            'cuisineClassNo',
            $classNos
        )->get();
    }
}
