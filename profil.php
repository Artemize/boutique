<?php require_once("inc/header.php");

$page = 'bienvenue chez toi bebe' . $_SESSION['membre']['pseudo']. '!';

// if (!isset($_SESSION['membre'])) 
// {
//     header('location:connexion.php');
// }
if(!userConnect())
{
    header('location:connexion.php');
    exit();
}
?>

       
    <h1>Bienvenue</h1>
    <p class="lead"></p>
    <ul>
        <li>votre nom: <?= $_SESSION['membre']['nom']?></li>
        <li>votre nom: <?= $_SESSION['membre']['prenom']?></li>
        <li>votre nom: <?= $_SESSION['membre']['email']?></li>
    </ul>
       

<?php require_once("inc/footer.php"); ?>