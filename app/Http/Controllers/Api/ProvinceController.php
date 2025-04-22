<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\BannerAds;
use App\models\Province;

class ProvinceController extends Controller
{
    public function list()
    {
        $data = Province::all();
        return response()->json([
            'data' => $data
        ]);
    }
}
