<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 100)->nullable();
            $table->string('file_type', 50)->nullable();
            $table->string('table_type', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->bigInteger('table_id')->nullable();
            $table->String('status', 30)->nullable();
            $table->tinyInteger('default')->nullable();
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
        Schema::dropIfExists('media');
    }
}
