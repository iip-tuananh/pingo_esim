<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\CarServicePrices;
use App\models\CarType;
use App\models\Province;
use Illuminate\Support\Facades\DB;

class CarServicePricesController extends Controller
{
    public function list()
    {
        $data = CarType::with('carServicePrices')->get();
        $data = $data->map(function ($item) {
            $item->images = json_decode($item->images);
            $details = [];
            foreach ($item->carServicePrices as $itemDetail) {
                $list_prices = [];
                $key = $itemDetail->car_type_id . '_' . $itemDetail->province_id;
                if (!isset($details[$key])) {
                    $details[$key]['province_id'] = $itemDetail->province_id;
                    $details[$key]['province_name'] = Province::where('id', $itemDetail->province_id)->first()->name;
                }
                $list_prices = [
                    'place_from' => $itemDetail->place_from,
                    'place_to' => $itemDetail->place_to,
                    'price_min' => $itemDetail->price_min,
                    'price_max' => $itemDetail->price_max,
                    'price_2_way' => $itemDetail->price_2_way,
                ];
                $details[$key]['list_prices'][] = $list_prices;
            }
            $item->details = array_values($details);
            return $item;
        });
        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        try {
            if ($request->data) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                CarServicePrices::truncate();
                CarType::truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');

                foreach ($request->data as $value) {
                    $carType = new CarType();
                    $carType->name = $value['name'];
                    $carType->slug = to_slug($value['name']);
                    $carType->images = $value['images'] ? json_encode($value['images']) : null;
                    $carType->description = $value['description'] ?? null;
                    $carType->save();

                    foreach ($value['details'] as $valueDetail) {
                        foreach ($valueDetail['list_prices'] as $valuePrice) {
                            $province = Province::where('id', $valueDetail['province_id'])->first();
                            $carServicePrices = new CarServicePrices();
                            $carServicePrices->car_type_id = $carType->id;
                            $carServicePrices->province_id = $valueDetail['province_id'];
                            $carServicePrices->province_name = $province->name;
                            $carServicePrices->place_from = $valuePrice['place_from'];
                            $carServicePrices->place_to = $valuePrice['place_to'];
                            $carServicePrices->price_min = $valuePrice['price_min'];
                            $carServicePrices->price_max = $valuePrice['price_max'];
                            $carServicePrices->price_2_way = $valuePrice['price_2_way'];
                            $carServicePrices->save();
                        }
                    }
                }
            }
            return response()->json([
                'message' => 'Thao tác thành công',
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Thao tác thất bại',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
