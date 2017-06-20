<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SessionReportTable extends Migration {

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
		Schema::create('session_report', function($table)
		{
			$table->increments('id');
			$table->integer('wpm')->default(null);
			$table->integer('ers')->default(null);
			$table->integer('net')->default(null);
			$table->integer('time_spent')->default(null);
			$table->float('percentage')->default(null);
			$table->float('eye_power')->default(null);
			$table->integer('exercise_id')->unsigned()->nullable();

			//foreign keys
			$table->integer('user_id')->unsigned();
			$table->integer('session_id')->unsigned();
			$table->integer('session_exercise_id')->unsigned();
			$table->integer('session_exercise_type_id')->unsigned();

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
		Schema:drop('session_report');
	}

}
