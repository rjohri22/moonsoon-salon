<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->nullable();
           /*  $table->bigInteger('module_id')->nullable(); */
            $table->bigInteger('item_id');
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
