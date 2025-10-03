<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: ../../index.php");    exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

$errorMessage = '';
$successMessage = '';
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($id) {
    // Récupération des informations de l'emploi du temps
    $stmt = $conn->prepare("
        SELECT 
            id, 
            heure_fin 
            classe, 
            matiere_id, 
            enseignant_id, 
            jour, 
            heure_debut
        FROM EmploisDuTemps 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $emploiDuTemps = $stmt->get_result()->fetch_assoc();

    if (!$emploiDuTemps) {
        $errorMessage = "Aucun emploi du temps trouvé avec cet ID.";
    }
} else {
    $errorMessage = "ID invalide.";
}

// Récupération des matières
$resultMatieres = $conn->query("SELECT id, nom FROM Matieres");
$matieres = $resultMatieres->fetch_all(MYSQLI_ASSOC);

// Récupération des enseignants
$resultEnseignants = $conn->query("SELECT id, nom, prenom FROM Utilisateurs WHERE role = 'enseignant'");
$enseignants = $resultEnseignants->fetch_all(MYSQLI_ASSOC);

// Gestion de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classe = htmlspecialchars($_POST['classe']);
    $matiere_id = htmlspecialchars($_POST['matiere']);
    $enseignant_id = htmlspecialchars($_POST['enseignant']);
    $jour = htmlspecialchars($_POST['jour']);
    $heure_debut = htmlspecialchars($_POST['heure_debut']);
    $heure_fin = htmlspecialchars($_POST['heure_fin']);

    // Vérification que les champs ne sont pas vides
    if ($classe && $matiere_id && $enseignant_id && $jour && $heure_debut && $heure_fin) {
        $stmt = $conn->prepare("
            UPDATE EmploisDuTemps 
            SET classe = ?, matiere_id = ?, enseignant_id = ?, jour = ?, heure_debut = ?, heure_fin = ? 
            WHERE id = ?
        ");
        $stmt->bind_param("siisssi", $classe, $matiere_id, $enseignant_id, $jour, $heure_debut, $heure_fin, $id);

        if ($stmt->execute()) {
            $successMessage = "Emploi du temps mis à jour avec succès.";
            // Recharger les nouvelles données
            header("Location: gerer_emploiesdutemps.php?id=$id");
            exit;
        } else {
            $errorMessage = "Une erreur est survenue lors de la mise à jour.";
        }
    } else {
        $errorMessage = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Emploi du Temps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/GestionScolaireGroupe/Public/css/style.css">
</head>

<body>
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
    <div class="content">
        <h2>Modifier un Emploi du Temps</h2>

        <!-- Affichage des messages de succès ou d'erreur -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php elseif ($errorMessage): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if ($emploiDuTemps): ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="classe" class="form-label">Classe :</label>
                    <input type="text" id="classe" name="classe" class="form-control" 
                        value="<?php echo htmlspecialchars($emploiDuTemps['classe']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="matiere" class="form-label">Matière :</label>
                    <select name="matiere" id="matiere" class="form-select" required>
                        <?php foreach ($matieres as $matiere): ?>
                            <option value="<?php echo $matiere['id']; ?>" 
                                <?php echo $emploiDuTemps['matiere_id'] == $matiere['id'] ? 'selected' : ''; ?>>
                                <?php echo $matiere['nom']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="enseignant" class="form-label">Enseignant :</label>
                    <select name="enseignant" id="enseignant" class="form-select" required>
                        <?php foreach ($enseignants as $enseignant): ?>
                            <option value="<?php echo $enseignant['id']; ?>" 
                                <?php echo $emploiDuTemps['enseignant_id'] == $enseignant['id'] ? 'selected' : ''; ?>>
                                <?php echo $enseignant['nom'] . " " . $enseignant['prenom']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jour" class="form-label">Jour :</label>
                    <select id="jour" name="jour" class="form-select" required>
                        <?php 
                        $jours = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
                        foreach ($jours as $jour): ?>
                            <option value="<?php echo $jour; ?>" 
                                <?php echo $emploiDuTemps['jour'] === $jour ? 'selected' : ''; ?>>
                                <?php echo $jour; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="heure_debut" class="form-label">Heure de début :</label>
                    <input type="time" id="heure_debut" name="heure_debut" class="form-control" 
                        value="<?php echo htmlspecialchars($emploiDuTemps['heure_debut']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="heure_fin" class="form-label">Heure de fin :</label>
                    <input type="time" id="heure_fin" name="heure_fin" class="form-control" 
                        value="<?php echo htmlspecialchars($emploiDuTemps['heure_fin']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>