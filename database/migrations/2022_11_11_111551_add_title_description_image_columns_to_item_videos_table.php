<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleDescriptionImageColumnsToItemVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_videos', function (Blueprint $table) {
            $table->string('title',200)->nullable();
            $table->string('description',1000)->nullable();
            $table->string('thumbnail_image',500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_videos', function (Blueprint $table) {
            //

            $table->dropColumn('title');
            
            $table->dropColumn('description');
            
            $table->dropColumn('thumbnail_image');


        });
    }
}
