<?php
include 'bd.php';

session_start();

if (!isset($_SESSION['email_entreprise'])) {
    header("Location: Accueil.php");
    exit;
}

$emailEntrepriseConnectee = $_SESSION['email_entreprise'];

$sqlInfoEntreprise = "SELECT nomEntreprise FROM Entreprise WHERE emailEntreprise = ?";
$stmtInfoEntreprise = $conn->prepare($sqlInfoEntreprise);

if ($stmtInfoEntreprise) {
    $stmtInfoEntreprise->bind_param("s", $emailEntrepriseConnectee);
    $stmtInfoEntreprise->execute();
    $stmtInfoEntreprise->bind_result($nomEntreprise);
    $stmtInfoEntreprise->fetch();
    $stmtInfoEntreprise->close();
} else {
    echo "Erreur lors de la préparation de la requête pour récupérer le nom de l'entreprise : " . $conn->error;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['creer_annonce'])) {
    $nomJob = htmlspecialchars($_POST['nomJob']);
    $nombrePersonnesRecherche = intval($_POST['nombrePersonnesRecherche']);
    $descriptionPoste = htmlspecialchars($_POST['descriptionPoste']);
    $descriptionRecherchePersonne = htmlspecialchars($_POST['descriptionRecherchePersonne']);

    if (empty($nomJob) || $nombrePersonnesRecherche <= 0 || empty($descriptionPoste) || empty($descriptionRecherchePersonne)) {
        echo "Veuillez remplir tous les champs du formulaire.";
        exit;
    }

    $sqlInsertAnnonce = "INSERT INTO Annonce (idEntreprise, nomJob, nombrePersonnesRecherche, descriptionPoste, descriptionRecherchePersonne) 
                        VALUES (?, ?, ?, ?, ?)";
    $stmtInsertAnnonce = $conn->prepare($sqlInsertAnnonce);

    if ($stmtInsertAnnonce) {
        $sqlIdEntreprise = "SELECT idEntreprise FROM Entreprise WHERE emailEntreprise = ?";
        $stmtIdEntreprise = $conn->prepare($sqlIdEntreprise);

        if ($stmtIdEntreprise) {
            $stmtIdEntreprise->bind_param("s", $emailEntrepriseConnectee);
            $stmtIdEntreprise->execute();
            $stmtIdEntreprise->bind_result($idEntreprise);
            $stmtIdEntreprise->fetch();
            $stmtIdEntreprise->close();

            $stmtInsertAnnonce->bind_param("issss", $idEntreprise, $nomJob, $nombrePersonnesRecherche, $descriptionPoste, $descriptionRecherchePersonne);
            $stmtInsertAnnonce->execute();
            $stmtInsertAnnonce->close();

            echo "Annonce créée avec succès.";
        } else {
            echo "Erreur lors de la préparation de la requête pour récupérer l'id de l'entreprise : " . $conn->error;
        }
    } else {
        echo "Erreur lors de la préparation de la requête pour créer une annonce : " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Annonce</title>
    <link rel="stylesheet" href="cssEntrepriseProfil.css">
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

<main class="profile-container"> 
    <h2>Créer une nouvelle annonce</h2>
    <form method="post" action="">
        <label for="nomJob">Nom du poste :</label>
        <input type="text" name="nomJob" required>

        <label for="nombrePersonnesRecherche">Nombre de personnes recherchées :</label>
        <input type="number" name="nombrePersonnesRecherche" required>

        <label for="descriptionPoste">Description du poste :</label>
        <textarea name="descriptionPoste" required></textarea>

        <label for="descriptionRecherchePersonne">Description de la personne recherchée :</label>
        <textarea name="descriptionRecherchePersonne" required></textarea>

        <input type="submit" name="creer_annonce" value="Créer l'annonce">
    </form>
</main>

</body>

</html>
