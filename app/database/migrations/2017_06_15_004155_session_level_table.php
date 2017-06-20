<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SessionLevelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/**
		 * create session_levels table in reference to document.
		 * stating that each each session level will have number of sessions.
		 * though there is a column level_id but it doesn't relate to 
		 * session levels referred to the document
		 * i.e. level 1 (session 0 - 10); level 2 (session 11 - 20)
		 *
		 * @return void
		 */
		Schema::create('session_levels', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('level_no');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('session_levels');
	}

}
