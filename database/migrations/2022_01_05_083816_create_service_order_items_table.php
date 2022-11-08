<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('service_order_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->string('service_name',100)->nullable();
            $table->string('description',2000)->nullable();
            $table->string('status',50)->nullable();
            $table->decimal('rate',8,2)->nullable();
            $table->date('service_date')->nullable();
            $table->time('service_time')->nullable();
            $table->decimal('service_price',8,2)->nullable();
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
