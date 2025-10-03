<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../../index.php");
    exit();
}
require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Récupérer l'emploi du temps de l'étudiant connecté
$etudiant_id = $_SESSION['id'];
$query = $conn->prepare("
    SELECT jour, heure_debut, heure_fin, m.nom AS matiere, salle 
    FROM EmploisDuTemps e
    JOIN Matieres m ON e.matiere_id = m.id
    WHERE e.classe = (SELECT classe FROM Utilisateurs WHERE id = ?)
");
$query->bind_param("i", $etudiant_id);
$query->execute();
$result = $query->get_result();
$emploi_du_temps = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/GestionScolaireGroupe/Public/css/style.css"></head>
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
        <h1>Mon Emploi du Temps</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Jour</th>
                    <th>Heure Début</th>
                    <th>Heure Fin</th>
                    <th>Matière</th>
                    <th>Salle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emploi_du_temps as $cours): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cours['jour']); ?></td>
                        <td><?php echo htmlspecialchars($cours['heure_debut']); ?></td>
                        <td><?php echo htmlspecialchars($cours['heure_fin']); ?></td>
                        <td><?php echo htmlspecialchars($cours['matiere']); ?></td>
                        <td><?php echo htmlspecialchars($cours['salle']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>