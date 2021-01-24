<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('full')->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->string('categories')->nullable();
            $table->string('featured_image')->nullable();
            $table->char('language', 3);
            $table->boolean('active')->default(1);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_tags')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_translations');
    }
}
