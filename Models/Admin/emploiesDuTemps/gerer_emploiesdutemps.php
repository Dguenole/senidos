<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['role'])) {
header("Location: ../../index.php");  
  exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';


// Récupération des emplois du temps
$stmt = $conn->prepare("
    SELECT 
        e.id, 
        e.classe, 
        m.nom AS matiere,
        u.nom AS enseignant,  
        u.prenom AS tt,
        e.jour, 
        e.heure_debut, 
        e.heure_fin
    FROM EmploisDuTemps e
    JOIN Matieres m ON e.matiere_id = m.id
    JOIN Utilisateurs u ON m.enseignant_id = u.id
");
$stmt->execute();
$result = $stmt->get_result();
$schedules = $result->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation des Emplois du Temps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/GestionScolaireGroupe/Public/css/style.css">

</head>

      <!-- Sidebar -->
      <div class="sidebar">
        <h4>SENEIDOS</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/matieres/gerer_matieres.php"><i class="fas fa-book"></i> Matières</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/emploiesDuTemps/gerer_emploiesdutemps.php"><i class="fas fa-calendar-alt"></i> Emplois du temps</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/utilisateurs/gerer_utilisateurs.php"><i class="fas fa-users"></i> Utilisateurs</a>
            <a class="nav-link" href="/GestionScolaireGroupe/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </div>
<body>
    <div class="content">
        <h2>Consultation des Emplois du Temps</h2>

        <!-- Boutons d'actions -->
        <div class="action-buttons">
            <a href="creer_emploiesdutemps.php" class="btn btn-success">Créer un emploi du temps</a>
            <a href="generate_pdf.php" class="btn btn-primary">Génération de PDF</a>
        </div>

        <!-- Affichage des emplois du temps -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Classe</th>
                    <th>Enseignant</th>
                    <th>Jour</th>
                    <th>Heure de début</th>
                    <th>Heure de fin</th>
                    <th>Matiere</th>
                    <th>Editage</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($schedules)): ?>
                    <?php foreach ($schedules as $schedule): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($schedule['id']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['classe']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['enseignant'] ." " .$schedule['tt']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['jour']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['heure_debut']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['heure_fin']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['matiere']); ?></td>
                            <td>
                            <a href="mod_emploiesdutemps.php?id=<?php echo $schedule['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="../../controllers/AdminController.php?action=deleteEmplois&id=<?php echo $schedule['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucun emploi du temps trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <footer class="text-center mt-5 py-3 bg-light">
        <p>&copy; 2024 Gestion Scolaire. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>