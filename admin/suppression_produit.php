<?php require_once('inc/header.php');




if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) // verification si il existre le GET 'id' + 
//il est rempli + c'est un chiffre

{
    $req = "SELECT * FROM produit WHERE id_produit= :id";
    $resultat = $pdo->prepare($req);
    $resultat->bindValue(':id',$_GET['id'], PDO::PARAM_INT);
    $resultat->execute();


    if($resultat->rowCount() > 0)
    {
        $produit = $resultat->fetch();

        $req2 = "DELETE FROM produit WHERE id_produit = $produit[id_produit]";

        $resultat2 = $pdo->exec($req2);

        if($resultat !== FALSE)
        {
            $chemin_photo_suppression = RACINE_SITE .'assets/upload/img/' . $produit['photo'];

            if (file_exists($chemin_photo_suppression) && $produit['photo'] != 'default.jpg')
             // cette fonction permet de verifier si un fichier existe
            {
                unlink($chemin_photo_suppression);
                // cette fonction nous permet de supprimer le fichier selectionné

            }
            header('location:' . URL .'admin/liste_produit.php');
            
        }
    }
    else 
    {
        header('location:' . URL .'admin/liste_produit.php');
    }
    
}
else 
{
    header('location:' . URL .'admin/liste_produit.php');
}

?>


