<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminAttraction extends Model
{
    public $timestamps = false;
    protected $table = 'attraction';
    protected $fillable = [
        'id',
        'attractionID',
        'attractionName',
        'attractionClassNo',
        'attractionClassName',
        'attractionClassName2',
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
        'websiteURL',
        'createTime',
        'updateTime'
    ];
}
