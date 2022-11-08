<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopDateTimeSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_date_time_slots', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->nullable();
            $table->string('day', 50)->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('time_slot_view', 50)->nullable();
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
        Schema::dropIfExists('shop_date_time_slots');
    }
}
