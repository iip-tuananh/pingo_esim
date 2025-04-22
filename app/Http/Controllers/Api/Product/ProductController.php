<?php

namespace App\Http\Controllers\Api\Product;

use App\ExcelImport\ProductImport as AppProductImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\product\Product;
use Illuminate\Support\Facades\Validator;
use App\Imports\ProductImport;
use App\models\product\Category;
use App\models\product\ProductOption;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use stdClass;
use DB;
use Response;

class ProductController extends Controller
{
    public function create(Request $request, Product $product)
    {
        $data = $product->createOrEdit($request);
        return response()->json([ 'data' => $data, 'message' => 'success'], 200);
    }
    public function list(Request $request)
    {
        $keyword = $request->keyword;
        $data = Product::with('product_options')->leftJoin('product_category', function($join){
            $join->on('product_category.id','=','products.category');
        })
        ->select('products.*','product_category.name as cate')->orderBy('id','DESC');
        if(!empty($keyword)){
            $data = $data->where('products.name', 'LIKE', '%'.$keyword.'%');
        }
        $data = $data->get()->toArray();
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function edit($id)
    {
        $data = Product::where([
            'id'=> $id
        ])
        ->first();
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function delete($id)
    {
        $query = Product::where(['id'=>$id])
        ->first();
        if($query->images){
            $imgArr = json_decode($query->images);
            foreach($imgArr as $i){
                $file= str_replace('http://localhost:8080','',$i);
                $filename = public_path().$file;
                if(file_exists( public_path().$file ) ){
                    \File::delete($filename);
                }
            }
        }
        $query->delete();

        return response()->json([
            'message' => 'Delete success'
        ]);
    }

    // Import Excel
	public function importExcel(Request $request) {
		$validate = Validator::make(
			$request->all(),
			[
                'file' => 'required|file',
			],
			[
				'file.required' => 'Không được để trống',
				'file.file' => 'Không hợp lệ',
				'file.mimes' => 'Không đúng định dạng file',
			]
		);

		$json = new stdClass();

        if ($validate->fails()) {
            $json->success = false;
            $json->errors = $validate->errors();
            $json->message = "Import thất bại!";
            return Response::json($json);
        }
        DB::beginTransaction();
        try {
			$import = new AppProductImport;
			Excel::import($import, $request->file('file'));
            DB::commit();

            $json->success = true;
            $json->details = [
                'import' => $import->getImportCount(),
                'skip' => $import->getSkipCount(),
                'invalid_rows' => $import->getInvalidRow(),
            ];
            $json->message = "Import thành công!";
            return Response::json($json);
        } catch (Exception $e) {
            DB::rollBack();
            $json->success = false;
            $json->message = "Đã có lỗi xảy ra!";
            return Response::json($json);
        }
	}

    // Import CSV Custom
    public function importCsvCustom(Request $request)
    {
        $validate = Validator::make(
			$request->all(),
			[
                'file' => 'required|file',
			],
			[
				'file.required' => 'Không được để trống',
				'file.file' => 'Không hợp lệ',
				'file.mimes' => 'Không đúng định dạng file',
			]
		);

		$json = new stdClass();

        if ($validate->fails()) {
            $json->success = false;
            $json->errors = $validate->errors();
            $json->message = "Import thất bại!";
            return Response::json($json);
        }

        $file = $request->file('file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        // Bỏ dòng đầu tiên (header)
        fgetcsv($handle, 0, ",");

        $importRows = 0;
        $skipRows = 0;
        $invalidRows = [];

        $rate_vnd = 26000;
        $index = 1;

        while (($row = fgetcsv($handle, 0, ",")) !== false) {
            $index++;

            $errors = [];

            // Kiểm tra các cột bắt buộc
            if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                $skipRows++;
                continue;
            }

            // Gán dữ liệu từ cột
            $handleSku     = trim($row[0]);  // Mã sản phẩm
            $optionSku     = trim($row[1]);  // Mã option
            $title         = trim($row[2]);
            $optionName    = trim($row[4]);
            $priceUsd      = trim($row[7]);
            $body          = trim($row[8]);
            $image         = trim($row[11]);
            $seoTitle      = trim($row[13]);
            $seoDescription = trim($row[14]);

            $titleObjArr = [
                ['lang_code' => "vi", 'content' => $title],
                ['lang_code' => "en-US", 'content' => $title],
            ];
            $bodyObjArr = [
                ['lang_code' => "vi", 'content' => $body],
                ['lang_code' => "en-US", 'content' => $body],
            ];
            $images = explode(',', $image);

            // Kiểm tra product đã tồn tại chưa
            $product = \App\models\product\Product::where('sku', $handleSku)->first();
            if ($product) {
                $productOption = $product->product_options()->where('sku', $optionSku)->first();
                if (!$productOption) {
                    $productOption = new \App\models\product\ProductOption();
                    $productOption->product_id = $product->id;
                    $productOption->sku = $optionSku;
                    $productOption->name = $optionName;
                    $productOption->price_usd = $priceUsd;
                    $productOption->price_vnd = $priceUsd * $rate_vnd;
                    $productOption->save();
                } else {
                    $errors[] = "Option SKU $optionSku already exists";
                }
            } else {
                // Tạo mới product
                $product = new \App\models\product\Product();
                $product->sku = $handleSku;
                $product->name = json_encode($titleObjArr, JSON_UNESCAPED_UNICODE);
                $product->description = json_encode($bodyObjArr, JSON_UNESCAPED_UNICODE);
                $product->content = json_encode($bodyObjArr, JSON_UNESCAPED_UNICODE);
                $product->images = json_encode($images);
                $product->status = 1;
                $product->seo_title = $seoTitle;
                $product->seo_description = $seoDescription;
                $product->save();

                // Tạo product option luôn
                $productOption = new \App\models\product\ProductOption();
                $productOption->product_id = $product->id;
                $productOption->sku = $optionSku;
                $productOption->name = $optionName;
                $productOption->price_usd = $priceUsd;
                $productOption->price_vnd = $priceUsd * $rate_vnd;
                $productOption->save();
            }

            if (count($errors)) {
                $invalidRows[] = [
                    'detail' => implode("\n", $errors),
                    'row'    => $row,
                    'index'  => $index,
                ];
                $skipRows++;
                continue;
            }

            $importRows++;
        }

        fclose($handle);

        $json->success = true;
        $json->details = [
            'import' => $importRows,
            'skip' => $skipRows,
            'invalid_rows' => $invalidRows,
        ];
        $json->message = "Import thành công!";
        return Response::json($json);
    }

    public function addCateMultiple(Request $request)
    {
        $data = $request->all();
        $product = Product::whereIn('id', $data['arrayItemChecked'])->get();
        $cate = Category::where('id', $data['category'])->first();
        foreach ($product as $item) {
            $item->category = $data['category'];
            $item->cate_slug = $cate->slug;
            $item->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật phân loại thành công'
        ]);
    }

    public function listProductOptions(Request $request)
    {
        $data = ProductOption::query()->with('product');
        if($request->keyword){
            $data = $data->where(function($query) use ($request){
                $query->where('name', 'LIKE', '%'.$request->keyword.'%')->orWhere('sku', 'LIKE', '%'.$request->keyword.'%');
            });
        }
        if($request->title){
            $data = $data->whereHas('product', function($query) use ($request){
                $query->where('name', 'LIKE', '%'.$request->title.'%');
            });
        }
        if($request->product_id){
            $data = $data->where('product_id', $request->product_id);
        }
        $data = $data->orderBy('id', 'DESC')->get();
        return response()->json([
            'data' => $data,
        ]);
    }

    public function updatePriceMultiple(Request $request)
    {
        $data = $request->all();
        $product_options = ProductOption::whereIn('id', $data['arrayItemChecked'])->get();
        foreach ($product_options as $item) {
            if ($request->currency == 'usd') {
                $item->price_usd = $request->price;
            } else {
                $item->price_vnd = $request->price;
            }
            $item->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật phân loại thành công'
        ]);
    }

    public function getOptionDetail($id)
    {
        $data = ProductOption::where('id', $id)->first();
        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateOption(Request $request)
    {
        $data = $request->all();
        if(isset($data['id'])){
            $productOption = ProductOption::where('id', $data['id'])->first();
            $productOption->name = $data['name'];
            $productOption->sku = $data['sku'];
            $productOption->product_id = $data['product_id'];
            $productOption->price_vnd = $data['price_vnd'];
            $productOption->price_usd = $data['price_usd'];
        } else {
            $productOption = new ProductOption();
            $productOption->name = $data['name'];
            $productOption->sku = $data['sku'];
            $productOption->product_id = $data['product_id'];
            $productOption->price_vnd = $data['price_vnd'];
            $productOption->price_usd = $data['price_usd'];
        }
        $productOption->save();
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật option thành công'
        ]);
    }

    public function deleteProductOption(Request $request)
    {
        $data = $request->all();
        ProductOption::where('id', $data['id_item'])->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa option thành công'
        ]);
    }
}
