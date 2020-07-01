<?php
    session_start();
    include '_class/config.php';
    include "_constantes/constantes.php";
    include "_functions/functions.php";

    spl_autoload_register(function($class){
        include "_class/".$class.".php";
    });

    if(isset($_SESSION['lang'])){

        $lang=$_SESSION['lang'];

    }else{

        $lang="fr";    
    }


	if(!isset($_GET['page'])){

		$_GET['page']='accueil';
	}

    HeaderXframe(0);

    // if(!visitor::at_visite(visitor::ip())){

    // 	visitor::create(["ip"=>visitor::ip(),"date_visite"=>date('Y-m-d')]);
    // }

   	$access = new access;
   	$access->as_access($_GET['page']);
    
    path::path_include('header');



	if(isset($_GET['page'])){

    	$page=strtolower($_GET['page']);

		if(is_file(path::path_url($page))){

			path::path_view($page);

		}else{

			path::path_view('404');
		}

	}elseif(is_file(path::path_url('accueil'))){

		path::path_view('accueil');
	}
    	


    path::path_include('footer');

?>
