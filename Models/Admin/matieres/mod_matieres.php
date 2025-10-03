<?php
session_start();

// Vérification si l'utilisateur est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: ../../index.php");
    exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Initialisation des variables
$matiere = null;
$enseignants = [];
$errorMessage = '';
$successMessage = '';

// Vérifier si l'ID de la matière est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gestion_matieres.php");
    exit;
}

$matiereId = intval($_GET['id']);

// Récupérer les informations de la matière
$stmt = $conn->prepare("SELECT * FROM matieres WHERE id = ?");
$stmt->bind_param("i", $matiereId);
$stmt->execute();
$result = $stmt->get_result();
$matiere = $result->fetch_assoc();

if (!$matiere) {
    header("Location: gestion_matieres.php");
    exit;
}

// Récupérer la liste des enseignants
$enseignantsResult = $conn->query("SELECT id, nom, prenom FROM utilisateurs WHERE role = 'enseignant'");
$enseignants = $enseignantsResult->fetch_all(MYSQLI_ASSOC);

// Traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateSubject'])) {
    $newName = trim($_POST['subject_name']);
    $enseignantId = !empty($_POST['enseignant_id']) ? intval($_POST['enseignant_id']) : null;

    if (!empty($newName)) {
        // Mise à jour de la matière
        if ($enseignantId) {
            $stmt = $conn->prepare("UPDATE matieres SET nom = ?, enseignant_id = ? WHERE id = ?");
            $stmt->bind_param("sii", $newName, $enseignantId, $matiereId);
        } else {
            $stmt = $conn->prepare("UPDATE matieres SET nom = ?, enseignant_id = NULL WHERE id = ?");
            $stmt->bind_param("si", $newName, $matiereId);
        }

        if ($stmt->execute()) {
            $successMessage = "La matière a été mise à jour avec succès.";
        } else {
            $errorMessage = "Une erreur s'est produite lors de la mise à jour.";
        }
    } else {
        $errorMessage = "Le nom de la matière est obligatoire.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Matière</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/GestionScolaireGroupe/Public/css/style.css">
</head>

<body>
      <!-- Sidebar -->
      <div class="sidebar">
        <h4>SENEIDOS/h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/matieres/gerer_matieres.php"><i class="fas fa-book"></i> Matières</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/emploiesDuTemps/gerer_emploiesdutemps.php"><i class="fas fa-calendar-alt"></i> Emplois du temps</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/utilisateurs/gerer_utilisateurs.php"><i class="fas fa-users"></i> Utilisateurs</a>
            <a class="nav-link" href="/GestionScolaireGroupe/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </div>

    <div class="content">
        <h2 class="text-center mb-4">Modifier une Matière</h2>

        <!-- Messages -->
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"> <?php echo $successMessage; ?> </div>
        <?php elseif (!empty($errorMessage)): ?>
            <div class="alert alert-danger"> <?php echo $errorMessage; ?> </div>
        <?php endif; ?>

        <!-- Formulaire de modification -->
        <form action="" method="post" class="p-4 shadow rounded bg-light">
            <div class="mb-3">
                <label for="subject_name" class="form-label">Nom de la matière :</label>
                <input 
                    type="text" 
                    name="subject_name" 
                    id="subject_name" 
                    class="form-control" 
                    value="<?php echo htmlspecialchars($matiere['nom']); ?>" 
                    required>
            </div>
            <div class="mb-3">
                <label for="enseignant_id" class="form-label">Enseignant assigné (facultatif) :</label>
                <select name="enseignant_id" id="enseignant_id" class="form-control">
                    <option value="">-- Aucun enseignant --</option>
                    <?php foreach ($enseignants as $enseignant): ?>
                        <option value="<?php echo $enseignant['id']; ?>" <?php echo ($matiere['enseignant_id'] == $enseignant['id']) ? 'selected' : ''; ?>>
                            <?php echo $enseignant['prenom'] . ' ' . $enseignant['nom']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="updateSubject" class="btn btn-primary w-100">Mettre à jour</button>
        </form>

        <div class="text-center mt-4">
            <a href="gestion_matieres.php" class="btn btn-secondary">Retour à la gestion des matières</a>
        </div>
    </div>

    <footer class="text-center mt-5 py-3 bg-light">
        <p>&copy; 2025 Gestion Scolaire. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
