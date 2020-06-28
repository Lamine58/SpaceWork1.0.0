<?php

class access{


	public $pages_access = [
							// 'IdAdmin'=>['dashboard'],
							];

	public function __construct(){

	}


	public function as_access($page){

		foreach ($this->pages_access as $session_id => $pages) {

			if(isset($_SESSION[$session_id]) && !empty($_SESSION[$session_id])){

				#continue
				$_array=explode('Id', $session_id);
				$class=strtolower($_array[1]);

				$$class=$class::find($_SESSION[$session_id]);
			}

			if(in_array($page, $pages)){

				if(isset($_SESSION[$session_id]) && !empty($_SESSION[$session_id])){

					#continue;

				}else{

					header("location:/");
				}

			}

			break;
		}

	}

}
