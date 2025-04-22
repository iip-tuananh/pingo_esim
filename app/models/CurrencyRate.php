<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    protected $table = 'currency_rates';
    protected $fillable = ['currency', 'rate', 'base_currency'];

    public function nationalCurrency(){
        return $this->belongsTo(Currency::class, 'currency', 'code');
    }
}
