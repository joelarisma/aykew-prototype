n<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class SessionReport extends Eloquent {

	protected $table = 'demo_session_report';

	protected $fillable = [
			'wpm',
			'ers',
			'net',
			'time_spent',
			'percentage',
			'eye_power',
			'seconds',
			'wordcount',
			'exercise_id',
			'user_id',
			'session_id',
			'session_exercise_id',
			'session_exercise_type_id'
		];

	public function courseSession()
	{
		return $this->belongsTo('CourseSession', 'session_id');
	}

	public function type()
	{
		return $this->belongsTo('SessionExerciseType', 'session_exercise_type_id');
	}
}