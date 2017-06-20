<?php

class SessionExerciseTypeSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		$types = [
				[ 'type' => 'Post-Test', 'type_code' => 'post-test' ],
				[ 'type' => 'Pre-Test', 'type_code' => 'pre-test' ],
				[ 'type' => 'Comprehension Test', 'type_code' => 'comprehension' ],
				[ 'type' => 'Eye Speed Test', 'type_code' => 'eye-speed' ],
				[ 'type' => 'Typing Test', 'type_code' => 'typing-test' ],
				[ 'type' => 'Eye Exercise', 'type_code' => 'eye-exercise' ]
			];

		foreach ($types as $type) {
			SessionExerciseType::create($type);
		}
	}

}
