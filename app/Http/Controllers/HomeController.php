<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\models\product\Category;
use App\models\product\Product;
use App\models\blog\Blog;
use Session;
use App\models\website\Partner;
use App\models\blog\BlogCategory;
use App\models\CarType;
use App\models\ReviewCus;
use App\models\PageContent;
use App\models\Project;
use App\models\website\Faq;
use App\models\website\Prize;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
    {
        $data['hotnews'] = Blog::where([
            ['status','=',1]
        ])->orderBy('id','DESC')->limit(6)->get(['id','title','slug','created_at','image','description']);

        $data['gioithieu'] = PageContent::where(['slug'=>'gioi-thieu','language'=>'vi'])->first(['id','title','content','image']);
        $data['aboutUsPageContent'] = PageContent::where(['type'=>'ve-chung-toi','language'=>'vi'])->where('status',1)->where('quiz_id','!=',1)->select('id','title','content','image', 'description')->get();
        $data['partners'] = Partner::where(['status'=>1])->get();
        $data['reviewcus'] = ReviewCus::where(['status'=>1])->get();
        $data['prizes'] = Prize::where(['status'=>1])->get();
        $data['faqs'] = Faq::where(['status'=>1])->get();
        $categories = Category::with(['product' => function ($query) {
            $query->where('status', 1)
                    ->with(['product_options' => function ($q) {
                        $q->select('product_id', DB::raw('MIN(price_usd) as min_price_usd'), DB::raw('MIN(price_vnd) as min_price_vnd'))
                            ->groupBy('product_id');
                    }]);
        }])->where('status', 1)->get();

        // Gán giá trị min_price vào từng product
        foreach ($categories as $category) {
            foreach ($category->product as $product) {
                $option = $product->product_options->first();
                $product->min_price_usd = $option->min_price_usd ?? 0;
                $product->min_price_vnd = $option->min_price_vnd ?? 0;
                unset($product->product_options); // nếu muốn bỏ mảng gốc
            }
        }
        $data['categories'] = $categories;
        return view('home',$data);
    }
}
