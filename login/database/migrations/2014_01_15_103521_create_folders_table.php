<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->integer('pid')->nullable();
            $table->string('name');
            $table->integer('bookedcheck')->default(0);
            $table->string('color')->default('#ff3434');
            $table->integer('order')->nullable();
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
        Schema::drop('folders');
    }
}
