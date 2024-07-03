<?php
include 'bd.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $motDePasse = $_POST["motDePasse"];

    $sql = "SELECT idClient, nom, prenom, email, motDePasse FROM Client WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($idClient, $nom, $prenom, $emailDB, $motDePasseDB);
        $stmt->fetch();

        if (password_verify($motDePasse, $motDePasseDB)) {
            $_SESSION['email_client'] = $email; 
            header("Location: ClientDashboard.php");
            exit; 
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client</title>
    <link rel="stylesheet" href="accueil.css">
</head>
<body>

    <h1>Connexion Client</h1>

    <div class="container">

        <form method="post" action="ClientConnexion.php">

            <label for="email">Email :</label>
            <input type="email" name="email" required>

            <label for="motDePasse">Mot de passe :</label>
            <input type="password" name="motDePasse" required>

            <button type="submit">Se connecter</button>

        </form>

    </div>

</body>
</html>
