<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminHotel extends Model
{
    public $timestamps = false;
    protected $table = 'hotel';
    protected $fillable = [
        'id',
        'hotelID',
        'hotelLicenseNumber',
        'hotelName',
        'hotelClassNo',
        'hotelClassName',
        'hotelClassName2',
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
        'roomInfo',
        'lowestPrice',
        'ceilingPrice',
        'createTime',
        'updateTime'
    ];

    // 單一 hotelClassNo 時使用
    public function hotelClass()
    {
        return $this->belongsTo(
            AdminHotelClass::class, // 要連哪個 Model
            'hotelClassNo',         // hotel 表的欄位
            'hotelClassNo'          // hotelClass 表的欄位
        );
    }
    // 多個 hotelClassNo 時使用
    public function getHotelClassList()
    {
        $classNos = explode(',', $this->hotelClassNo);

        return AdminHotelClass::whereIn(
            'hotelClassNo',
            $classNos
        )->get();
    }
}
