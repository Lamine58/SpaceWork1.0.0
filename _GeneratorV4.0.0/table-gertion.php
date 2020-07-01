<?php
    //Connexion a la base de données
    include("traitement.php");
    //table_name_list();
?>
<form method="POST" class="text-left" action="index.php?page=1">
    <div style="visibility:hidden; position:absolute;">
        <input name="host-name" type="text" value="<?php echo $_POST["host-name"];?>">
        <input name="db-name" type="text" value="<?php echo $_POST["db-name"];?>">
        <input name="user-name" type="text" value="<?php echo $_POST["user-name"];?>">
        <input name="password" type="text" value="<?php echo $_POST["password"];?>">
        <input name="folder" type="text" value="<?php echo $_POST["folder"];?>">
    </div>
    <div class="form-group">
        <label for="selectTable">Sélectionnez votre table</label>
        <select name="table-name" type="text" class="form-control form-control-sm" id="selectTable">
            <?php
                //Affichage des noms de table de la base de données
                echo get_list_table($_POST["db-name"], $conn); //Cette ligne affichait le tableau dans le navigateur donc je l'ai mis en commentaire
            ?>
        </select>
    </div>
    <div class="form-group text-center">
        <br>
        <button name="g-class" type="submit" class="btn btn-primary" style="width:100%">Générer Class</button>
    </div>
</form>
