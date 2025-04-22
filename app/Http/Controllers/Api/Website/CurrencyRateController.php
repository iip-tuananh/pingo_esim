<?php

namespace App\Http\Controllers\Api\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\CurrencyRate;

class CurrencyRateController extends Controller
{
    public function createOrUpdateCurrencyRate(Request $request)
    {
    	if($request->data){
    		Banner::truncate();

	    	foreach ($request->data as $key => $value) {
	    		Banner::updateOrCreate(
				    [
                        'image' => $value['image'],
				        'status' =>$value['status'],
                        'title' => $value['title'],
                        'description' => $value['description'],
				        'link' => $value['link'],
				 	]
				);
	    	}
    	}
    	return response()->json([
            'messenge' => 'success'
        ],200);
    }
    public function listCurrencyRate(Request $request)
    {
    	$data = CurrencyRate::query();
    	if($request->base_currency){
    		$data->where('base_currency', 'like', '%'.$request->base_currency.'%');
    	}
    	if($request->currency){
    		$data->where('currency', 'like', '%'.$request->currency.'%');
    	}
    	$data = $data->get();
    	return response()->json([
            'messenge' => 'success',
            'data' => $data
        ],200);
    }
}
