<?php

use App\models\product\Product;
use Illuminate\Database\Seeder;

class UpdateContentToProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $product->content = $product->description;
            $product->save();
        }
    }
}
