<?php require_once("inc/header.php");

$page = "connexion";
if ($_POST) 
{
   //debug($_POST);
   $req = "SELECT * FROM membre WHERE pseudo = :pseudo";
   $resultat =$pdo->prepare($req);
   $resultat->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
   $resultat->execute();

   if ($resultat->rowCount()>0)// si on trouve un resultat pour le pseudo
    {
       $membre = $resultat->fetch();

        // debug($membre);

       if (password_verify($_POST['password'], $membre['mdp'])) 
       {
        //    $_SESSION['pseudo'] = $membre['pseudo']  
        
        
            foreach ($membre as $key => $value) 
            {
                    if($key != "mdp")
                    {
                        $_SESSION['membre'][$key] = $value;

                        header('location:profil.php');
                    
                    }
            }       
        
       }
       else
       {
        $msg_erreur .= "<div class='alert alert-danger'>Id 
        ou Mdp incorrect</div>";
       }
    }
    else
    {
    $msg_erreur .= "<div class='alert alert-danger'>Id ou 
    Mdp incorrect</div>";
        
    }
}


?>
    <h1><?= $page?></h1>
    <p class="lead">super ecommerce</p>
    <form action="" method="post">
    <?=$msg_erreur?>
        <div class="form-group col-md-6">
            <input type="text" class="form-control" name="pseudo" id="pseudo" placeholder="Votre pseudo" >
        </div>
        <div class="form-group col-md-6">
            <input type="password" class="form-control" name="password" id="password" placeholder="Votre mot de passe" >
        </div> 
        <div class="row mt-4">
            <div class="col-12">
                <input type="submit" value="Connexion">
            </div>
        </div>
    </form>

    <?php require_once("inc/footer.php");

    