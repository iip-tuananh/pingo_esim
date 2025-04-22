<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $fillable = ['code', 'name', 'symbol'];

    public function countries()
    {
        return $this->hasMany(Country::class, 'currency_code', 'code');
    }
}
