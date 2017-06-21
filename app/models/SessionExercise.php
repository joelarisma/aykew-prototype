<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class SessionExercise extends Eloquent {
	
	public $timestamps = false;

	protected $fillable = [
			'session_id',
			'session_exercise_type_id',
			'reference_table',
			'exercise_id'
		];

	public function type()
	{
		return $this->belongsTo('SessionExerciseType', 'session_exercise_type_id', 'id');
	}
}