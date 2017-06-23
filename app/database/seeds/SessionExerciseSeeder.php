<?php

class SessionExerciseSeeder extends Seeder {

	private $mapTypes = [
			'Comprehension' => 'comprehension',
			'Eye Speed' => 'eye-speed',
			'Post-Test' => 'post-test',
			'Pre-Test' => 'pre-test',
			'Typing' => 'typing-test',
			'Eye Exercise' => 'eye-exercise'
		];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$sessionExercises = new SessionProductOrder;
		$sessions = CourseSession::whereBetween('session', [0, 90])
						->where('course_id', 3)
						->orderBy('session', 'ASC')
						->get();

		$sampleArr = [];
		foreach ($sessions as $session) 
		{
			$exercises = $sessionExercises->where('session_id', $session->id)->get();
			$length = $exercises->count();

			$referenceTable = ''; 
			for($i=0; $i < $length; $i++) 
			{
				$exercise = $exercises[$i];

				if($exercise->product_value == 'Reading' && $i == 0)
				{
					$type = ($session->session == 0) ? $this->mapTypes['Post-Test'] : $this->mapTypes['Pre-Test'];
					$type = $this->getExerciseType($type);
					$referenceTable = 'posttest';
				
				} else if($exercise->product_value == 'Reading' && 
					$i == (($length - 1) || $i == ($length - 2)) ) {
				
					$type = $this->mapTypes['Post-Test'];
					$type = $this->getExerciseType($type);
					$referenceTable = 'posttest';

				} else if(preg_match('/^[1-9][0-9]*$/', $exercise->product_value) 
					&& $exercise->product_type == 'exercise' ) {

					$type = $this->mapTypes['Eye Exercise'];
					$type = $this->getExerciseType($type);
					$referenceTable = 'exercise';
				} else {
					$type = $this->mapTypes[$exercise->product_value];
					$type = $this->getExerciseType($type);
					$referenceTable = $exercise->product_value == 'Comprehension' ? 'posttest' : '';	
				}

				/*SessionExercise::create([
						'session_id' => 
					])*/

				$sampleArr = [
						'session_id' => $exercise->session_id,
						'session_exercise_type_id' => $type,
						'reference_table' => $referenceTable,
						'exercise_id' => $referenceTable == 'exercise' ? $exercise->product_value : null,
					];

				SessionExercise::create($sampleArr);
			}
		}

		//dd($sampleArr);
		


	}

	private function getExerciseType($typeCode)
	{
		return SessionExerciseType::where('type_code', $typeCode)->first()->id;
	}
}
