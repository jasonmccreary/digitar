<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid')->nullable();
            $table->integer('cid')->nullable();
            $table->string('username');
            $table->string('password');
            $table->integer('rights');
            $table->string('company')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('website');
            $table->string('tell');
            $table->string('address');
            $table->string('zipcode');
            $table->string('city');
            $table->integer('listed')->default(1);
            $table->integer('lookonly')->default(0);
            $table->unique('username');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('users');
    }
};
