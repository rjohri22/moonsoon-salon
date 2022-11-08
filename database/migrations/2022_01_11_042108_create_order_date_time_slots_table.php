<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDateTimeSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_date_time_slots', function (Blueprint $table) {
            $table->id();
            /* $table->bigInteger('module_id')->nullable(); */
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('shop_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('time_slot', 40)->nullable();
            $table->string('time_slot_show', 40)->nullable();
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
        Schema::dropIfExists('order_date_time_slots');
    }
}
