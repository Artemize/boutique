<?php

// creation d'une fonction pour afficher les var_dump() & print_r()

function debug($var)
{
    echo'<div style="color: white;font_weight: bold; padding: 20px; background: #' .rand(111, 999) .'">';

    $trace = debug_backtrace();// la fonction debug_backtrace() permet de recup des infos par rapport a l'endroit d'une erreur.
    $trace = array_shift($trace);// la fonction array_shift() me permet de casser le premier rang d'un array multi pour me renvoyer les premlier résultat

    
    echo "le debug a été demandé dans le fichier $trace[file] à la ligne $trace[line] <hr>";

    echo '<pre>';

    switch ($mode) {
        case '1':
        var_dump($var);
            break;
        
        default:
        print_r($var);
            break;
    }
    echo'</pre>';    


echo'</div>';    
   
}
// fonction pour verifier user = connecté


function userConnect()
{
    if (isset($_SESSION['membre'])) 
    {
        return  TRUE;
    }
    else 
    {
       return FALSE;
    }


    if(isset($_SESSION['membre'])) return TRUE;
    else return FALSE;

}
//FONCTION POUR VERIFIER USER=ADMIN
function userAdmin()
{
    if (userConnect() && $_SESSION['membre']['statut'] ==1) 
    return TRUE;
    else return FALSE;
    
}



?>