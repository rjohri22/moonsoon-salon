<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_category_id');
            $table->bigInteger('item_sub_category_id');
            $table->bigInteger('branch_id')->nullable();
            $table->string('name', 100);
            $table->bigInteger('qty')->default(0);
            $table->decimal('price', 16, 2);
            $table->string('service_time',10)->nullable();
            $table->decimal('discount_amount', 16, 2)->nullable();
            $table->string('discount_type', 200)->nullable();
            $table->string('description', 200)->nullable();
            $table->string('how_to_use', 200)->nullable();
            $table->string('benefits', 200)->nullable();
            $table->string('status', 30)->default('active');
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
        Schema::dropIfExists('services');
    }
}
