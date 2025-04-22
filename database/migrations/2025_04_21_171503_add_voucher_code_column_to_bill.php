<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoucherCodeColumnToBill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill', function (Blueprint $table) {
            $table->integer('payment_status')->default(0);
            $table->string('voucher_code')->nullable();
            $table->decimal('discount_value', 16, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->dropColumn('voucher_code');
            $table->dropColumn('discount_value');
        });
    }
}
