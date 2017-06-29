<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class CourseSession extends Eloquent {

	protected $table = 'sessions';
	public $timestamps = false;

    protected $fillable = [
            'session_level_id'
        ];


    public function exercises()
    {
    	return $this->hasMany('SessionExercise', 'session_id', 'id');
    }

    public function reports()
    {
    	return $this->hasMany('SessionReport', 'session_id', 'id')
    				->orderBy('updated_at', 'DESC')
					->orderBy('created_at', 'DESC');
    }

    public function sessionLevel()
    {
        return $this->belongsTo('SessionLevel', 'session_level_id', 'id');
    }
}