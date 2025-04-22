<?php

namespace App\Http\Controllers\Api\Voucher;

use App\Http\Controllers\Controller;
use App\models\product\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function create(Request $request, Voucher $voucher)
    {
        $data = $voucher->createOrUpdateVoucher($request);
        return response()->json([
            'success' => true,
            'message' => 'Save Success',
            'data' => $data
        ], 200);
    }
    public function list(Request $request)
    {
        $keyword = $request->keyword;
        $data = Voucher::query()->orderBy('id', 'DESC');
        if (!empty($keyword)) {
            $data->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $data = $data->get();
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function delete($id)
    {
        $query = Voucher::find($id);
        $query->delete();
        return response()->json(['message' => 'Delete Success'], 200);
    }
    public function edit($id)
    {
        $data = Voucher::where([
            'id' => $id
        ])
            ->first();
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
}
