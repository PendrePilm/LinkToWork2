<?php
include 'bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = filter_var($_POST["nom"], FILTER_SANITIZE_STRING);
    $prenom = filter_var($_POST["prenom"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $motDePasse = password_hash($_POST["motDePasse"], PASSWORD_DEFAULT);

    $checkEmail = "SELECT idClient FROM Client WHERE email = ?";
    $checkStmt = $conn->prepare($checkEmail);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "L'email est déjà utilisé. Veuillez choisir un autre email.";
    } else {
        $sql = "INSERT INTO Client (nom, prenom, email, motDePasse) VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nom, $prenom, $email, $motDePasse);

        if ($stmt->execute()) {
            echo "Compte client créé avec succès.";
            header("Location: ClientCI.php");
        } else {
            echo "Erreur lors de la création du compte client : " . $stmt->error;
        }

        $stmt->close();
    }

    $checkStmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Client</title>
    <link rel="stylesheet" href="accueil.css">
    <style>
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 5px;
            align-items: center;
            text-align: left;
        }

        label {
            margin-bottom: 5px; 
        }

        input {
            margin-bottom: 10px;
        }

        button {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <h1>Inscription Client</h1>

    <div class="container">

        <form method="post" action="ClientInscription.php">

            <label for="nom">Nom :</label>
            <input type="text" name="nom" required>

            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" required>

            <label for="email">Email :</label>
            <input type="email" name="email" required>

            <label for="motDePasse">Mot de passe :</label>
            <input type="password" name="motDePasse" required>

            <button type="submit">S'inscrire</button>

        </form>

    </div>

</body>
</html>
