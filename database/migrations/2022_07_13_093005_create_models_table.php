<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->longtext('gallery_image')->nullable();
            $table->string('Orientation')->nullable();
            $table->string('Ethnicity')->nullable();
            $table->string('Language')->nullable();
            $table->string('Hair')->nullable();
            $table->string('Fetishes')->nullable();
            $table->string('Model_Category')->nullable();
            $table->string('stage_name')->nullable();
            $table->string('stage_name')->nullable();
            $table->string('url1')->nullable();
            $table->string('url2')->nullable();
            $table->string('url3')->nullable();
            $table->longtext('socail_links')->nullable();
            $table->string('phone')->nullable();
            $table->string('video')->nullable();
            $table->string('cost_msg')->nullable();
            $table->string('cost_pic')->nullable();
            $table->string('cost_videomsg')->nullable();
            $table->string('cost_audiocall')->nullable();
            $table->string('cost_videocall')->nullable();
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
        Schema::dropIfExists('models');
    }
}
