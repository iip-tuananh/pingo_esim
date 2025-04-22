<?php
namespace App\ExcelImport;

use App\models\product\Product as AppProduct;
use App\models\product\ProductOption;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductImport implements ToCollection, WithStartRow, WithMultipleSheets
{
    private $import_rows = 0;
    private $skip_rows = 0;
    private $invalid_rows = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row)
        {
            $errors = [];
            if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                $this->skip_rows++;
                continue;
            }
            $handle = trim($row[0]);
            $option_sku = trim($row[1]);
            $title = trim($row[2]);
            $option_name = trim($row[4]);
            $price_usd = trim($row[7]);
            $body = trim($row[8]);
            $image = trim($row[11]);
            $seo_title = trim($row[13]);
            $seo_description = trim($row[14]);

            $title_obj_arr = [
                [
                    'lang_code' => "vi",
                    'content' => $title,
                ],
                [
                    'lang_code' => "en",
                    'content' => $title,
                ],
            ];
            $body_obj_arr = [
                [
                    'lang_code' => "vi",
                    'content' => $body,
                ],
                [
                    'lang_code' => "en",
                    'content' => $body,
                ],
            ];
            $images = explode(',', $image);
            $rate_vnd = 26000;
            $product = AppProduct::where('sku', $handle)->first();
            if($product) {
                $product_option = $product->product_options()->where('sku', $option_sku)->first();
                if(!$product_option) {
                    $product_option = new ProductOption();
                    $product_option->product_id = $product->id;
                    $product_option->sku = $option_sku;
                    $product_option->name = $option_name;
                    $product_option->price_usd = $price_usd;
                    $product_option->price_vnd = $price_usd * $rate_vnd;
                    $product_option->save();
                } else {
                    $errors[] = 'Option SKU ' . $option_sku . ' already exists';
                }
            } else {
                $product = new AppProduct();
                $product->sku = $handle;
                $product->name = json_encode($title_obj_arr);
                $product->description = json_encode($body_obj_arr);
                $product->images = json_encode($images);
                $product->status = 1;
                $product->seo_title = $seo_title;
                $product->seo_description = $seo_description;
                $product->save();

                $product_option = new ProductOption();
                $product_option->product_id = $product->id;
                $product_option->sku = $option_sku;
                $product_option->name = $option_name;
                $product_option->price_usd = $price_usd;
                $product_option->price_vnd = $price_usd * $rate_vnd;
                $product_option->save();
            }
            if(count($errors)) {
                $this->invalid_rows[] = [
                    'detail' => implode("\n", $errors),
                    'row' => $row,
                    'index' => $index + 2,
                ];
                $this->skip_rows++;
                continue;
            }
            $this->import_rows++;
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function getImportCount(): int
    {
        return $this->import_rows;
    }

    public function getSkipCount(): int
    {
        return $this->skip_rows;
    }

    public function getInvalidRow()
    {
        return $this->invalid_rows;
    }
}
