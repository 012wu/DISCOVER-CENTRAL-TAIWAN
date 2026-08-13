<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\AdminAttractionClass;


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
    // 單一 attractionClassNo 時使用
    public function attractionClass()
    {
        return $this->belongsTo(
            AdminAttractionClass::class, // 要連哪個 Model
            'attractionClassNo',         // attraction 表的欄位
            'attractionClassNo'          // attractionClass 表的欄位
        );
    }
    // 多個 attractionClassNo 時使用
    public function getAttractionClassList()
    {
        $classNos = explode(',', $this->attractionClassNo);

        return AdminAttractionClass::whereIn(
            'attractionClassNo',
            $classNos
        )->get();
    }
}
