<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CarServicePrices extends Model
{
    protected $table = 'car_service_prices';
    protected $fillable = ['car_type_id', 'province_id', 'province_name', 'place_from', 'place_to', 'price_min', 'price_max'];

    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
