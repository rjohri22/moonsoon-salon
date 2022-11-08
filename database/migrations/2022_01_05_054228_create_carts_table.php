<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_id');
            $table->bigInteger('user_id');
            $table->integer('quantity');
            $table->decimal('discount')->nullable();
            $table->string('discount_type', 20)->nullable();
            $table->decimal('price', 16, 2)->nullable();
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
        Schema::dropIfExists('carts');
    }
}
