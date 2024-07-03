<?php
include 'bd.php';

session_start();

if (!isset($_SESSION['email_entreprise'])) {
    header("Location: Accueil.php");
    exit;
}

$emailEntrepriseConnectee = $_SESSION['email_entreprise'];

$sqlIdEntreprise = "SELECT idEntreprise, nomEntreprise FROM Entreprise WHERE emailEntreprise = ?";
$stmtIdEntreprise = $conn->prepare($sqlIdEntreprise);

if ($stmtIdEntreprise) {
    $stmtIdEntreprise->bind_param("s", $emailEntrepriseConnectee);
    $stmtIdEntreprise->execute();
    $stmtIdEntreprise->bind_result($idEntreprise, $nomEntreprise);
    $stmtIdEntreprise->fetch();
    $stmtIdEntreprise->close();
} else {
    echo "Erreur lors de la préparation de la requête pour récupérer l'id de l'entreprise : " . $conn->error;
    exit;
}

$sqlAnnonces = "SELECT a.idAnnonce, a.nomJob, a.nombrePersonnesRecherche, a.descriptionPoste, a.descriptionRecherchePersonne, e.lieuEntreprise AS entrepriseLieu, e.numeroDeTelephone, e.emailEntreprise FROM annonce a INNER JOIN Entreprise e ON a.idEntreprise = e.idEntreprise WHERE a.idEntreprise = ?";
$stmtAnnonces = $conn->prepare($sqlAnnonces);

if ($stmtAnnonces) {
    $stmtAnnonces->bind_param("i", $idEntreprise);
    $stmtAnnonces->execute();
    $stmtAnnonces->bind_result($idAnnonce, $nomJob, $nombrePersonnesRecherche, $descriptionPoste, $descriptionRecherchePersonne, $lieuEntreprise, $numeroDeTelephone, $emailEntreprise);
    $stmtAnnonces->store_result();
} else {
    echo "Erreur lors de la préparation de la requête pour récupérer les annonces : " . $conn->error;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_annonce'])) {
    $idAnnonceASupprimer = $_POST['idAnnonce'];

    $sqlSupprimerAnnonce = "DELETE FROM Annonce WHERE idAnnonce = ?";
    $stmtSupprimerAnnonce = $conn->prepare($sqlSupprimerAnnonce);

    if ($stmtSupprimerAnnonce) {
        $stmtSupprimerAnnonce->bind_param("i", $idAnnonceASupprimer);
        $stmtSupprimerAnnonce->execute();
        $stmtSupprimerAnnonce->close();

        // Redirection vers la page actuelle pour actualiser la liste des annonces après la suppression
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Erreur lors de la suppression de l'annonce : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entreprise Dashboard</title>
    <link rel="stylesheet" href="cssEntrepriseDashboard.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="EntrepriseDashboard.php">Accueil</a></li>
                <li><a href="NouvelleAnnonce.php">Annonces</a></li>
                <li class="name"><?= isset($nomEntreprise) ? $nomEntreprise : "Nom de l'entreprise"; ?></li>
                <li><a href="EntrepriseProfil.php">Profil</a></li>
                <li><a href="Deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Annonces de <?= isset($nomEntreprise) ? $nomEntreprise : "l'entreprise"; ?></h2>

        <?php
            while ($stmtAnnonces->fetch()) {
                echo '<div class="annonce-container">';
                echo '<div class="annonce">';
                echo "<h3>$nomJob</h3>";
                echo "<p>Nombre de personnes recherchées : $nombrePersonnesRecherche</p>";
                echo "<p>Description du poste: $descriptionPoste</p>";
                echo "<p>Description de la recherche de personnes: $descriptionRecherchePersonne</p>";
                echo "<p>Lieu de l'entreprise: $lieuEntreprise</p>";
                echo "<p>Numéro de téléphone de l'entreprise: 0$numeroDeTelephone</p>";
                echo "<p>Adresse e-mail de l'entreprise: $emailEntreprise</p>";
                echo '<form method="post" action="">';
                echo "<input type='hidden' name='idAnnonce' value='$idAnnonce'>";
                echo '<input type="submit" name="supprimer_annonce" value="Supprimer cette annonce">';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }

            $stmtAnnonces->close();
        ?>
    </main>
</body>
</html>
