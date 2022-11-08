<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('service_carts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_id');
            $table->bigInteger('user_id');
            $table->date('service_date')->nullable();
            $table->time('service_time')->nullable();
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
