<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class Section extends Eloquent {

	protected $table = 'section';
	public $timestamps = false;
    public function course()
    {
        return $this->hasOne('Course','id','course_id');
    }

    public function begSessions()
    {
        return $this->hasOne('CourseSession','id','beg_session_id');
    }

    public function endSessions()
    {
        return $this->hasOne('CourseSession','id','end_session_id');
    }

    public function level_number() {
        $levels = array();
	$all = Section::where('course_id', '=', $this->course_id)->get();

	foreach($all as $l) {
	    $beg = $l->begSessions->session;
	    $levels[$beg] = $l; 
	}

	ksort($levels);
	$i = 0;
	foreach($levels as $l) {
	    if($l->id == $this->id) { return $i; }
	    $i++;
	}
	return 0;
    }
    
}