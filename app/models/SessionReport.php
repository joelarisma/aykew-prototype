n<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class SessionReport extends Eloquent {

	protected $table = 'session_report';

	protected $fillable = [
			'wpm',
			'ers',
			'net',
			'time_spent',
			'percentage',
			'eye_power',
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
}