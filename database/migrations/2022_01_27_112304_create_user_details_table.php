<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->tinyInteger('marital_status')->default(0);
            $table->date('dob')->nullable();
            $table->date('anniversary')->nullable();
            $table->tinyInteger('hair_length')->default(0);
            $table->tinyInteger('hair_type')->default(0);
            $table->tinyInteger('skin_type')->default(0);
            $table->tinyInteger('allergies')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
