<?php
    include 'bd.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion/Inscription - LinkToWork</title>
    <link rel="icon" href="Logo_Rond.png" type="image/x-icon">
    <link rel="stylesheet" href="accueil.css">
</head>

<body>

    <h1>Bienvenue sur LinkToWork</h1>

    <div class="container">

        <p>Connectez-vous ou inscrivez-vous</p>

        <button onclick="redirectToConnectionClient()">Connexion</button>
        <button onclick="redirectToInscriptionClient()">Inscription</button>

    </div>

    <script>
        function redirectToConnectionClient() {
            window.location.href = "EntrepriseConnexion.php";
        }

        function redirectToInscriptionClient() {
            window.location.href = "EntrepriseInscription.php";
        }
    </script>

</body>
</html>
