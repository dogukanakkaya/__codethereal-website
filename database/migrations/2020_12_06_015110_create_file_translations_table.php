<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files');
            $table->string('title')->nullable();
            $table->string('alt')->nullable();
            $table->boolean('active')->default(1);
            $table->tinyInteger('type')->default(1);
            $table->char('language', 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_translations');
    }
}
