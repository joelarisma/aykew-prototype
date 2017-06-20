<?php

class SessionLevelSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		for($i=1; $i <= 10; $i++)
			SessionLevel::create(['level_no' => $i]);
	}

}
