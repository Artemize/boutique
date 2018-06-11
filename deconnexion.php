<?php

require_once('inc/init.php');

unset($_SESSION['membre']);//on supprime seulement la partie membre de la SESSION
//et on regarde le reste

header('location:index.php');

?>