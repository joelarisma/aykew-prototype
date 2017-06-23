<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DemoSessionReport extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/**
		 * session_report table will serve as user's exercise results
		 * in every session. it will determine the user's calculated metrics
		 * on which type of exam the user is taking
		 *
		 * @return void
		 */
		Schema::create('demo_session_report', function($table)
		{
			$table->increments('id');
			$table->integer('wpm')->default(null);
			$table->integer('ers')->default(null);
			$table->integer('net')->default(null);
			$table->integer('time_spent')->default(null);
			$table->float('percentage')->default(null);
			$table->float('eye_power')->default(null);
			$table->integer('exercise_id')->unsigned()->nullable();
			$table->float('seconds')->default(null)->nullable();
			$table->integer('wordcount')->default(null)->nullable();
			$table->integer('cscore')->default(null)->nullable();

			//foreign keys
			$table->integer('user_id')->unsigned();
			$table->integer('session_id')->unsigned();
			$table->integer('session_exercise_id')->unsigned();
			$table->integer('session_exercise_type_id')->unsigned();

			$table->timestamps();

			$table->foreign('session_id')
		      ->references('id')->on('sessions')
		      ->onDelete('cascade');

		    $table->foreign('session_exercise_id')
		      ->references('id')->on('session_exercises')
		      ->onDelete('cascade');

		    $table->foreign('session_exercise_type_id')
		      ->references('id')->on('session_exercise_types')
		      ->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('demo_session_report');
	}

}
