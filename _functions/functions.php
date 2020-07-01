<?php

function ChargerFicher($nomFicher,$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'],$dossier="_files/categories/"){

    if(!isset($_FILES[$nomFicher])){

      return 1;

    }else{

        $fichier = $_FILES[$nomFicher];
    }

    if ($fichier['error'] !== 0) {

        return 2;
    }

    if (!file_exists($fichier['tmp_name'])){

        return 3;
    }

    $maxFileSize = 5 * 10e6; // = 2 000 000 bytes = 2MB
    if ($fichier['size'] > $maxFileSize){

        return 4;
    }

    $imageData = getimagesize($fichier['tmp_name']);

    if (!$imageData){

        return 5;
    }

    $mimeType = $imageData['mime'];
    
    if (!in_array($mimeType, $allowedMimeTypes)){

        return 6;
    }

    $image = $dossier.'IMG_CAT_'.CodeActivation(6).'.'.str_replace("image/", "", $fichier['type']);

     if(move_uploaded_file($fichier['tmp_name'], '../'.$image)){
        
        return $image;

     }else{

        return 7;
     }
}


function ValideEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function pathUrl($lien){

    return "https://innovecrea.com/Innovecrea-admin/".$lien;
}

function base64_to_jpeg($base64_string, $output_file="../_files/ordonnances/image.jpeg") {

    $ifp = fopen( $output_file, 'wb' ); 

    $data = explode( ',', $base64_string );
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    fclose( $ifp ); 

    return $output_file; 
}

function DateHistorique($date)
{
  $temps = strtotime(date("Y-m-d H:i:s")) - strtotime($date);

  if ($temps < 60) {
    return "Il y a  ".$temps . "s" ;
  }
  elseif ($temps < 3600) {
      return "Il y a  ".round(($temps/60),0) . "min" ;
  }
  elseif ($temps < 86400 ) {
      return "Il y a  ".round(($temps/3600),0) . "H" ;
  }
  elseif ($temps < 604800 ) {
      return "Il y a  ".round(($temps/86400),0) . " Jours" ;
  }
  else {
    return $date;
  }
}

function sendMessage($id_phone,$message){

    $content = array(
        "en" => $message
    );

    $fields = array(
        'app_id' => "a7f3bbed-e4b5-40a7-a164-d76f9549985d",
        'include_player_ids' => array($id_phone),
        'large_icon' =>"ic_launcher_round.png",
        'contents' => $content
    );

    $fields = json_encode($fields);
    // print("\nJSON sent:\n");
    // print($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}


function required($arrayPost,$required){

    $errors=[];

    $champsPost=[];

    foreach ($arrayPost as $name => $valeur) {

        $champsPost[]=$name;
    }


    foreach ($required as $champs) {

        if(!in_array($champs, $champsPost) || str_replace(" ","", $arrayPost[$champs])==""){

            $errors[]=$champs;
        }
    }


    return $errors;

}


function dateFr($date){

    setlocale(LC_TIME, "fr_FR");

    return utf8_encode(strftime(" %d %B %Y ",strtotime($date))).date('à H:i:s',strtotime(($date)));
}

function MoisFr($mois){

    setlocale(LC_TIME, "fr_FR");

    return utf8_encode(strftime("%B",strtotime('01-'.$mois.'-2000')));
}


function dateFrNoTime($date){

    setlocale(LC_TIME, "fr_FR");

    return utf8_encode(strftime(" %d %B %Y ",strtotime($date)));
}





function ValiderMail($adresse,$nom, $numerodecompte)
{
    //$email_from = 'info@ezyshopci.com';
    $email = $adresse;
    $nom = $nom;
    $headers = 'From:doumgba-marche@doumgba-marche.com'."\r\n";
    $headers .= "Organization: Sender Organization\r\n";
    $headers .= 'Reply-To: doumgba-marche@doumgba-marche.com' . "\r\n";
    $headers .= "MIME-Version: 1.0". "\r\n";
    $headers .= 'Content-Type: text/html; charset="utf-8; charset=iso-8859-1" ' . "\r\n";
    $headers .= 'X-Mailer: ' . phpversion() . "\r\n";
    $message = '<Doctype html><html><body>';
    $message .= '<img width=100 src="http://doumgba-marche.com/_files/logo-1.png" alt="logo"  />';
    $message .= ' <h1>Bonjour ' . $nom . "</h1> \r\n";
    $message .= " <br>Félicitation vous êtes maintenant inscrit au Doumgba Marché ! Vous pourrez grâce à votre compte: \n";
    $message .= " <li> Achéter des articles </li>\r\n";
    $message .= " <li> Vendre des articles </li>\r\n";
    $message .= " <li> Livrer des article </li>\r\n";
    // $message .= "<p>Cliquez sur le liens ci desous pour <strong>confirmer votre adresse e-mail</strong> .<br><a href='http://doumgba-marche.com/confirmation-". $numerodecompte . "'>doumgba-marche.com/confirmation-" . $numerodecompte . "</a></p> \n";
    $message .= "<p> N'hésitez pas à inviter vos amis à découvrir nos offres et services. <br>Notre équipe est à votre service.</p> \r\n";
    $message .= '</body></html>';
    $sujet = 'Activation de compte Doumgba Marché';
    //adresse d'envoie
    mail($email, $sujet, $message, $headers);

    return true;
}

function redirec($lien){

    echo '<script> window.location="'.$lien.'"</script>';
    die();
}

function ModifierCode($adresse,$numerodecompte)
{
    //$email_from = 'info@ezyshopci.com';
    $email = $adresse;
    $headers = 'From:doumgba-marche@doumgba-marche.com'."\r\n";
    $headers .= "Organization: Sender Organization\r\n";
    $headers .= 'Reply-To: doumgba-marche@doumgba-marche.com' . "\r\n";
    $headers .= "MIME-Version: 1.0". "\r\n";
    $headers .= 'Content-Type: text/html; charset="utf-8; charset=iso-8859-1" ' . "\r\n";
    $headers .= 'X-Mailer: ' . phpversion() . "\r\n";
    $message = '<Doctype html><html><body>';
    $message .= '<img width=100 src="http://doumgba-marche.com/_files/logo-1.png" alt="logo" alt="doumgba-marche" />';
    $message .= ' <h1>Modification du mot de passe'."</h1> \r\n";
    $message .= " <br>Vous pouvez maintenent modifier votre mot de passe  \n";
    $message .= "<p>Cliquez sur le liens ci desous pour <strong>modifier votre mot de passe</strong> .<br><a href='http://doumgba-marche.com/restauration-". $numerodecompte . "'>http://doumgba-marche.com/restauration-" . $numerodecompte . "</a></p> \n";
    $message .= "<p>Si vous n'avez rien demander vous pouvez ignorer cet mail. <br>Notre équipe est à votre service.</p> \r\n";
    $message .= '</body></html>';
    $sujet = 'Modification de mot de passe';
    //adresse d'envoie
    mail($email, $sujet, $message, $headers);

    return true;
}


function CodeActivation($char=5)
{
    $nbcar = $char;
    $string = '';
    $chaine = strtoupper('abcdefghijklmnpqrstuvwxy123456789');
    srand((float)microtime() * 1000000);
    for ($i = 0; $i < $nbcar; ++$i) {
        $string .= $chaine[rand() % strlen($chaine)];
    }

    return $string;
}


function AlerteMail($texte=['Nouvelle commande','Nouvelle commande sur votre espace administrateur',
    'voir la commande','Alerte commande Modall','ouattaradesijeanmarie@gmail.com'])
{
    $email = $texte[4];
    $headers = 'From:doumgba-marche@doumgba-marche.com'."\r\n";
    $headers .= "Organization: Sender Organization\r\n";
    $headers .= 'Reply-To: doumgba-marche@doumgba-marche.com' . "\r\n";
    $headers .= "MIME-Version: 1.0". "\r\n";
    $headers .= 'Content-Type: text/html; charset="utf-8; charset=iso-8859-1" ' . "\r\n";
    $headers .= 'X-Mailer: ' . phpversion() . "\r\n";
    $message = '<Doctype html><html><body>';
    $message .= '<img width=100 src="http://doumgba-marche.com/_files/logo-1.png" alt="logo" alt="doumgba-marche" />';
    $message .= ' <h1>'.$texte[0]."</h1> \r\n";
    $message .= $texte[1]."<br> \n";
    $message .= "<p>Cliquez sur le liens ci dessous pour <strong>".$texte[2]."</strong> .<br><a href='https://doumgba-marche.com/'>Doumgba Marché</a></p> \n";
    $message .= '</body></html>';
    $sujet = $texte[3];
    //adresse d'envoie
    mail($email, $sujet, $message, $headers);

    return true;
}


function EnvoieMail($adresse,$messageEmail)
{
    //$email_from = 'info@ezyshopci.com';
    $email = $adresse;
    $headers = 'From:ezyshopci@ezyshopci.com'."\r\n";
    $headers .= "Organization: Sender Organization\r\n";
    $headers .= 'Reply-To: ezyshopci@ezyshopci.com' . "\r\n";
    $headers .= "MIME-Version: 1.0". "\r\n";
    $headers .= 'Content-Type: text/html; charset="utf-8; charset=iso-8859-1" ' . "\r\n";
    $headers .= 'X-Mailer: ' . phpversion() . "\r\n";
    $message = '<Doctype html><html><body>';
    $message .= '<img width=100 src="https://ezyshopci.com/assets/img/logo/logo-2.png" alt="logo" alt="Ezyshopci" /><br><br>';
    $message .= " $messageEmail <br> \n";
    $message .= "<p>Cliquez sur le liens ci desous pour <strong> visiter notre site</strong> .<br><a href='https://ezyshopci.com'>ezyshopci.com</a></p> \n";
    $message .= '</body></html>';
    $sujet = 'Nouveau message';
    //adresse d'envoie
    mail($email, $sujet, $message, $headers);

    return true;
}


function TokenGenerate($form='token'){

    $length = 40;
    $token = bin2hex(openssl_random_pseudo_bytes($length));
    $_SESSION[$form]=$token;
    $inputToken="<input name='$form' value='$token' type='hidden' >";
    return $inputToken;
}

function TokenGenerateEgain(){

    $length = 40;
    $token = bin2hex(openssl_random_pseudo_bytes($length));
    $_SESSION['token2']=$token;
    $inputToken="<input name='token2' value='$token' type='hidden' >";
    return $inputToken;
}


function HeaderXframe($niveau=0,$site=null){

	switch ($niveau) {

		case 0:
			# tout les sites peuvent m'integrer en iframe

			 header('X-Frame-Options: ALLOWALL');

			break;

		case 1:
			# les page de la meme origine peuvent m'integrer en iframe

			 header('X-Frame-Options: SAMEORIGIN');

			break;

		case 2:
			# Un site precis peut m'integrer en iframe

			 header('X-Frame-Options: ALLOW-FROM https://".$site."/');

			break;

		case 3:
			# aucun site peut m'integrer en iframe

			 header('X-Frame-Options: DENY');

			break;
		
		default:
			# tout les sites peuvent m'integrer en iframe

			 header('X-Frame-Options: ALLOWALL');

			break;
	}


}

function CaptchaV2($response_post){

    $post_data = "secret=6LfdUvcUAAAAANlk42FQw1EKvXAhj29KaTnvaSUm&response=".$response_post."&remoteip=".$_SERVER['REMOTE_ADDR'] ;

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8', 'Content-Length: ' . strlen($post_data)));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $googresp = curl_exec($ch);      
    $decgoogresp = json_decode($googresp);
    curl_close($ch);

    return $decgoogresp;
}


function VerifieCaptcha($user_response) {

    $fields_string = '';
    $fields = array(
        'secret' => '6LfdUvcUAAAAANlk42FQw1EKvXAhj29KaTnvaSUm',
        'response' => $user_response
    );
    foreach($fields as $key=>$value)
    $fields_string .= $key . '=' . $value . '&';
    $fields_string = rtrim($fields_string, '&');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPTtableauPost, count($fields));
    curl_setopt($ch, CURLOPTtableauPostFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

    $result = curl_exec($ch);
    curl_close($ch);

     // <div class="g-recaptcha" data-sitekey="__Site_Key__"></div>

    return json_decode($result, true);
}



function ContactMail($tableauPost)
{
    $email = "ouattaradesijeanmarie@gmail.com";
    $headers = 'From:doumgba-marche@doumgba-marche.com'."\r\n";
    $headers .= "Organization: Sender Organization\r\n";
    $headers .= 'Reply-To: doumgba-marche@doumgba-marche.com' . "\r\n";
    $headers .= "MIME-Version: 1.0". "\r\n";
    $headers .= 'Content-Type: text/html; charset="utf-8; charset=iso-8859-1" ' . "\r\n";
    $headers .= 'X-Mailer: ' . phpversion() . "\r\n";
    $message = ' <h1><img width=100 src="http://doumgba-marche.com/_files/logo-1.png" alt="logo" alt="doumgba-marche" /> <br> Message d\'un visiteur</h1>'." \r\n";

    $message .= ' <p>NOM & PRENOM : <b> '.strip_tags(ucwords($tableauPost['nom'])).' </b>'."</p> \r\n";
    $message .= ' <p>E-MAIL : <b> '.strip_tags(ucwords($tableauPost['email'])).' </b>'."</p> \r\n";
    $message .= ' <p>CONTACT : <b> '.strip_tags(ucwords($tableauPost['contact'])).' </b>'."</p>\r\n ";
    $message .= ' <p><br><br> '.strip_tags(ucfirst($tableauPost['message'])).' '."</p> \r\n";


    $sujet = ucwords($tableauPost['objet']);

    return mail($email, $sujet, $message, $headers);
}

function DevisMail($tableauPost)
{
    $boundary = md5(uniqid(microtime(), TRUE));

    $facture=$_FILES['facture']['name'];

    move_uploaded_file($_FILES['facture']['tmp_name'] , "../files/$facture");

    $email = "ouattaradesijeanmarie@gmail.com";
    $headers = 'From:topchrono@topchrono.com'."\r\n";
    $headers .= 'Mime-Version: 1.0'."\r\n";
    $headers .= 'Content-Type: multipart/mixed;boundary='.$boundary."\r\n";
    $headers .= "\r\n";
    $msg = 'Texte affiché par des clients mail ne supportant pas le type MIME.'."\r\n\r\n";

    $message = 'Content-Type: text/html; charset="utf-8"; charset=iso-8859-1;' . "\r\n";

    $message .= "

    <h1><img src='https://cabinetieltc.com/Top/files/logotop.png' width='200px'> <br> Demande de devis </h1>

    <p>NOM & PRENOM : <b> ".strip_tags(ucwords($tableauPost['Nom'])).' </b>'."</p>

    <p>ADRESSE E-MAIL  : <b> ".strip_tags($tableauPost['Email']).' </b>'."</p>

    <p>DIMENSION : <b> ".strip_tags($tableauPost['Dimension']).' </b>'."</p>

    <p>POIDS (KG) : <b> ".strip_tags($tableauPost['Poids']).' </b>'."</p>

    <p>ADRESSE D'ENLEVEMENT: <b> ".strip_tags(ucwords($tableauPost['Adresse'])).' </b>'."</p>

    <p>NATURE DU COLIS : <b> ".strip_tags(ucwords($tableauPost['Colis'])).' </b>'."</p>

    <p>FACTURE D'ACHAT : <b> Pièce jointe </b>"."</p>";


    $sujet = 'Demande de devis';

    $file_name = "../files/".$facture;

    if (file_exists($file_name))
    {
        $file_type = filetype($file_name);
        $file_size = filesize($file_name);
        $handle = fopen($file_name, 'r') or die('File '.$file_name.'can t be open');
        $content = fread($handle, $file_size);
        $content = chunk_split(base64_encode($content));
        $f = fclose($handle);
        $msg .= '--'.$boundary."\r\n";
        $msg .= 'Content-type:'.$file_type.';name='.$file_name."\r\n";
        $msg .= 'Content-transfer-encoding:base64'."\r\n\r\n";
        $msg .= $content."\r\n"; 
    }

    
    $msg .= '--'.$boundary."\r\n ".$message;


    return mail($email, $sujet, $msg, $headers);
}

function AjaxQueryJS($id_form){

	?>
		<script
		  src="https://code.jquery.com/jquery-3.5.1.js"
		  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
		  crossorigin="anonymous"></script>
	     <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
		 <script type="text/javascript">
		 	

	        $('.<?= $id_form ?>').submit(function(e){


	            e.preventDefault();

	            var form = $(this).serialize();

	            $.ajax({
	                    type: 'POST',
	                    url: $(this).attr('action'),
	                    data: form,
	                    dataType: 'json',
	                    success: function (result){


	                        if(result.status=="success"){

	                            swal("Demade", result.message, result.status);  
	                            setTimeout(()=>window.location.reload(),2000);

	                        }else{
	                            swal("Demade", result.message, result.status);
	                        }

	                        
	                    }
	                });

	        });

		 </script>

	<?php  
}
