<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class ExerciseImage extends Eloquent {

	protected $table = 'exercise_image';
	public $timestamps = false;
    protected $fillable = ['name','filename'];

}