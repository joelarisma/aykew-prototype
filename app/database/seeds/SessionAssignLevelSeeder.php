<?php

class SessionAssignLevelSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		$sessions = CourseSession::where('course_id', 3)->get();
		foreach($sessions as $session) {
			$level = $session->session == 0 ? 1 : ceil($session->session/10);
			$levelNo = SessionLevel::where('level_no', $level)->first();

			$session->session_level_id = $levelNo->id;
			$session->save();
		}
	}

}
