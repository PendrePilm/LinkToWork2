<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion à la base de données</title>
</head>
<body>

<?php
$user = 'root';
$password = '';
$database = 'projet';
$port = NULL;

$conn = new mysqli('127.0.0.1', $user, $password, $database, $port);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

echo "";


?>

</body>
</html>

<!-- -- Création de la table CLIENT
CREATE TABLE CLIENT (
    idClient INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    email VARCHAR(200) UNIQUE,
    motDePasse VARCHAR(200)
);

-- Création de la table Entreprise
CREATE TABLE Entreprise (
    idEntreprise INT PRIMARY KEY AUTO_INCREMENT,
    nomEntreprise VARCHAR(100),
    lieuEntreprise VARCHAR(40),
    emailEntreprise VARCHAR(200),		
    motDePasse VARCHAR(200),
    numeroDeTelephone INT(10),
    UNIQUE (emailEntreprise)
);

-- Création de la table Annonce
CREATE TABLE Annonce (
    idAnnonce INT PRIMARY KEY AUTO_INCREMENT,
    idEntreprise INT,
    nomJob VARCHAR(100),
    nombrePersonnesRecherche INT,
    descriptionPoste TEXT,
    descriptionRecherchePersonne TEXT,
    FOREIGN KEY (idEntreprise) REFERENCES Entreprise(idEntreprise)
); -->