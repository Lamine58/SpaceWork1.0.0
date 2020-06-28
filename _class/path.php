<?php 
	
   class path {


   	public static function path_include($files,$lang='fr'){

   		include("_includes/$lang/$files.php");
   	}


   	public static function path_view($page,$lang='fr'){

   		include("_views/$lang/$page.view.php");
   	}


   	public static function path_url($page,$lang='fr'){

   		return "_views/$lang/$page.view.php";
   	}

  }
        