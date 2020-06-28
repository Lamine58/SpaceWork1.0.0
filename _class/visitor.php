<?php 

class visitor extends spacework{

	public $fields=['ip','date_visite'];

	public function __construct(){

		parent::__construct();
	}


	public static function ip(){

	    return isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] :
	    (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] :
	    $_SERVER['REMOTE_ADDR']);
	}

	public static function at_visite($ip){

		$day=date('Y-m-d');
	    return count(visitor::query(["ip='$ip'"," AND date_visite='$day'"]))>0 ? true : false;
	}


}
