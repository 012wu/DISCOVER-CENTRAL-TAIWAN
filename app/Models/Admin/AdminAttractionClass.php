<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminAttractionClass extends Model
{
    public $timestamps = false;
    protected $table = 'attraction_class';
    protected $fillable = ['id', 'attractionClassNo', 'attractionClassName', 'attractionClassName2'];
}
