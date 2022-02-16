<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('nova-cms-news.db_prefix');

        Schema::create($prefix.'news_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('is_sticky')->default(false)->index();
            $table->integer('sort_order')->nullable();
            $table->json('title');
            $table->string('slug');
            $table->json('subtitle')->nullable();
            $table->json('abstract')->nullable();
            $table->json('main_content')->nullable();
            $table->json('browser_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->unsignedInteger('main_image')->nullable();
            $table->unsignedInteger('overview_image')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->unsignedBigInteger('artist_id')->nullable();
            $table->unsignedBigInteger('slideshow_id')->nullable();
            $table->string('width_in_overview')->default('regular')->nullable();
            $table->string('width_in_frame')->default('full')->nullable();
            $table->string('title_position')->default('bottom_left')->nullable();
            $table->string('link')->nullable();
            $table->string('bgcolor', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('nova-cms-news.db_prefix');

        Schema::dropIfExists($prefix.'news_items');
    }
};
