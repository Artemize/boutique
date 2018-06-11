<?php require_once("inc/header.php");
    
    $page = 'Inscription';
    if($_POST)
    {
        // debug($_POST, 2);                
        if(!empty($_POST['pseudo']))
        {
            $verif_pseudo = preg_match('#^[a-zA-Z0-9._]{3,20}$#', $_POST['pseudo']);
           //la fonction preg_match me permet de définir les caractères autorisé dans une     STR/VAR.
            // elle attend deux argument REGEX ou expression regulière.Elle attend 2 arguments:REGEX expression regulière + ma STR/VAR a chercher
            // Elle renvoit un true(succées) ou false(echec)

            if(!$verif_pseudo)
            {
                $msg_erreur .= "<div class='alert alert-danger'>votre
                 pseudo doit comporter de 3 à 20 caracatères
                 (majuscule, minuscule, chiffre et signe '.', '_', '-' acceptés)</div>";

            }
            
        }
        else 
            {
                $msg_erreur .= "<div class='alert alert-danger'>veuillez rentrer un  pseudo valide</div>";
            }

            //verif mdp
            if (!empty($_POST['password'])) 
            {
                $verif_mdp = preg_match('#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*\'\?$@%_])([-+!*\?$\'@%_\w]{6,15})$#', $_POST['password']); 
                
                /*
                    Le mot de passe doit contenir :
                        - 6 caractères minimum
                        - 15 caractères maximum
                        - 1 majuscule
                        - 1 minuscule
                        - 1 chiffre
                        - 1 caractère spécial
                    
                    Mot de passe à copier-coller pour tester : Test2Mdp@Yeah
                */
        
                if(!$verif_mdp)
                {
                    $msg_erreur .= "<div class='alert alert-danger'>Votre mdp doit 
                    comporter entre 6 et 15
                     cara dont une maj une min un chiffre et un carac special</div>";
                }
        
            }
            else
            {   
                $msg_erreur .= "<div class='alert alert-danger'>rentrer un mdp valide</div>";
                                 
            }
            // verification
            if (!empty($_POST['email'])) 
            {
                $verif_email = filter_var($_POST['email'],FILTER_VALIDATE_EMAIL);// la fonction filter_var nous permet
                //de verifier une string (emal,url-> FILTER_VALIDATE_URL ...) Elle prend 2 arguments: la STR + la methode. Elle retourne un DOOL
                
                $dom_interdit = 
                [
                    'mailinator.com','yopmail.com','mail.com'
                ];
                $dom_email = explode('@',$_POST['email']);// la fonction explode() nous permet dexploser une string/variable
                // a partir de l'elemnt choisis en premiere argument
                if(!$verif_email || in_array($dom_email[1],$dom_interdit))
                
                {
                    $msg_erreur.= "<div class='alert alert-danger'>renseigner un email valide</div>";
                }
                
            }
            else
            {
                $msg_erreur .= "<div class='alert alert-danger'>Veuillez renseignez un email valide</div>";
            }
            if(!isset($_POST['civilite']) || $_POST['civilite'] !== "m" && $_POST['civilite'] !== "f" && 
            $_POST['civilite'] !== "o")
            {
                $msg_erreur .= "<div class='alert alert-danger'>Veuillez rentrer une civilité valide</div>";
            }
    
                // autres verif possibles

            if(empty($msg_erreur))
            {
                // verification si le pseudo libre
                $resultat = $pdo-> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");

                $resultat -> bindvalue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
                $resultat -> execute();

                if($resultat->rowCount()>0) // signifie qu'on a une ligne de resultat dans la bdd
                {
                    $msg_erreur.= "<div class='alert alert-danger'>le pseudo" .$_POST['$pseudo'] . " éxiste déjà!!Recommence Non d'une pipe!</div>";

                }
                else
                {
                    $resultat= $pdo->prepare("INSERT INTO membre
                    (pseudo, mdp, nom, prenom, email, civilite, ville,
                    code_postal, adresse, statut) VALUES (:pseudo, :mdp, :nom,
                    :prenom, :email, :civilite, :ville, :code_postal, :adresse, 0)");

                    $mdp_crypte = password_hash($_POST['password'],PASSWORD_BCRYPT); // la fonction password_hash() nous permet 
                //de securiser l'enregistrement du mdp en BDD. elle prend 2 argument: l'element a 
                //hasher + la methelogie d'hashage

                $resultat ->bindValue(':pseudo', $_POST['pseudo'],PDO::PARAM_STR);
                $resultat ->bindValue(':mdp', $mdp_crypte, PDO::PARAM_STR);
                $resultat ->bindValue(':nom', $_POST['nom'],PDO::PARAM_STR);
                $resultat ->bindValue(':prenom', $_POST['prenom'],PDO::PARAM_STR);
                $resultat ->bindValue(':email', $_POST['email'],PDO::PARAM_STR);
                $resultat ->bindValue(':civilite', $_POST['civilite'],PDO::PARAM_STR);
                $resultat ->bindValue(':ville', $_POST['ville'],PDO::PARAM_STR);
                $resultat ->bindValue(':adresse', $_POST['adresse'],PDO::PARAM_STR);
                $resultat ->bindValue(':code_postal', $_POST['code_postal'],PDO::PARAM_INT);
                
                if ($resultat->execute())
                {
                    header('location:connexion.php');
                }
            }
    
        }

    }
    // debug($dom_email);


    // traitement pour re-afficher les valeurs rentrées en cas 
    //de rechargement de la page et erreur d'inscription

    $pseudo = (isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';

    //ici nous mettons une condition : si on recoit du POST, 
    //alors ma variable contiendras la valeur envoyer sinon la valeur seras vide

    $nom = (isset($_POST['nom'])) ? $_POST['nom'] : '';
    $prenom = (isset($_POST['prenom'])) ? $_POST['prenom'] : '';
    $email = (isset($_POST['email'])) ? $_POST['email'] : '';
    $ville = (isset($_POST['ville'])) ? $_POST['ville'] : '';
    $adresse = (isset($_POST['adresse'])) ? $_POST['adresse'] : '';
    $code_postal = (isset($_POST['code_postal'])) ? $_POST['code_postal'] : '';
    
    
?>

    <div class="container text-center">
        <h1><?= $page?></h1>

        <p class="lead">Super e-commerce</p>

        <p class="mt-5">Si vous avez déjà un compte, connectez-vous !</p>
        <a name="" id="" class="btn btn-success mt-2" href="<?= URL ?>connexion.php" role="button">Se connecter</a>


        <form action="" method="post" class="mt-5 border-top pt-5 border-bottom pb-5">
        <?=$msg_erreur?>
            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="pseudo" id="pseudo" placeholder="Votre pseudo" value ="<?=$pseudo?>">
                </div>
                <div class="form-group col-md-6">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Votre mot de passe" >
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <input type="text" class="form-control" name="nom" id="nom" placeholder="Votre nom" value ="<?=$nom?>">
                </div>
                <div class="form-group col-md-4">
                    <input type="text" class="form-control" name="prenom" id="prenom" placeholder="Votre prénom" value ="<?=$prenom?>">
                </div>
                <div class="form-group col-md-4">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Votre email"value ="<?=$email?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                        <select class="form-control" name="civilite" id="exampleFormControlSelect1">
                            <option selected disabled>-- Choisissez votre civilité --</option>
                            <option value="m">M.</option>
                            <option value="f">Mme</option>
                            <option value="o">Autre</option>
                        </select>
                </div>
                <div class="form-group col-md-4">
                    <input type="text" class="form-control" name="ville" id="ville" placeholder="Votre ville" value ="<?=$ville?>">
                </div>
                <div class="form-group col-md-4">
                    <input type="text" class="form-control" name="adresse" id="adresse" placeholder="Votre adresse" value ="<?=$adresse?>">
                </div>
                <div class="form-group col-md-4">
                    <input type="text" class="form-control" name="code_postal" id="code_postal" placeholder="Votre code postal" value ="<?=$code_postal?>">
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <input type="submit" value="s'inscrire">
                </div>
            </div>

        </form>
    </div>
       
<?php require_once("inc/footer.php"); ?>