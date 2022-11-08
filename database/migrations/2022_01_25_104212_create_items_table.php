<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('brand_id')->nullable();
            $table->bigInteger('item_category_id');
            $table->bigInteger('item_sub_category_id');
            $table->bigInteger('product_category_id');
            $table->string('name');
            $table->bigInteger('qty')->default(0);
            $table->decimal('price', 16, 2);
            $table->decimal('unit_value', 5, 2);
            $table->integer('unit_id')->nullable();
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
        Schema::dropIfExists('items');
    }
}
