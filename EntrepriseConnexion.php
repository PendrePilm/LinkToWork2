<?php
include 'bd.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailEntreprise = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $motDePasse = $_POST["motDePasse"];

    $checkEmail = "SELECT idEntreprise, motDePasse FROM Entreprise WHERE emailEntreprise = ?";
    $checkStmt = $conn->prepare($checkEmail);
    
    if ($checkStmt) {
        $checkStmt->bind_param("s", $emailEntreprise);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $checkStmt->bind_result($idEntreprise, $hashedPassword);
            $checkStmt->fetch();

            if (password_verify($motDePasse, $hashedPassword)) {
                $_SESSION['email_entreprise'] = $emailEntreprise;

                header("Location: EntrepriseDashboard.php");
                exit;
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Aucun compte trouvé avec cet email.";
        }

        $checkStmt->close();
    } else {
        echo "Erreur lors de la préparation de la requête : " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Entreprise</title>
    <link rel="stylesheet" href="accueil.css">
</head>
<body>

    <h1>Connexion Entreprise</h1>

    <div class="container">

        <form method="post" action="EntrepriseConnexion.php">

            <label for="email">Email :</label>
            <input type="email" name="email" required>

            <label for="motDePasse">Mot de passe :</label>
            <input type="password" name="motDePasse" required>

            <button type="submit">Se connecter</button>

        </form>

    </div>

</body>
</html>
