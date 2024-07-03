<?php
include 'bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomEntreprise = filter_var($_POST["nomEntreprise"], FILTER_SANITIZE_STRING);
    $lieuEntreprise = filter_var($_POST["lieuEntreprise"], FILTER_SANITIZE_STRING);
    $emailEntreprise = filter_var($_POST["emailEntreprise"], FILTER_SANITIZE_EMAIL);
    $motDePasse = password_hash($_POST["motDePasse"], PASSWORD_DEFAULT);
    $numeroDeTelephone = filter_var($_POST["numeroDeTelephone"], FILTER_SANITIZE_NUMBER_INT);

    $checkEmailSql = "SELECT idEntreprise FROM Entreprise WHERE emailEntreprise = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);
    
    if ($checkEmailStmt) {
        $checkEmailStmt->bind_param("s", $emailEntreprise);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            echo "L'email est déjà utilisé. Veuillez choisir un autre.";
        } else {
            $checkSql = "SELECT idEntreprise FROM Entreprise WHERE nomEntreprise = ? AND lieuEntreprise = ?";
            $checkStmt = $conn->prepare($checkSql);
            
            if ($checkStmt) {
                $checkStmt->bind_param("ss", $nomEntreprise, $lieuEntreprise);
                $checkStmt->execute();
                $checkStmt->store_result();

                if ($checkStmt->num_rows > 0) {
                    echo "Ce nom d'entreprise associé à cette ville est déjà utilisé.";
                } else {
                    $insertSql = "INSERT INTO Entreprise (nomEntreprise, lieuEntreprise, emailEntreprise, motDePasse, numeroDeTelephone) VALUES (?, ?, ?, ?, ?)";
                    $insertStmt = $conn->prepare($insertSql);
                    
                    if ($insertStmt) {
                        $insertStmt->bind_param("sssss", $nomEntreprise, $lieuEntreprise, $emailEntreprise, $motDePasse, $numeroDeTelephone);
                        
                        if ($insertStmt->execute()) {
                            header("Location: EntrepriseCI.php");
                            exit;
                        } else {
                            echo "Erreur lors de la création du compte entreprise : " . $insertStmt->error;
                        }

                        $insertStmt->close();
                    } else {
                        echo "Erreur lors de la préparation de la requête d'insertion : " . $conn->error;
                    }
                }

                $checkStmt->close();
            } else {
                echo "Erreur lors de la préparation de la requête de vérification : " . $conn->error;
            }
        }

        $checkEmailStmt->close();
    } else {
        echo "Erreur lors de la préparation de la requête de vérification : " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Entreprise</title>
    <link rel="stylesheet" href="accueil.css">
  
</head>

<body>

    <h1>Inscription Entreprise</h1>

    <div class="container">

        <form method="post" action="EntrepriseInscription.php">

            <label for="nomEntreprise">Nom de l'entreprise :</label>
            <input type="text" name="nomEntreprise" required>

            <label for="lieuEntreprise">Lieu de l'entreprise :</label>
            <input type="text" name="lieuEntreprise" required>

            <label for="emailEntreprise">Email :</label>
            <input type="email" name="emailEntreprise" required>

            <label for="motDePasse">Mot de passe :</label>
            <input type="password" name="motDePasse" required>

            <label for="numeroDeTelephone">Numéro de téléphone :</label>
            <input type="tel" name="numeroDeTelephone" required>

            <button type="submit">S'inscrire</button>

        </form>

    </div>

</body>
</html>
