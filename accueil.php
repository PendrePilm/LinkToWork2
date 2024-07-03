<?php
    include 'bd.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="icon" href="images/Logo_Rond.png" type="image/x-icon">
    <link rel="stylesheet" href="accueil.css">
</head>

<body>

    <h1>Bienvenue sur LinkToWork</h1>

    <div class="container">

        <p>Quel utilisateur êtes-vous ?</p>

        <button onclick="redirectToEntreprise()">Une entreprise</button>
        <button onclick="redirectToClient()">En recherche d'emploi</button>

    </div>

    <script>
        function redirectToEntreprise() {
            window.location.href = "EntrepriseCI.php";
        }

        function redirectToClient() {
            window.location.href = "ClientCI.php";
        }
    </script>

</body>
</html>

