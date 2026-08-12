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
}
