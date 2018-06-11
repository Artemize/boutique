<?php require_once('inc/header.php');

// debug($_POST);
if($_POST)
{
  if(!empty($_FILES['photo']['name'])) // je regarde si une photo et uploader
    {
      // je donne une référence aléatoire unique au nom de ma photo
        $nom_photo = $_POST['reference']. '_' . time() . '_' . rand(1,999) . '_' . $_FILES['photo']['name'];
        
        // on enregistre dans cette variable le chemin définitif de ma photo
        $chemin_photo = RACINE_SITE . 'assets/upload/img/' . $nom_photo;

        // vérification de la taille de la photo
        if($_FILES['photo']['size'] > 2000000 )
        {
            $msg_erreur .= "<div class='alert alert-danger'>
            Veuillez selectionner un fichier de 2mo max </div>";
        }
        $tab_type = ['image/jpeg', 'image/png/','image/gif'];
        if(!in_array($_FILES['photo']['type'],$tab_type))// on regarde que le type de photo envoyée avec $_FILES est conforme aux types rentrés dans notre ARRAY

        {
            $msg_erreur .= "<div class='alert alert-danger'>
            Veuillez selectionner un fichier jpeg/png ou gif </div>";

        }
        elseif (isset($_POST['photo_actuelle'])) {
            $nom_photo = $_POST['photo_actuelle'];
        }
   }
   else    {
       $nom_photo = 'default.jpg';
    }

   //verification des autres champs
   //remplis, nombre de caractère, informations numériques en stock/prix...
   
    if(empty($msg_erreur))
    {

        if(!empty($_POST['id_produit']))
        {
          
            $resultat = $pdo->prepare("REPLACE INTO produit(id_produit, reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES
            (:id_produit, :reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");
            
            $resultat->bindValue(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
        }
        else // on enregistre en bdd pour la premiere fois
        {
           
        }
        $resultat = $pdo->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES
        (:reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");
        
        
        $resultat->bindValue(":reference", $_POST['reference'], PDO::PARAM_STR);
        $resultat->bindValue(":categorie", $_POST['categorie'], PDO::PARAM_STR);
        $resultat->bindValue(":titre", $_POST['titre'], PDO::PARAM_STR);
        $resultat->bindValue(":description", $_POST['description'], PDO::PARAM_STR);
        $resultat->bindValue(":couleur", $_POST['couleur'], PDO::PARAM_STR);
        $resultat->bindValue(":taille", $_POST['taille'], PDO::PARAM_STR);
        $resultat->bindValue(":public", $_POST['public'], PDO::PARAM_STR);
        $resultat->bindValue(":prix", $_POST['prix'], PDO::PARAM_STR);

        $resultat->bindValue(":stock",$_POST['stock'], PDO::PARAM_INT);

        $resultat->bindValue(":photo",$nom_photo, PDO::PARAM_STR);

        if ($resultat->execute()) // si la requêtes est bien enregsitrée en BDD
        {
           if(!empty($_FILES['photo']['name']))
           {
            copy($_FILES['photo']['tmp_name'], $chemin_photo);
           } 
            
        }

        
    }
    

}

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) // verification si il existre le GET 'id' + 
//il est rempli + c'est un chiffre

{
    $req = "SELECT * FROM produit WHERE id_produit= :id";
    $resultat = $pdo->prepare($req);
    $resultat->bindValue(':id',$_GET['id'], PDO::PARAM_INT);
    $resultat->execute();


    if($resultat->rowCount() > 0)
    {
        $produit_actuel = $resultat->fetch();
    }

}

$reference = (isset($produit_actuel)) ? $produit_actuel['reference'] : '';
$categorie = (isset($produit_actuel)) ? $produit_actuel['categorie'] : '';
$titre = (isset($produit_actuel)) ? $produit_actuel['titre'] : '';
$description = (isset($produit_actuel)) ? $produit_actuel['description'] : '';
$couleur = (isset($produit_actuel)) ? $produit_actuel['couleur'] : '';
$taille = (isset($produit_actuel)) ? $produit_actuel['taille'] : '';
$public = (isset($produit_actuel)) ? $produit_actuel['public'] : '';
// $photo = (isset($produit_actuel)) ? $produit_actuel['photo'] : '';
$prix = (isset($produit_actuel)) ? $produit_actuel['prix'] : '';
$stock = (isset($produit_actuel)) ? $produit_actuel['stock'] : '';
$action= (isset($produit_actuel)) ? 'Modifier':'stock' ; 
$id_produit = (isset($produit_actuel)) ? $produit_actuel['id_produit'] : '';
$photo = (isset($produit_actuel)) ? $produit_actuel['photo'] : '' ;

?>
<h1>Ajouter un produit</h1>
<form action="" method="post" enctype="multipart/form-data">

    <?= $msg_erreur ?>

    <div class="row">
        <div class="form-group col-md-4">
            <input type="text" class="form-control" name="reference" id="reference" aria-describedby="helpId" placeholder="Référence produit" value="<?= $reference ?>">
        </div>
        <div class="form-group col-md-4">
            <input type="text" class="form-control" name="categorie" id="categorie" aria-describedby="helpId" placeholder="Catégorie produit" value="<?= $categorie ?>">
        </div>
        <div class="form-group col-md-4">
            <input type="text" class="form-control" name="titre" id="titre" aria-describedby="helpId" placeholder="Titre du produit" value="<?= $titre ?>">
        </div>
    </div>

    <div class="form-group">
        <textarea class="form-control" name="description" id="description" rows="10" cols="30" placeholder="Description du produit"><?= $description ?></textarea>
    </div>
    <div class="form-group">
        <label for="public">public</label>
        <select  class="form-control" name="public" id="public">
            <option <?php if($public =='homme'){echo'selected';}?> value="homme" >homme</option>
            <option <?php if($public =='femme'){echo'selected';}?> value="femme" >femme</option>
            <option <?php if($public =='mixte'){echo'selected';}?> value="mixte" >mixte</option>          
        </select>
    <div class="form-group">
        <label for="couleur">couleur</label>
        <select  class="form-control" name="couleur" id="couleur">
            <option <?php if($couleur == 'noir'){echo'selected';}?> >noir</option>
            <option <?php if($couleur == 'blanc'){echo'selected';}?> >blanc</option>
            <option <?php if($couleur == 'rouge'){echo'selected';}?> >rouge</option>
            <option <?php if($couleur == 'jaune'){echo'selected';}?> >jaune</option>
            <option <?php if($couleur == 'vert'){echo'selected';}?> >vert</option>
            <option <?php if($couleur == 'violet'){echo'selected';}?> >violet</option>
            <option <?php if($couleur == 'moutarde'){echo'selected';}?> >moutarde</option>
            <option <?php if($couleur == 'rose'){echo'selected';}?> >rose</option>
            <option <?php if($couleur == 'saumon'){echo'selected';}?> >saumon</option>           
        </select>
    </div>
    <div class="form-group">
        <label for="taille">taille</label>
        <select name="taille" class="form-control" id="taille">
                <option <?php if(empty($taille)){echo 'selected';}?> disabled>-- Choisissez la taille --</option>
                <option <?php if($taille == "xs"){echo 'selected';}?> value="xs">XS</option>
                <option <?php if($taille == "s"){echo 'selected';}?> value="s">S</option>
                <option <?php if($taille == "m"){echo 'selected';}?> value="m">M</option>
                <option <?php if($taille == "l"){echo 'selected';}?> value="l">L</option>
                <option <?php if($taille == "xl"){echo 'selected';}?> value="XL">XL</option>
            </select>
    </div>
    <div class="form-group">
        <label for="public">public</label>
        <select name="public" class="form-control" id="public">
                <option <?php if(empty($public)){echo 'selected';}?> disabled>-- Choisissez le publique --</option>
                <option <?php if($public == "homme"){echo 'selected';}?> value="homme">Homme</option>
                <option <?php if($public == "femme"){echo 'selected';}?> value="femme">Femme</option>
                <option <?php if($public == "mixte"){echo 'selected';}?> value="mixte">Mixte</option>
            </select>
    </div>
    <div class="form-group">
        <label for="photo">photo produit</label>
        <input type="file" class="form-control-file" name="photo" id="photo">
        <?php
                // Si je modifie un produit
                if(isset($produit_actuel))
                {
                    echo "<input name='photo_actuelle' value=" . $photo . " type='hidden'>";
                    echo "<img style='width:20%;' src='" . URL . "assets/uploads/img/" . $photo . "'>";
                }
            ?>
    </div>
    <div class="form-group">        
        <input type="text" class="form-control" name="prix" id="prix" placeholder="prix du produit">
        <!-- alerte = si float en bdd alors le type et text -->
    </div>
    <div class="form-group ">
        <input type="text" class="form-control" name="stock"  
        placeholder="stock du produit" >
    </div>
    <input type="submit" class="btn btn-primary"  value="<?= $action?>">
</form>
<?php require_once('inc/footer.php')?>