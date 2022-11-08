<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopPaymentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_payment_settings', function (Blueprint $table) {
            /*  $table->id();
            $table->timestamps(); */
            $table->bigIncrements('id');
            $table->integer('shop_id');
            $table->string('paytm_no')->nullable();
            $table->integer('paytm_qrcode')->nullable();
            $table->string('phonepe_no')->nullable();
            $table->integer('phonepe_qrcode')->nullable();
            $table->string('googlepay_no')->nullable();
            $table->integer('googlepay_qrcode')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->integer('whatsapp_qrcode')->nullable();
            $table->string('account_no', 100)->nullable();
            $table->string('ifsc_code', 100)->nullable();
            $table->string('bank_name', 150)->nullable();
            $table->string('account_holder', 150)->nullable();
            $table->string('account_type', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_payment_settings');
    }
}
