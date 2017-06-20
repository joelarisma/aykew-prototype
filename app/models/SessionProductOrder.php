<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class SessionProductOrder extends Eloquent {

	protected $table = 'session_product_order';
	public $timestamps = false;


    public $guarded = ['id'];
}