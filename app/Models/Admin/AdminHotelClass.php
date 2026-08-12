<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminHotelClass extends Model
{
    public $timestamps = false;
    protected $table = 'hotel_class';
    protected $fillable = ['id', 'hotelClassNo', 'hotelClassName', 'hotelClassName2'];
}
