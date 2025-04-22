<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\product\Product;
use Session;
use App\models\product\Category;
use App\models\product\TypeProduct;
use DB,stdClass,File;
use App\models\District;
use Goutte\Client;
use App\models\blog\Blog;
use App\models\CarType;
use App\models\MessContact;
use App\models\Services;
use App\models\ServiceCate;
use App\models\website\Prize;
use App\models\website\Founder;
use App\models\website\Partner;
use App\models\ReviewCus;
use App\models\PageContent;
use App\models\Project;
use App\models\website\Faq;

class PageController extends Controller
{
    public function orderNow()
    {
        return view('orderNow');
    }
    public function baogia()
    {
        $carTypes = CarType::with(['carServicePrices' => function ($query) {
            $query->with('province');
        }])->get();

        foreach ($carTypes as $carType) {
            $carServicePrices = $carType->carServicePrices->groupBy('province_id');
            $provinces = [];
            foreach ($carServicePrices as $key => $carServicePrice) {
                $provinces[$key]['province_id'] = $key;
                $provinces[$key]['province_name'] = $carServicePrice[0]->province->province_name;
                $provinces[$key]['listPrices'] = $carServicePrices[$key];
            }
            $carType->provinces = array_values($provinces);
            $carType->provinces = collect($carType->provinces)->map(function ($item) use ($carType) {
                $newRecords = [];
                foreach ($item['listPrices'] as $index1 => $carServicePrice1) {
                    foreach ($item['listPrices'] as $index2 => $carServicePrice2) {
                    if ($carServicePrice1->province_id == $carServicePrice2->province_id && $carServicePrice1->place_from == $carServicePrice2->place_to && $carServicePrice1->place_to == $carServicePrice2->place_from) {
                        if (isset($carServicePrice2->price_2_way) && $carServicePrice2->price_2_way > 0) {
                            $key1 = $index1 . '_' . $index2;
                            $key2 = $index2 . '_' . $index1;
                            if (!isset($newRecords[$key1]) && !isset($newRecords[$key2])) {
                                $newRecords[$key1] = [
                                    "type" => 'two_way',
                                    'parent_id' => $carServicePrice2->id,
                                    "car_type_id" => $carType->id,
                                    "province_id" => $carServicePrice1->province_id,
                                    "province_name" => $carServicePrice1->province->province_name,
                                    "place_from" => 'Hai chiều ('.$carServicePrice1->place_from.' <=> '.$carServicePrice1->place_to.')',
                                    "place_to" => 'Hai chiều ('.$carServicePrice1->place_to.' <=> '.$carServicePrice1->place_from.')',
                                    "price_min" => $carServicePrice2->price_2_way,
                                    "price_max" => $carServicePrice2->price_2_way,
                                    "price_2_way" => $carServicePrice2->price_2_way,
                                ];
                            }
                        }
                    }
                    }
                }
                foreach ($newRecords as $newRecord) {
                    $item['listPrices']->push(collect($newRecord));
                }

                // Sắp xếp lại theo `parent_id`
                $item['listPrices'] = $item['listPrices']->sortBy(function ($item) {
                    return $item['parent_id'] ?? $item['id'];
                })->values();
                return $item;
            });
        }
        return view('baogia', compact('carTypes'));
    }
    public function carType()
    {
        $carTypes = CarType::with(['carServicePrices' => function ($query) {
            $query->with('province');
        }])->get();
        return view('car_type', compact('carTypes'));
    }
    public function menu()
    {

        $data['allproduct'] = Product::where([
            ['status', '=', 1]
        ])->limit(9)->orderBy('id','DESC')->get(['id','name','discount','price','images','slug']);
        $data['hotnews'] = Blog::where([
            ['status','=',1],
            ['type_news','=','tin-hot']
        ])->orderBy('id','DESC')->limit(7)->get(['id','title','slug','created_at','image']);
        return view('menu',$data);
    }
    public function quickview($id){
        $data['product'] = Product::with('cate')->where('id',$id)->first();
        return view('layouts.product.quickview',$data);
    }
    public function aboutUs(){
        $data['reviewcus'] = ReviewCus::where(['status'=>1])->get();
        $data['album'] = Prize::where(['status'=>1])->get(['id','name','image']);
        $data['gioithieu'] = PageContent::where(['slug'=>'gioi-thieu','language'=>'vi'])->first(['id','title','content','image']);
        return view('aboutus',$data);
    }
    public function contact()
    {
        return view('contactus');
    }
    public function getPostInfor()
    {
        $data['category_product'] = Category::where('status',1)->get();
        return view('post_info.index',$data);
    }
    public function postPostInfor(Request $request,Product $product )
    {
        $data = $product->createClient($request);
        $data['category'] = Category::where(['status'=> 1])->orderBy('id','ASC')->get();
        $data['categoryFirst'] = Category::where(['status'=> 1])->orderBy('id','ASC')->first();
        $productNewFirstTab = Product::where([
            'category'=> $data['categoryFirst'] ? $data['categoryFirst']->id : 0,
            'status' => 0
        ])->with('customer')
        ->orderBy('id','ASC')
        ->limit(10)->get()->toArray();
        $data['productNewFirstTab'] = array_chunk($productNewFirstTab,2);
        return view('home',$data)->with('success','Tin của bạn đang được xét duyệt!');
    }
    public function typeproduct($id)
    {
        $arr = [];
        $data = TypeProduct::where('cate_id',$id)->get();
        $lang = Session::get('locale');
        foreach($data as $item){
            $obj = new stdClass();
            $obj->name = languageName($item->name);
            $obj->id = $item->id;
            $arr[] = $obj;
        }
        return response()->json([
    		'message' => 'get data Success',
    		'data'=> $arr
    	],200);
    }
    public function district($id)
    {
        $data = District::where('_province_id',$id)->get();
        return response()->json([
    		'message' => 'get data Success',
    		'data'=> $data
    	],200);
    }
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $code = Session::get('locale');
        $arr = [];
        $arrb = [];
        $arrOpt = [];
        //search option
        $productOpt =  Product::with('cate')
        ->where('status',1)
        ->get()
        ->toArray();
        foreach($productOpt as $key => $item){
            $fielName = json_decode($item['name']);
            foreach($fielName as $i){
                if(strpos(strtolower(stripVN($i->content)), strtolower(stripVN($keyword))) !== false && $i->lang_code == $code){
                    array_push($arr,$productOpt[$key]);
                }
            }
        }
        $data['keyword'] = $request->keyword;
        $data['countproduct'] = count($arr);
        $data['resultPro'] = $arr;
        return view('search_result',$data);
    }
    public function postcontact(Request $request){
        $data = new MessContact();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->mess = $request->mess;
        $data->save();
        if($data){
            return \Redirect::to('/')->with('success', 'Gửi tin thành công');
        }else{
            return back()->with('error', 'Gửi tin thất bại');
        }

    }
    public function serviceCateList($slug)
    {
        $data['listService'] = Services::where(['cate_slug'=>$slug])->paginate(15);
        $data['category'] = ServiceCate::where('slug',$slug)->first();
        return view('servicelist',$data);
    }
    public function serviceDetail($slug)
    {
        $data['detail_service'] = Services::where(['slug'=>$slug])->first();
        $data['carTypes'] = CarType::all();

        $carTypes = CarType::with(['carServicePrices' => function ($query) {
            $query->with('province')->where('province_id',2);
        }])->get();
        $carTypes->map(function ($carType) {
            $newRecords = [];
            foreach ($carType->carServicePrices as $index1 => $carServicePrice1) {
                foreach ($carType->carServicePrices as $index2 => $carServicePrice2) {
                    if ($carServicePrice1->car_type_id == $carServicePrice2->car_type_id && $carServicePrice1->province_id == $carServicePrice2->province_id && $carServicePrice1->place_from == $carServicePrice2->place_to && $carServicePrice1->place_to == $carServicePrice2->place_from) {
                        if (isset($carServicePrice2->price_2_way) && $carServicePrice2->price_2_way > 0) {
                            $key1 = $index1 . '_' . $index2;
                            $key2 = $index2 . '_' . $index1;
                            if (!isset($newRecords[$key1]) && !isset($newRecords[$key2])) {
                                $newRecords[$key1] = [
                                    "type" => 'two_way',
                                    'parent_id' => $carServicePrice2->id,
                                    "car_type_id" => $carType->id,
                                    "province_id" => $carServicePrice1->province_id,
                                    "province_name" => $carServicePrice1->province->province_name,
                                    "place_from" => 'Hai chiều ('.$carServicePrice1->place_from.' <=> '.$carServicePrice1->place_to.')',
                                    "place_to" => 'Hai chiều ('.$carServicePrice1->place_to.' <=> '.$carServicePrice1->place_from.')',
                                    "price_min" => $carServicePrice2->price_2_way,
                                    "price_max" => $carServicePrice2->price_2_way,
                                    "price_2_way" => $carServicePrice2->price_2_way,
                                ];
                            }
                        }
                    }
                }
            }
            foreach ($newRecords as $newRecord) {
                $carType->carServicePrices->push(collect($newRecord));
            }

            // Sắp xếp lại theo `parent_id`
            $carType->sortedCarServicePrices = $carType->carServicePrices->sortBy(function ($item) {
                return $item['parent_id'] ?? $item['id'];
            })->values();
        });

        $data['groupCarTypes'] = $carTypes;

        return view('servicedetail',$data);
    }
    public function serviceList()
    {
        $data['list'] = Services::where('status',1)->paginate(9);

        return view('servicelist',$data);
    }
    public function duanTieuBieu()
    {
        $data['duan'] = Project::where('status',1)->paginate(12);
        $data['album'] = Prize::where(['status'=>1])->get(['id','image','name','link']);
        return view('album',$data);
    }
    public function duanTieuBieuDetail($slug)
    {
        $data['detail'] = Project::where('slug',$slug)->first();
        return view('detailProject',$data);
    }
    public function fag()
    {
        return view('faq');
    }
    public function getPrice(Request $request)
    {
        $data = CarType::query()
            ->join('car_service_prices', 'car_types.id', '=', 'car_service_prices.car_type_id');
        if (!empty($request->loaixe)) {
            $data->where('car_types.name', $request->loaixe);
        }
        if (!empty($request->loaixediduongdai)) {
            $data->where('car_types.name', $request->loaixediduongdai);
        }
        if (!empty($request->province_id)) {
            $data->where('car_service_prices.province_id', $request->province_id);
        }
        if (!empty($request->diemdi)) {
            // $data->where('car_service_prices.place_from', 'like', '%' . $request->diemdi . '%');
            $data->whereRaw("? LIKE CONCAT('%', car_service_prices.place_from, '%')", [$request->diemdi]);
        }
        if (!empty($request->diemden)) {
            // $data->where('car_service_prices.place_to', 'like', '%' . $request->diemden . '%');
            $data->whereRaw("? LIKE CONCAT('%', car_service_prices.place_to, '%')", [$request->diemden]);
        }
        if (!empty($request->twoway) && $request->twoway == 1) {
            $data->where('car_service_prices.price_2_way', '>', 0);
        }
        if (!empty($request->diemdididuongdai)) {
            // $data->where('car_service_prices.place_from', 'like', '%' . $request->diemdididuongdai . '%');
            $data->whereRaw("? LIKE CONCAT('%', car_service_prices.place_from, '%')", [$request->diemdididuongdai]);
        }
        if (!empty($request->diemdendiduongdai)) {
            // $data->where('car_service_prices.place_to', 'like', '%' . $request->diemdendiduongdai . '%');
            $data->whereRaw("? LIKE CONCAT('%', car_service_prices.place_to, '%')", [$request->diemdendiduongdai]);
        }
        if (!empty($request->twowayduongdai) && $request->twowayduongdai == 1) {
            $data->where('car_service_prices.price_2_way', '>', 0);
        }
        $data = $data->get();
        if ($data->count() > 0) {
            $result = $data[0];
            if ($request->twoway == 1 || $request->twowayduongdai == 1) {
                $price = 'Từ ' . number_format($result->price_2_way) . '₫';

                return response()->json(['price' => $price]);
            } else {
                if ($result->price_min > 0 || $result->price_max > 0) {
                    if ($result->price_min > 0 && $result->price_max > 0) {
                        $price = 'Từ ' . number_format($result->price_min) . '₫ - ' . number_format($result->price_max) . '₫';
                    } else {
                        $price = $result->price_min > 0 ? 'Từ ' . number_format($result->price_min) . '₫' : 'Từ ' . number_format($result->price_max) . '₫';
                    }
                    return response()->json(['price' => $price]);
                } else {
                    return response()->json(['price' => 'Vui lòng liên hệ để nhận báo giá chính xác nhất']);
                }
            }

        } else {
            return response()->json(['price' => 'Vui lòng liên hệ để nhận báo giá chính xác nhất']);
        }
    }

    public function faq()
    {
        $data['faqs'] = Faq::where(['status'=>1])->get();
        return view('faq',$data);
    }

    public function howItWorks()
    {
        $data['howItWorks'] = Prize::all();
        return view('how_it_works',$data);
    }
}
