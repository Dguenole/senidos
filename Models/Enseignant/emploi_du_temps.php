<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'enseignant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'enseignant') {
    header("Location: ../../index.php");
    exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Récupérer l'emploi du temps pour l'enseignant connecté
$query = $conn->prepare("
    SELECT E.jour, E.heure_debut, E.heure_fin, M.nom AS matiere, E.salle, E.classe
    FROM EmploisDuTemps E
    INNER JOIN Matieres M ON E.matiere_id = M.id
    WHERE E.enseignant_id = ?
    ORDER BY FIELD(E.jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'), E.heure_debut
");
$query->bind_param("i", $_SESSION['id']);
$query->execute();
$result = $query->get_result();
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
            <a class="nav-link" href="ajout_note.php"><i class="fas fa-book"></i> Ajouter une Note</a>
            <a class="nav-link" href="gerer_notes.php"><i class="fa-regular fa-file"></i> Modifier/Supprimer une Note</a>
            <a class="nav-link" href="emploi_du_temps.php"><i class="fas fa-calendar-alt"></i> Emploi du Temps</a>
            <a class="nav-link" href="modifier_mdp.php"><i class="fas fa-key"></i> modifier MDP</a>
            <a class="nav-link" href="/GestionScolaireGroupe/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </div>
    <div class="content">
        <h2>Mon Emploi du Temps</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Jour</th>
                    <th>Heure Début</th>
                    <th>Heure Fin</th>
                    <th>Matière</th>
                    <th>Salle</th>
                    <th>Classe</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['jour']; ?></td>
                            <td><?php echo date("H:i", strtotime($row['heure_debut'])); ?></td>
                            <td><?php echo date("H:i", strtotime($row['heure_fin'])); ?></td>
                            <td><?php echo $row['matiere']; ?></td>
                            <td><?php echo $row['salle']; ?></td>
                            <td><?php echo $row['classe']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucun emploi du temps disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>