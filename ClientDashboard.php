<?php
include 'bd.php';
session_start();

if (!isset($_SESSION['email_client'])) {
    header("Location: Accueil.php");
    exit;
}

$emailClientConnecte = $_SESSION['email_client'];

$sqlIdClient = "SELECT idClient, nom FROM Client WHERE email = ?";
$stmtIdClient = $conn->prepare($sqlIdClient);

if ($stmtIdClient) {
    $stmtIdClient->bind_param("s", $emailClientConnecte);
    $stmtIdClient->execute();
    $stmtIdClient->bind_result($idClient, $nomClient);
    $stmtIdClient->fetch();
    $stmtIdClient->close();
} else {
    echo "Erreur lors de la préparation de la requête pour récupérer l'ID du client : " . $conn->error;
    exit;
}

$sqlAnnonces = "SELECT a.idAnnonce, a.nomJob, a.nombrePersonnesRecherche, a.descriptionPoste, a.descriptionRecherchePersonne, e.nomEntreprise, e.lieuEntreprise, e.numeroDeTelephone, e.emailEntreprise
                FROM Annonce a
                JOIN Entreprise e ON a.idEntreprise = e.idEntreprise";
$stmtAnnonces = $conn->prepare($sqlAnnonces);

if ($stmtAnnonces) {
    $stmtAnnonces->execute();
    $stmtAnnonces->bind_result($idAnnonce, $nomJob, $nombrePersonnesRecherche, $descriptionPoste, $descriptionRecherchePersonne, $nomEntreprise, $lieuEntreprise, $numeroDeTelephone, $emailEntreprise);
    $stmtAnnonces->store_result();
} else {
    echo "Erreur lors de la préparation de la requête pour récupérer les annonces : " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client</title>
    <link rel="stylesheet" href="cssEntrepriseDashboard.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="ClientDashboard.php">Accueil</a></li>
                <li><a href="ClientRechercher.php">Recherche</a></li>
                <li class="name"><?= isset($nomClient) ? $nomClient : "Nom"; ?></li>
                <li><a href="ClientProfil.php">Profil</a></li>
                <li><a href="Deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Annonces</h2>

        <?php
        while ($stmtAnnonces->fetch()) {
            echo '<div class="annonce-container">';
            echo '<div class="annonce">';
            echo "<h3>Nom de l'entreprise: $nomEntreprise</h3>";
            echo "<p>Ville: $lieuEntreprise</p>";
            echo "<p>Numéro de téléphone: $numeroDeTelephone</p>";
            echo "<p>Email: $emailEntreprise</p>";
            echo "<p>Nom du job: $nomJob</p>";
            echo "<p>Nombre de personnes recherchées: $nombrePersonnesRecherche personnes recherchées</p>";
            echo "<p>Description du poste: $descriptionPoste</p>";
            echo "<p>Description de la recherche de personnes: $descriptionRecherchePersonne</p>";
            echo "</div>";
            echo "</div>";
        }

        $stmtAnnonces->close();
        ?>
    </main>
</body>

</html>
