<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceWishlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_wishlists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->nullable();
           /*  $table->bigInteger('module_id')->nullable(); */
            $table->bigInteger('service_id');
            $table->time('service_date')->nullable();
            $table->date('service_time')->nullable();
            $table->bigInteger('user_id');
            $table->decimal('discount')->nullable();
            $table->string('discount_type', 20)->nullable();
            $table->float('price', 15, 2);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
}
