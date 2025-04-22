<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrice2WayToCarServicePrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_service_prices', function (Blueprint $table) {
            $table->decimal('price_2_way', 10, 2)->after('price_max')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_service_prices', function (Blueprint $table) {
            $table->dropColumn('price_2_way');
        });
    }
}
