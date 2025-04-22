<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Ví dụ: VN, US, FR
            $table->string('name');           // Việt Nam, France
            $table->string('currency_code');  // VND, USD, EUR
            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();     // USD
            $table->string('name')->nullable();   // United States Dollar
            $table->string('symbol')->nullable(); // $
            $table->timestamps();
        });

        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency');       // USD, VND
            $table->decimal('rate', 16, 6);   // 1.084000
            $table->string('base_currency'); // USD
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
        Schema::dropIfExists('countries');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('currency_rates');
    }
}
