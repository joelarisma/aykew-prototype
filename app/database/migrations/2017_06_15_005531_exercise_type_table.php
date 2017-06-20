<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExerciseTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/**
		 * the intent of session_exercise_types table is to classify the type
		 * of exercises the user should take.
		 * this is where the system can determine also whether the exercise is
		 * post test, pre-test, reading, comprehension test,
		 * typing test, or eye exercises (moving images or text), etc
		 *
		 * @return void
		 */
		Schema::create('session_exercise_types', function(Blueprint $table) {
			$table->increments('id');
			$table->string('type', 50);
			$table->string('type_code', 50)->unique();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('session_exercise_types');
	}

}
