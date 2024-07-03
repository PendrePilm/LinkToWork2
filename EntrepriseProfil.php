<?php
include 'bd.php';

session_start();

if (!isset($_SESSION['email_entreprise'])) {
    header("Location: Accueil.php");
    exit;
}

$emailEntrepriseConnectee = $_SESSION['email_entreprise'];

$sqlInfoEntreprise = "SELECT * FROM Entreprise WHERE emailEntreprise = ?";
$stmtInfoEntreprise = $conn->prepare($sqlInfoEntreprise);

if ($stmtInfoEntreprise) {
    $stmtInfoEntreprise->bind_param("s", $emailEntrepriseConnectee);
    $stmtInfoEntreprise->execute();
    $resultInfoEntreprise = $stmtInfoEntreprise->get_result();

    if ($resultInfoEntreprise->num_rows > 0) {
        $infoEntreprise = $resultInfoEntreprise->fetch_assoc();
    } else {
        echo "Aucune information d'entreprise trouvée.";
        exit;
    }

    $stmtInfoEntreprise->close();
} else {
    echo "Erreur lors de la préparation de la requête pour récupérer les informations de l'entreprise : " . $conn->error;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changer_mdp'])) {
    $ancienMotDePasse = $_POST['ancien_mot_de_passe'];
    $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'];

    if (password_verify($ancienMotDePasse, $infoEntreprise['motDePasse'])) {
        $nouveauMotDePasseHash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);

        $sqlUpdateMotDePasse = "UPDATE Entreprise SET motDePasse = ? WHERE emailEntreprise = ?";
        $stmtUpdateMotDePasse = $conn->prepare($sqlUpdateMotDePasse);

        if ($stmtUpdateMotDePasse) {
            $stmtUpdateMotDePasse->bind_param("ss", $nouveauMotDePasseHash, $emailEntrepriseConnectee);
            $stmtUpdateMotDePasse->execute();
            $stmtUpdateMotDePasse->close();

            echo '<span style="color: red;">Mot de passe mis à jour avec succès.</span>';
        } else {
            echo '<span style="color: red;">Erreur lors de la préparation de la requête pour la mise à jour du mot de passe : ' . $conn->error . '</span>';
        }
    } else {
        echo '<span style="color: red;">Ancien mot de passe incorrect.</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'entreprise</title>
    <link rel="stylesheet" href="cssEntrepriseProfil.css">
</head>

<body>

<header>
        <nav>
            <ul>
                <li><a href="EntrepriseDashboard.php">Accueil</a></li>
                <li><a href="NouvelleAnnonce.php">Annonces</a></li>
                <li class="name"><?= isset($infoEntreprise['nomEntreprise']) ? $infoEntreprise['nomEntreprise'] : "Nom de l'entreprise"; ?></li>
                <li><a href="EntrepriseProfil.php">Profil</a></li>
                <li><a href="Deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main class="profile-container">
        <h2>Profil de l'entreprise</h2>
        <div class="profile-info">
            <p><strong>Nom de l'entreprise:</strong> <?= isset($infoEntreprise['nomEntreprise']) ? $infoEntreprise['nomEntreprise'] : ""; ?></p>
            <p><strong>Email de l'entreprise:</strong> <?= isset($infoEntreprise['emailEntreprise']) ? $infoEntreprise['emailEntreprise'] : ""; ?></p>
            <p><strong>Numèro de télephone de l'entreprise: </strong> 0<?= isset($infoEntreprise['numeroDeTelephone']) ? $infoEntreprise['numeroDeTelephone'] : ""; ?></p>
        </div>

        <div class="password-change-form">
            <h3>Changer le mot de passe</h3>
            <form method="post" action="">
                <label for="ancien_mot_de_passe">Ancien mot de passe:</label>
                <input type="password" name="ancien_mot_de_passe" required>

                <label for="nouveau_mot_de_passe">Nouveau mot de passe:</label>
                <input type="password" name="nouveau_mot_de_passe" required>

                <input type="submit" name="changer_mdp" value="Changer le mot de passe">
            </form>
        </div>
    </main>

</body>

</html>
