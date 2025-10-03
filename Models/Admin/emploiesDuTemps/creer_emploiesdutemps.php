<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: ../../index.php");
    exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Récupération des matières
$matieres = [];
$resultMatieres = $conn->query("SELECT id, nom FROM Matieres");
if ($resultMatieres && $resultMatieres->num_rows > 0) {
    while ($row = $resultMatieres->fetch_assoc()) {
        $matieres[] = $row;
    }
}

// Récupération des enseignants
$enseignants = [];
$resultEnseignants = $conn->query("SELECT id, nom, prenom FROM Utilisateurs WHERE role = 'enseignant'");
if ($resultEnseignants && $resultEnseignants->num_rows > 0) {
    while ($row = $resultEnseignants->fetch_assoc()) {
        $enseignants[] = $row;
    }
}

// Gestion du formulaire
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classe = htmlspecialchars($_POST['classe']);
    $matiere = htmlspecialchars($_POST['matiere']);
    $enseignant = htmlspecialchars($_POST['enseignant']);
    $salle = htmlspecialchars($_POST['salle']);
    $jour = htmlspecialchars($_POST['jour']);
    $heure_debut = htmlspecialchars($_POST['heure_debut']);
    $heure_fin = htmlspecialchars($_POST['heure_fin']);

    // Vérifier que les champs ne sont pas vides
    if ($classe && $salle && $matiere && $enseignant && $jour && $heure_debut && $heure_fin) {
        $stmt = $conn->prepare("INSERT INTO EmploisDuTemps (classe, matiere_id, enseignant_id, jour, heure_debut, heure_fin, salle) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            $errorMessage = "Erreur SQL : " . $conn->error;
        } else {
            $stmt->bind_param("sssssss", $classe, $matiere, $enseignant, $jour, $heure_debut, $heure_fin, $salle);
            if ($stmt->execute()) {
                $successMessage = "Emploi du temps ajouté avec succès.";
            } else {
                $errorMessage = "Erreur SQL : " . $stmt->error;
            }
            $stmt->close();
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
    <title>Créer un Emploi du Temps</title>
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
        <h2>Créer un Emploi du Temps</h2>

        <!-- Affichage des messages -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php elseif ($errorMessage): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Formulaire -->
        <form action="" method="POST">
            <div class="mb-3">
                <label for="classe" class="form-label">Classe :</label>
                <input type="text" id="classe" name="classe" class="form-control" placeholder="Exemple : 3ème A" required>
            </div>
            <div class="mb-3">
                <label for="salle" class="form-label">Salle :</label>
                <input type="text" id="salle" name="salle" class="form-control" placeholder="Exemple : salle 5-3" required>
            </div>
            <div class="mb-3">
                <label for="matiere" class="form-label">Matière :</label>
                <select name="matiere" id="matiere" class="form-select" required>
                    <option value="" selected disabled>Choisissez une matière</option>
                    <?php if (empty($matieres)): ?>
                        <option disabled>Aucune matière disponible</option>
                    <?php else: ?>
                        <?php foreach ($matieres as $matiere): ?>
                            <option value="<?php echo $matiere['id']; ?>"><?php echo $matiere['nom']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="enseignant" class="form-label">Enseignant :</label>
                <select name="enseignant" id="enseignant" class="form-select" required>
                    <option value="" selected disabled>Choisissez un enseignant</option>
                    <?php if (empty($enseignants)): ?>
                        <option disabled>Aucun enseignant disponible</option>
                    <?php else: ?>
                        <?php foreach ($enseignants as $enseignant): ?>
                            <option value="<?php echo $enseignant['id']; ?>">
                                <?php echo $enseignant['nom'] . " " . $enseignant['prenom']; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jour" class="form-label">Jour :</label>
                <select id="jour" name="jour" class="form-control" required>
                    <option value="">Sélectionnez un jour</option>
                    <option value="Lundi">Lundi</option>
                    <option value="Mardi">Mardi</option>
                    <option value="Mercredi">Mercredi</option>
                    <option value="Jeudi">Jeudi</option>
                    <option value="Vendredi">Vendredi</option>
                    <option value="Samedi">Samedi</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="heure_debut" class="form-label">Heure de début :</label>
                <input type="time" id="heure_debut" name="heure_debut" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="heure_fin" class="form-label">Heure de fin :</label>
                <input type="time" id="heure_fin" name="heure_fin" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
            <button type="reset" class="btn btn-secondary w-100 mt-2">Réinitialiser</button>
        </form>
    </div>
    <footer class="text-center mt-5">
        <p>&copy; <?php echo date("Y"); ?> Gestion Scolaire. Tous droits réservés.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>