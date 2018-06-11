<?php require_once('inc/header.php');

$resultat = $pdo->query("SELECT * FROM membre");
$membres = $resultat->fetchALL();

$contenu .= "<table class='table'>";
$contenu .= "<thead><tr>";

for ($i = 0; $i < $resultat->columnCount(); $i++) {
    $champs = $resultat->getColumnMeta($i);
    $contenu .= "<th>" . $champs['name'] . "</th>";
}

$contenu .= "<th colspan='2'>Actions</th>";
$contenu .= "</tr></thead><tbody>";
foreach ($membres as $membre ) {
    $contenu .= "<tr>";
    foreach ($membre as $key => $value) {
        if ($key == 'photo') {
            $contenu .= '<td><img height="150" src="' . URL . 'assets/upload/img/' . $produit['photo'] .'"/></td>';
        } else {
            $contenu .= "<td>" . $value . "</td>";
        }
    }
    
    $contenu .= "<td><a href='" . URL . 
    "admin/gestion_membres.php?id=" . $membre ['id_membre'] . "'>Modifier</a></td>";
    $contenu .= "<td><a href='" . URL . 
    "admin/suppression_membres.php?id=" . $membre['id_membre'] . "'>Supprimer</a></td>";
    $contenu .= "</tr>";
}

$contenu .= "</tbody></table>";




?>
<h1>Gestion membres</h1>

<?= $contenu  ?>

<h2>YOYOYOYOYOYOYOYOYOYOYOYOYOY</h2>


<?php require_once('inc/footer.php')?>