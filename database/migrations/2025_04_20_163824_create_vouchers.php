<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('name');
            $table->decimal('value', 16, 2);
            $table->unsignedInteger('status');
            $table->unsignedInteger('limit_product_qty')->nullable();
            $table->unsignedInteger('limit_bill_value')->nullable();
            $table->unsignedInteger('limit_user_qty')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->text('product_ids')->nullable();
            $table->unsignedTinyInteger('is_apply_all_product')->default(0);
            $table->string('quantity')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
