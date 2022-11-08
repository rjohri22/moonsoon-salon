<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaloonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saloons', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name', 150)->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('whatsapp_no', 20)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->string('description', 200)->nullable();
            $table->string('total_rating', 5)->nullable();
            $table->string('lat', 100)->nullable();
            $table->string('lng', 100)->nullable();
            $table->string('status', 30)->nullable();
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
        Schema::dropIfExists('saloons');
    }
}
