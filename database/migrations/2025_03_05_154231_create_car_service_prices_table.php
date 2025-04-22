<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarServicePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_service_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_type_id')->constrained('car_types');
            $table->bigInteger('province_id')->nullable();
            $table->string('province_name')->nullable();
            $table->string('place_from')->nullable();
            $table->string('place_to')->nullable();
            $table->decimal('price_min', 10, 2)->nullable(0);
            $table->decimal('price_max', 10, 2)->nullable(0);
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
        Schema::dropIfExists('car_service_prices');
    }
}
