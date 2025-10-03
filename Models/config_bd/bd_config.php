<?php

// Définir les paramètres de connexion
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'GestionProjetGroupe';

// Connexion à la base de données
$conn = new mysqli($host, $user, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
} else {
    echo " ";
}
?>