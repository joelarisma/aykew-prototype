<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class ExerciseText extends Eloquent {

	protected $table = 'posttest';
	public $timestamps = false;

    public function level()
    {
        return $this->hasOne('Level','id','level_id');
    }
}