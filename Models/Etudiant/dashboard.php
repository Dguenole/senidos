<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Vérifier si l'utilisateur est connecté et est un étudiant
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../../index.php");
    exit();
}

// Récupérer les informations de l'étudiant connecté
$etudiant_id = $_SESSION['id'];
$query = $conn->prepare("SELECT nom, prenom FROM Utilisateurs WHERE id = ?");
$query->bind_param("i", $etudiant_id);
$query->execute();
$result = $query->get_result();
$etudiant = $result->fetch_assoc(); // Tableau associatif contenant les infos de l'étudiant

// Récupérer le nombre de matières
$queryMatieres = $conn->prepare("SELECT COUNT(*) AS total_matieres FROM Matieres");
$queryMatieres->execute();
$resultMatieres = $queryMatieres->get_result();
$totalMatieres = $resultMatieres->fetch_assoc()['total_matieres'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/GestionScolaireGroupe/Public/css/style.css">
    <style>
        .card { margin: 15px; }
    </style>
</head>
<body>
<div class="sidebar">
        <h4>Gestion Scolaire</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
            <a class="nav-link" href="consulter_notes.php"><i class="fas fa-book"></i> Mes Notes</a>
            <a class="nav-link" href="emploi_du_temps.php"><i class="fa-regular fa-file"></i>Emplois du Temps</a>
            <a class="nav-link" href="modifier_mdp.php"><i class="fas fa-key"></i> modifier MDP</a>
            <a class="nav-link" href="/GestionScolaireGroupe/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </div>
    <div class="content">
        <h1>Bienvenue, <?php echo htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?> !</h1>
        
        <div class="row mt-4">
            <!-- Notes -->
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Mes Notes</h5>
                        <p class="card-text">Consultez vos résultats académiques.</p>
                        <a href="consulter_notes.php" class="btn btn-light">Voir Plus</a>
                    </div>
                </div>
            </div>

            <!-- Emploi du temps -->
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Emploi du Temps</h5>
                        <p class="card-text">Consultez vos horaires de cours.</p>
                        <a href="emploi_du_temps.php" class="btn btn-light">Voir Plus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>