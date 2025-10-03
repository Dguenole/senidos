<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Vérification si l'utilisateur est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: ../../index.php");
    exit;
}

// Récupération des informations de l'enseignant connecté
$admin_id = $_SESSION['id'];
$query = $conn->prepare("SELECT nom, prenom, email FROM Utilisateurs WHERE id = ?");
$query->bind_param("i", $admin_id);
$query->execute();
$result = $query->get_result();
$admin = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <title>Gestion Scolaire - Tableau de bord</title>

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
</head>

<body>
      <!-- Sidebar -->
      <div class="sidebar">
        <h4>SENEIDOS</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/matieres/gerer_matieres.php"><i class="fas fa-book"></i> Matières</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/emploiesDuTemps/gerer_emploiesdutemps.php"><i class="fas fa-calendar-alt"></i> Emplois du temps</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/utilisateurs/gerer_utilisateurs.php"><i class="fas fa-users"></i> Utilisateurs</a>
            <a class="nav-link" href="/GestionScolaireGroupe/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <h2 class="text-center mb-4">Bienvenue, <?php echo $admin['prenom'] . ' ' . $admin['nom']; ?></h2>

        <div class="row g-4">
            <!-- Gestion des matières -->
            <div class="col-md-4">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="icon"><i class="fas fa-book"></i></div>
                        <h5 class="card-title">Gestion des matières</h5>
                        <p class="card-text">Ajoutez, modifiez et supprimez des matières.</p>
                        <a href="/GestionScolaireGroupe/Models/Admin/matieres/gerer_matieres.php" class="btn btn-light">Gérer</a>
                    </div>
                </div>
            </div>

            <!-- Gestion des emplois du temps -->
            <div class="col-md-4">
                <div class="card bg-success">
                    <div class="card-body">
                        <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                        <h5 class="card-title">Gestion des emplois du temps</h5>
                        <p class="card-text">Organisez les horaires des enseignants et étudiants.</p>
                        <a href="/GestionScolaireGroupe/Models/Admin/emploiesDuTemps/gerer_emploiesdutemps.php" class="btn btn-light">Gérer</a>
                    </div>
                </div>
            </div>

            <!-- Gestion des utilisateurs -->
            <div class="col-md-4">
                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <h5 class="card-title">Gestion des utilisateurs</h5>
                        <p class="card-text">Ajoutez et gérez les utilisateurs du système.</p>
                        <a href="/GestionScolaireGroupe/Models/Admin/utilisateurs/gerer_utilisateurs.php" class="btn btn-light">Gérer</a>
                    </div>
                </div>
            </div>
    <!-- Intégration des scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
