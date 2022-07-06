<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('cat_slug');
            $table->string('author_id');
            $table->string('title');
            $table->string('slug');
            $table->longText('short_description');
            $table->longText('long_description');
            $table->longText('blog_images');
            $table->string('image');
            $table->string('meta_title');
            $table->string('meta_keyword');
            $table->longText('meta_description');
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
        Schema::dropIfExists('blogs');
    }
}
