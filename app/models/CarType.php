<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    protected $table = 'car_types';
    protected $fillable = ['name', 'images', 'description', 'status'];

    public function carServicePrices()
    {
        return $this->hasMany(CarServicePrices::class);
    }
}
