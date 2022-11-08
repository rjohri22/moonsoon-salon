<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50)->nullable();
            $table->string('code', 50)->nullable();
            $table->date('date_valid_from')->nullable();
            $table->date('date_valid_to')->nullable();
            $table->time('time_valid_from')->nullable();
            $table->time('time_valid_to')->nullable();
            $table->string('table_type', 60)->nullable();
            $table->bigInteger('table_id')->nullable();
            $table->json('days')->nullable();
            $table->decimal('amount', 8,2)->nullable();
            $table->string('status', 30)->nullable()->default('active');
            $table->string('amount_type', 20)->nullable()->comment("percentage,flat");
            $table->string('description', 200)->nullable();
            $table->tinyInteger('is_slider')->default(0)->nullable();
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
        Schema::dropIfExists('offers');
    }
}
