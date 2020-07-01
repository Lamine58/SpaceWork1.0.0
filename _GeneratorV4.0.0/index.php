<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        
    <!-- Bootstrap core CSS -->
        <link href="bootstrap/bootstrap.min.css" rel="stylesheet">  

        <title>Generateur</title>
    </head>

    <body>

        <!--<h1>Generator de class et de manager</h1>-->
        <div  class="row container-fluid" style="padding-top:5%">

            <div class="col-md-4"></div>
                <div class="col-md-4">
                    <?php if(!isset($_GET["page"])){ ?>
                        <form method="POST" action="index.php?page=1" class="text-left">
                            <div class="form-group">
                                <label for="inputHote">Hôte</label>
                                <input name="host-name" type="text" class="form-control form-control-sm" id="inputHote" required>
                            </div>
                            <div class="form-group">
                                <label for="inputdb">Base de données</label>
                                <input name="db-name" type="text" class="form-control form-control-sm" id="inputdb" required>
                            </div>
                            <div class="form-group">
                                <label for="inputUtilisateur">Nom d'utilisateur</label>
                                <input name="user-name" type="text" class="form-control form-control-sm" id="inputUtilisateur" required>
                            </div>
                            <div class="form-group">
                                <label for="mdp">Mot de passe</label>
                                <input name="password" type="password" class="form-control form-control-sm" id="mdp">
                            </div>
                           <!--  <div class="form-group">
                                <label for="inputFolder">Lien du dossier recepteur <small>(Se crée s'il n'existe pas)</small></label>
                                <input name="folder" type="text" class="form-control form-control-sm" id="inputFolder" required>
                            </div> -->
                            <div class="form-group text-center">
                                <br>
                                <button name="db-connect" type="submit" class="btn btn-primary">Connexion</button>
                            </div>
                        </form>
                    <?php }elseif(isset($_GET["page"]) and $_GET["page"] == "1"){ include("table-gertion.php");} ?>
                <div class="col-md-4"></div>
            </div>

        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    </body>

</html>
