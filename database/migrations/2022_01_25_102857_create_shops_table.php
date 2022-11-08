<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('user_id'); // Seller User Id
            $table->string('name', 150)->nullable();
            $table->string('whatsapp_no', 20)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('zip', 10)->nullable();
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
        Schema::dropIfExists('shops');
    }
}
