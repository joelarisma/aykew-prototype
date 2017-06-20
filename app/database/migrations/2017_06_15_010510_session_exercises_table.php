<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SessionExercisesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/**
		 * session_exercises table refer to the individual exercises that
		 * needs to be conducted by the user.
		 * this table determines also on the kind of exercises that needs to
		 * display given the user's current session so that would mean
		 * every exercise belongs to a specific session id
		 *
		 * @return void
		 */
		Schema::create('session_exercises', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('session_id')->unsigned()->nullable();
			$table->integer('session_exercise_type_id')->unsigned();
			$table->string('reference_table', 50);
			$table->integer('exercise_id')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('session_exercises');
	}

}
