<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class ExerciseSeries extends Eloquent {

	protected $table = 'exercise_series';
	public $timestamps = false;

    public function e1(){
        return $this->hasOne('Exercise','id','ex1');
    }

    public function e2(){
        return $this->hasOne('Exercise','id','ex2');
    }

    public function e3(){
        return $this->hasOne('Exercise','id','ex3');
    }

    public function e4(){
        return $this->hasOne('Exercise','id','ex4');
    }

    public function e5(){
        return $this->hasOne('Exercise','id','ex5');
    }

    public function e6(){
        return $this->hasOne('Exercise','id','ex6');
    }
    public function e7(){
        return $this->hasOne('Exercise','id','ex7');
    }

    public function e8(){
        return $this->hasOne('Exercise','id','ex8');
    }

}