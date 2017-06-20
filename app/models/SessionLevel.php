n<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class SessionLevel extends Eloquent {
	
	public $timestamps = false;

	protected $fillable = [
			'level_no'
		];
}