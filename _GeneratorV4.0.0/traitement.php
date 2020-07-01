<?php

$conn = db_connect($_POST["host-name"],$_POST["db-name"],$_POST["user-name"],$_POST["password"]);

// folder_create($_POST["folder"]);

if(isset($_POST["g-class"])){ //Appelle a la fonction qui génere les class
    if( $_POST["table-name"] == "Toutes les tables"){
        $table_name=get_name_table($_POST["db-name"], $conn);
        for($i = 0; $i < sizeof($table_name); $i++){
            generer_class($_POST["db-name"], $table_name[$i], $conn);
            generer_controller($_POST["db-name"], $table_name[$i], $conn);
        }
    }else{
        generer_class($_POST["db-name"], $_POST["table-name"], $conn);
            generer_controller($_POST["db-name"], $_POST["table-name"], $conn);
    }
}elseif(isset($_POST["g-manager"])){ //Appelle a la fonction qui genere les Managers
    if( $_POST["table-name"] == "Toutes les tables"){
        $table_name=get_name_table($_POST["db-name"], $conn);
        for($i = 0; $i < sizeof($table_name); $i++){
            generer_manager($_POST["db-name"],  $table_name[$i], $conn);
        }
    }else{
        generer_manager($_POST["db-name"], $_POST["table-name"], $conn);
    }
}
function db_connect($host, $dbname, $user, $password){
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
        return $conn;
    }
    catch(PDOException $e)
    {
        return "Connection failed: " . $e->getMessage();
    }
}

function folder_create($path){
    if(!is_dir($path)){
        mkdir($path);
        mkdir($path."/class");
    }
}

function get_list_table($dbname, $conn){
    //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $reponse = $conn->query('show tables');
    $liste_table = "";

    // On affiche chaque entrée une à une

    while ($donnees = $reponse->fetch())
    {
        $liste_table .= "<option value='".$donnees["Tables_in_".$dbname]."'>".$donnees["Tables_in_".$dbname]."</option>";
    }

    $liste_table .= "<option value='Toutes les tables'>Toutes les tables</option>";
    return $liste_table;
}

function generer_class($dbname, $tablename, $conn){

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $txt = "<?php \n  //Genéré par GeneratorClassV4.0.0 le ".date("d-m-Y")." à ".date("H:i:s")."\n\n";

    $txt.="     class ".$tablename." extends spacework {"."\n\n";


    $fichier = fopen("../_class/".$tablename.".php", 'w') or die("Unable to open file!");

    $reponse = $conn->query("SELECT column_name  FROM information_schema.columns WHERE table_schema='".$dbname."' AND table_name='".$tablename."'");

    $controle = false;

    $varibales='        public $fields=[';

    while ($donnees = $reponse->fetch()){
            
        if($donnees['column_name']!='id')    
            $varibales.="'$donnees[column_name]',";
         
    }

    $varibales=substr($varibales, 0,-1);

    $varibales.='];'."\n";

    $txt .=$varibales."\n";
    
    $txt .="        public function __construct(){"."\n\n";
    $txt .="            parent::__construct();"."\n";
    $txt .="        }"."\n";


    $txt .='    }';

    fwrite($fichier, $txt);
}

function generer_controller($dbname, $tablename, $conn){

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $reponse = $conn->query("SELECT column_name, column_default  FROM information_schema.columns WHERE table_schema='".$dbname."' AND table_name='".$tablename."'");

    $var="";

    while ($donnees = $reponse->fetch()){

        if($donnees["column_name"]!="id" && $donnees["column_name"]!="id".ucfirst($tablename) && $donnees["column_name"]!="supprimer"){
            $var .= '            $'.$tablename.'->'.$donnees["column_name"].'=$_POST["'.$donnees["column_name"].'"];'."\n";
         }
    }

    $fichier = fopen("../_controllers/controller".ucfirst($tablename).".php", 'w') or die("Unable to open file!");

    $txt = '<?php
    #Genéré par GeneratorClassV4.0.0 le '.date("d-m-Y")." à ".date("H:i:s").'

    session_start();
    include "../_class/config.php";
    include "../_constantes/constantes.php";
    include "../_functions/functions.php";

    spl_autoload_register(function($class){
        include "../_class/".$class.".php";
    });

    extract($_GET);

    function add_'.$tablename.'(){

        $requireds=[];

        if(count(required($_POST,$requireds))==0){

            $'.$tablename.' = new '.$tablename.';

'.$var.'

            if($'.$tablename.'->add()){

                echo json_encode(["message"=>"Ajout éffectué avec succès","status"=>"success"]);
            }else{

                echo json_encode(["message"=>"Echec de l\'ajout veuillez réessayer","status"=>"error"]);
            }

        }else{
            echo json_encode(["message"=>"Veuillez saisir tous les champs obligatoires","status"=>"error","fields"=>required($_POST,$requireds)]
                            );
        }
    }

    function update_'.$tablename.'(){

        $requireds=[];
        
        if(count(required($_POST,$requireds))==0){

            $'.$tablename.' = '.$tablename.'::find($_POST["id"]);

'.$var.'

            if($'.$tablename.'->update()){

                echo json_encode(["message"=>"Mofdification éffectué avec succès","status"=>"success"]);
            }else{

                echo json_encode(["message"=>"Echec de la mofdification veuillez réessayer","status"=>"error"]);
            }

        }else{
            echo json_encode(["message"=>"Veuillez saisir tous les champs obligatoires","status"=>"error","fields"=>required($_POST,$requireds)]
                            );
        }
        
    }

    function delete_'.$tablename.'(){

        $id = $_GET["id"];

        if(!isset($id))
            routes::index();

        if('.$tablename.'::delete($id)){

            echo json_encode(["message"=>"Suppresion éffectué avec succès","status"=>"success"]);
        }else{

            echo json_encode(["message"=>"Echec de la suppression","status"=>"error"]);
        }
    
    }

    if (function_exists($function)) {
    
        $function();

    }else{
    
        routes::index();
    }

    ';

    fwrite($fichier, $txt);
}

function get_name_table($dbname, $conn){
    //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $reponse = $conn->query('show tables');
    $liste_table = [];

    // On affiche chaque entrée une à une

    while ($donnees = $reponse->fetch())
    {
        $liste_table[]= $donnees["Tables_in_".$dbname];
    }

    return $liste_table;
}
