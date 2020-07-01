<?php

    define('USER', "");
    define('PASSWORD', "");
    define('SERVER', "");
    define('DB', "");

    date_default_timezone_set('Africa/Abidjan');

    try{

        $db = new PDO("mysql:host=".SERVER.";dbname=".DB, USER, PASSWORD);
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        return $db;
    }
    catch (Exception $e){
        // die('Erreur : ' . $e->getMessage());
    }


?>