<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name',300)->nullable();
            $table->string('description',5000)->nullable();
            $table->decimal('price',8,2)->nullable();
            $table->string('discount',10)->nullable();
            $table->string('discount_type',30)->nullable()->comment('flat/percentage');
            $table->string('packages_type','30')->nullable()->comment('service/product');
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
        Schema::dropIfExists('packages');
    }
}
