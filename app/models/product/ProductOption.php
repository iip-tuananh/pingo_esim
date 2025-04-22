<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $table = 'product_options';
    protected $fillable = ['product_id', 'sku', 'name', 'price_usd', 'price_vnd'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
