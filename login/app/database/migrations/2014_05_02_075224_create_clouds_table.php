<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCloudsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clouds', function(Blueprint $table) {
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
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clouds');
	}

}
