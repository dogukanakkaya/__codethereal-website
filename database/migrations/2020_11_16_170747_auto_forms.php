<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AutoForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('auto_forms', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->tinyInteger('html_type');
            $table->string('data_type');
            $table->string('data_type_constraint')->nullable();
            $table->string('validations')->nullable();
            $table->string('mimes')->nullable();
            $table->string('column_name');
            $table->string('order')->default("0");
            $table->string('belong_table');
            $table->string('belong_page');
            $table->timestamps();
            $table->softDeletes();
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auto_forms');
    }
}
