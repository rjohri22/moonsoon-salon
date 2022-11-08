<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('order_no', 50)->nullable();
            $table->string('txn_id', 150)->nullable();
            $table->string('txn_status', 40)->nullable();
            $table->string('payment_mode', 20)->nullable();
            $table->json('delivery_address')->nullable();
            $table->string('delivery_notes')->nullable();
            $table->string('status', 20)->nullable();
            $table->decimal('sub_total', 8, 2)->nullable();
            $table->decimal('discount_amount', 8, 2)->nullable();
            $table->decimal('delivery_charge', 8, 2)->nullable();
            $table->decimal('cgst_amount', 8, 2)->nullable();
            $table->decimal('sgst_amount', 8, 2)->nullable();
            $table->decimal('igst_amount', 8, 2)->nullable();
            $table->decimal('total_amount', 16, 2)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
