<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class PostTestQuestion extends Eloquent {

	protected $table = 'posttest_question';
	public $timestamps = false;


    public function postTest()
    {
        return $this->hasOne('PostTest','id','test_id');
    }
}