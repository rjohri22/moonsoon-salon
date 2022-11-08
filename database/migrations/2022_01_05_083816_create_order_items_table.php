<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->string('item_name',100)->nullable();
            $table->string('description',2000)->nullable();
            $table->string('status',50)->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('rate',8,2)->nullable();
            $table->decimal('item_price',8,2)->nullable();
            $table->integer('discount')->nullable();
            $table->string('discount_type',50)->nullable();
            $table->decimal('total',16,2)->nullable();
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
        Schema::dropIfExists('order_items');
    }
}
