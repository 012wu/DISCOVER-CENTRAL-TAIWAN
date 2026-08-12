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
}
