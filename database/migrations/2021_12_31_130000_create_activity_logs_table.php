<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('ip_address', 255)->nullable();
            $table->integer('device_id')->nullable();
            $table->string('device_name', 255)->nullable();
            $table->string('device_type', 100)->nullable();
            $table->string('os_name', 100)->nullable();
            $table->string('os_version', 100)->nullable();
            $table->string('browser_name', 100)->nullable();
            $table->string('browser_version', 100)->nullable();
            $table->string('feature', 100)->nullable();
            $table->string('action', 100)->nullable();
            $table->timestamp('time')->nullable();
            $table->json('json_info')->nullable();
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
        Schema::dropIfExists('activity_logs');
    }
}
