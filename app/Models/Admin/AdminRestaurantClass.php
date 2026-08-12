<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminRestaurantClass extends Model
{
    public $timestamps = false;
    protected $table = 'restaurant_class';
    protected $fillable = ['id', 'cuisineClassNo', 'cuisineClassName', 'cuisineClassName2'];
}
