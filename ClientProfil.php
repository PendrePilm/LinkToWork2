<?php
include 'bd.php';

session_start();

if (!isset($_SESSION['email_client'])) {
    header("Location: Accueil.php");
    exit;
}

$emailClientConnecte = $_SESSION['email_client'];

$sqlInfoClient = "SELECT * FROM Client WHERE email = ?";
$stmtInfoClient = $conn->prepare($sqlInfoClient);

if ($stmtInfoClient) {
    $stmtInfoClient->bind_param("s", $emailClientConnecte);
    $stmtInfoClient->execute();
    $resultInfoClient = $stmtInfoClient->get_result();

    if ($resultInfoClient->num_rows > 0) {
        $infoClient = $resultInfoClient->fetch_assoc();
    } else {
        echo "Aucune information de client trouvée.";
        exit;
    }

    $stmtInfoClient->close();
} else {
    echo "Erreur lors de la préparation de la requête pour récupérer les informations du client : " . $conn->error;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changer_mdp'])) {
    $ancienMotDePasse = $_POST['ancien_mot_de_passe'];
    $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'];

    if (password_verify($ancienMotDePasse, $infoClient['motDePasse'])) {
        $nouveauMotDePasseHash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);

        $sqlUpdateMotDePasse = "UPDATE Client SET motDePasse = ? WHERE email = ?";
        $stmtUpdateMotDePasse = $conn->prepare($sqlUpdateMotDePasse);

        if ($stmtUpdateMotDePasse) {
            $stmtUpdateMotDePasse->bind_param("ss", $nouveauMotDePasseHash, $emailClientConnecte);
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
    <title>Profil du client</title>
    <link rel="stylesheet" href="cssEntrepriseProfil.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="ClientDashboard.php">Accueil</a></li>
                <li><a href="ClientRechercher.php">Recherche</a></li>
                <li class="name"><?= isset($infoClient['nom']) ? $infoClient['nom'] : "nom"; ?></li>
                <li><a href="ClientProfil.php">Profil</a></li>
                <li><a href="Deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main class="profile-container">
        <h2>Profil du client</h2>
        <div class="profile-info">
            <p><strong>Nom:</strong> <?= isset($infoClient['nom']) ? $infoClient['nom'] : ""; ?></p>
            <p><strong>Prénom:</strong> <?= isset($infoClient['prenom']) ? $infoClient['prenom'] : ""; ?></p>
            <p><strong>Email:</strong> <?= isset($infoClient['email']) ? $infoClient['email'] : ""; ?></p>
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
