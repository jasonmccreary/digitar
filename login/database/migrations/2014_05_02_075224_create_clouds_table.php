<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clouds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cid');
            $table->integer('fid');
            $table->integer('uid');
            $table->string('file');
            $table->string('name');
            $table->text('note');
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('clouds');
    }
};
