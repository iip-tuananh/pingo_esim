<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';
    protected $fillable = [
        'code',
        'name',
        'value',
        'status',
        'from_date',
        'to_date',
        'product_ids',
        'limit_product_qty',
        'limit_bill_value',
        'is_apply_all_product',
        'content',
        'quantity',
        'description',
    ];

    public function createOrUpdateVoucher($request)
    {
        $voucher = $this->find($request->id);
        if ($voucher) {
            $request->merge(['product_ids' => json_encode($request->product_ids)]);
            $voucher->update($request->all());
        } else {
            $request->merge(['product_ids' => json_encode($request->product_ids)]);
            $voucher = $this->create($request->all());
        }
        return $voucher;
    }
}
