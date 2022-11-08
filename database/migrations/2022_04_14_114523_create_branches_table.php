<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name',250);
            $table->string('slug',250);
            $table->text('address')->nullable();
            $table->string('city',60)->nullable();
            $table->string('state',50)->nullable();
            $table->string('pincode',30)->nullable();
            $table->string('lat',60)->nullable();
            $table->string('lng',60)->nullable();
            $table->string('status',30)->nullable();
            $table->text('description')->nullable();
            // $table->id();
            $table->timestamps();
            $table->timestamp('delete_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
