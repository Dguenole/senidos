<?php
session_start();

// Vérification si l'utilisateur est un enseignant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'enseignant') {
    header("Location: ../../index.php");
    exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Récupération des informations de l'enseignant connecté
$enseignant_id = $_SESSION['id'];
$query = $conn->prepare("SELECT nom, prenom, email FROM Utilisateurs WHERE id = ?");
$query->bind_param("i", $enseignant_id);
$query->execute();
$result = $query->get_result();
$enseignant = $result->fetch_assoc();




?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Enseignant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
</head>
    <!-- Style personnalisé -->
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px 15px;
        }

        .sidebar .nav-link {
            color: #fff;
            margin: 10px 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }
        footer {
            background-color: #111827;
            color: #f9fafb;
            text-align: center;
            padding: 10px 0;
        }
        .btn btn-secondary mt-3
        

        .card {
            border: none;
            border-radius: 10px;
            color: #fff;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card .icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .card.bg-primary {
            background-color: #007bff;
        }

        .card.bg-success {
            background-color: #28a745;
        }

        .card.bg-danger {
            background-color: #dc3545;
        }

        .card.bg-warning {
            background-color: #ffc107;
        }

        .card.bg-info {
            background-color: #17a2b8;
        }
    </style>
<body>
<div class="sidebar">
        <h4>Gestion Scolaire</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
            <a class="nav-link" href="ajout_note.php"><i class="fas fa-book"></i> Ajouter une Note</a>
            <a class="nav-link" href="gerer_notes.php"><i class="fa-regular fa-file"></i> Modifier/Supprimer une Note</a>
            <a class="nav-link" href="emploi_du_temps.php"><i class="fas fa-calendar-alt"></i> Emploi du Temps</a>
            <a class="nav-link" href="modifier_mdp.php"><i class="fas fa-key"></i> modifier MDP</a>
            <a class="nav-link" href="/GestionScolaireGroupe/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </div>
    <div class="content">
        <div class="dashboard-header">
            <h2>Bienvenue, <?php echo $enseignant['prenom'] . ' ' . $enseignant['nom']; ?></h2>
            <p>Email : <?php echo $enseignant['email']; ?></p>
        </div>
        
        <div class="row">
            <!-- Ajouter une note -->
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title">Ajouter une Note</h5>
                        <p class="card-text">Ajoutez des notes pour vos étudiants.</p>
                        <a href="ajout_note.php" class="btn btn-light">Voir Plus</a>
                    </div>
                </div>
            </div>
            <!-- Modifier/Supprimer une note -->
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body text-center">
                        <h5 class="card-title">Modifier/Supprimer une Note</h5>
                        <p class="card-text">Gérez les notes attribuées.</p>
                        <a href="gerer_notes.php" class="btn btn-light">Voir Plus</a>
                    </div>
                </div>
            </div>
            <!-- Consulter l'emploi du temps -->
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body text-center">
                        <h5 class="card-title">Consulter l'Emploi du Temps</h5>
                        <p class="card-text">Consultez votre emploi du temps.</p>
                        <a href="emploi_du_temps.php" class="btn btn-light">Voir Plus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>