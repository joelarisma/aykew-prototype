<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class SessionExerciseType extends Eloquent {
	
	public $timestamps = false;

	protected $fillable = [
			'type',
			'type_code'
		];
}