<?php
    include 'bd.php';
    session_start();

    if (!isset($_SESSION['email_client'])) {
        header("Location: Accueil.php");
        exit;
    }

    $emailClientConnecte = $_SESSION['email_client'];
    $sqlNomClient = "SELECT nom FROM Client WHERE email = ?";
    $stmtNomClient = $conn->prepare($sqlNomClient);
    
    if ($stmtNomClient) {
        $stmtNomClient->bind_param("s", $emailClientConnecte);
        $stmtNomClient->execute();
        $stmtNomClient->bind_result($nomClient);
        $stmtNomClient->fetch();
        $stmtNomClient->close();
    } else {
        echo "Erreur lors de la récupération du nom du client : " . $conn->error;
        exit;
    }

    $annonces = array();

    if (isset($_POST['nom_job'])) {
        $nom_job = mysqli_real_escape_string($conn, $_POST['nom_job']);

        $sqlAnnonces = "SELECT a.nomJob, a.nombrePersonnesRecherche, a.descriptionPoste, a.descriptionRecherchePersonne, e.nomEntreprise, e.lieuEntreprise, e.numeroDeTelephone, e.emailEntreprise
                        FROM Annonce a
                        JOIN Entreprise e ON a.idEntreprise = e.idEntreprise
                        WHERE a.nomJob LIKE '%$nom_job%'";

        $resultAnnonces = mysqli_query($conn, $sqlAnnonces);

        if ($resultAnnonces) {
            while ($row = mysqli_fetch_assoc($resultAnnonces)) {
                $annonces[] = $row;
            }
        } else {
            echo "Erreur lors de la récupération des annonces : " . mysqli_error($conn);
            exit;
        }
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

        <form method="POST" action="">
            <div class="annonce-container">
                <div class="annonce">
                    <label for="nom_job" style="color: black;">Rechercher par nom de job :</label>
                    <input type="text" id="nom_job" name="nom_job">
                    <button type="submit">Rechercher</button>
                </div>
            </div>
        </form>

        <?php
        if (!empty($annonces)) {
            foreach ($annonces as $annonce) {
                echo '<div class="annonce-container">';
                echo '<div class="annonce">';
                echo "<h3>Nom de l'entreprise: {$annonce['nomEntreprise']}</h3>";
                echo "<p>Ville: {$annonce['lieuEntreprise']}</p>";
                echo "<p>Numéro de téléphone: {$annonce['numeroDeTelephone']}</p>";
                echo "<p>Email: {$annonce['emailEntreprise']}</p>";
                echo "<p>Nom du job: {$annonce['nomJob']}</p>";
                echo "<p>Nombre de personnes recherchées: {$annonce['nombrePersonnesRecherche']} personnes recherchées</p>";
                echo "<p>Description du poste: {$annonce['descriptionPoste']}</p>";
                echo "<p>Description de la recherche de personnes: {$annonce['descriptionRecherchePersonne']}</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<h2>Aucune annonce trouvée.</h2>";
        }
        ?>
    </main>
</body>
</html>
