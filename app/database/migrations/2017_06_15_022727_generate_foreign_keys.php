<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GenerateForeignKeys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sessions', function(Blueprint $table) {

			$table->integer('session_level_id')->unsigned()->nullable();

			$table->foreign('session_level_id')
		      ->references('id')->on('session_levels')
		      ->onDelete('cascade');
		});

		Schema::table('session_exercises', function(Blueprint $table) {

			$table->foreign('session_exercise_type_id')
		      ->references('id')->on('session_exercise_types')
		      ->onDelete('cascade');

		    $table->foreign('session_id')
		      ->references('id')->on('sessions')
		      ->onDelete('cascade');
		});

		Schema::table('session_report', function(Blueprint $table) {

			/*$table->foreign('user_id')
		      ->references('id')->on('users')
		      ->onDelete('cascade');*/

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
		//
	}

}
