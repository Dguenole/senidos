<?php
session_start();

// Vérification si l'utilisateur est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: ../../index.php");
    exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Initialisation des variables
$searchQuery = '';
$searchResults = [];

// Récupérer la liste des enseignants
$enseignants = $conn->query("SELECT id, nom, prenom FROM utilisateurs WHERE role = 'enseignant'");

// Ajout d'une nouvelle matière
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addSubject'])) {
    $newSubject = trim($_POST['subject_name']);
    $enseignantId = !empty($_POST['enseignant_id']) ? intval($_POST['enseignant_id']) : null;

    if (!empty($newSubject)) {
        if ($enseignantId) {
            $stmt = $conn->prepare("INSERT INTO matieres (nom, enseignant_id) VALUES (?, ?)");
            $stmt->bind_param("si", $newSubject, $enseignantId);
        } else {
            $stmt = $conn->prepare("INSERT INTO matieres (nom) VALUES (?)");
            $stmt->bind_param("s", $newSubject);
        }
        $stmt->execute();
        $successMessage = "La matière '$newSubject' a été ajoutée avec succès.";
    } else {
        $errorMessage = "Le nom de la matière est obligatoire.";
    }
}

// Suppression d'une matière
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $subjectId = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM matieres WHERE id = ?");
    $stmt->bind_param("i", $subjectId);
    $stmt->execute();
    $successMessage = "La matière avec l'ID $subjectId a été supprimée avec succès.";
}

// Recherche de matière
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchSubject'])) {
    $searchQuery = trim($_POST['search']);
    $stmt = $conn->prepare("
        SELECT 
            m.id AS matiere_id, 
            m.nom AS matiere_nom, 
            u.nom AS enseignant_nom, 
            u.prenom AS enseignant_prenom
        FROM 
            matieres m
        LEFT JOIN 
            utilisateurs u ON m.enseignant_id = u.id
        WHERE 
            m.id = ? 
            OR m.nom LIKE ? 
            OR u.nom LIKE ? 
            OR u.prenom LIKE ?
    ");
    $likeQuery = '%' . $searchQuery . '%';
    $stmt->bind_param("isss", $searchQuery, $likeQuery, $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();
    $searchResults = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Matières</title>
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
        <h2 class="text-center mb-4">Gestion des Matières</h2>

        <!-- Messages -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php elseif (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Formulaire d'ajout -->
        <form action="" method="post" class="p-4 shadow rounded bg-light mb-5">
            <div class="mb-3">
                <label for="subject_name" class="form-label">Nom de la nouvelle matière :</label>
                <input type="text" name="subject_name" id="subject_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="enseignant_id" class="form-label">Enseignant assigné (facultatif) :</label>
                <select name="enseignant_id" id="enseignant_id" class="form-control">
                    <option value="">-- Aucun enseignant --</option>
                    <?php while ($row = $enseignants->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo $row['prenom'] . ' ' . $row['nom']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="addSubject" class="btn btn-primary w-100">Ajouter la Matière</button>
        </form>

        <!-- Formulaire de recherche -->
        <form action="" method="post" class="p-4 shadow rounded bg-light mb-5">
            <div class="mb-3">
                <label for="search" class="form-label">Rechercher une matière :</label>
                <input 
                    type="text" 
                    name="search" 
                    id="search" 
                    class="form-control" 
                    placeholder="ID, nom de la matière, nom/prénom de l'enseignant"
                    value="<?php echo htmlspecialchars($searchQuery); ?>"
                    required>
            </div>
            <button type="submit" name="searchSubject" class="btn btn-primary w-100">Rechercher</button>
        </form>

        <!-- Résultats de recherche -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchSubject'])): ?>
            <h4>Résultats de la recherche :</h4>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom de la Matière</th>
                        <th>Enseignant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($searchResults)): ?>
                        <?php foreach ($searchResults as $row): ?>
                            <tr>
                                <td><?php echo $row['matiere_id']; ?></td>
                                <td><?php echo $row['matiere_nom']; ?></td>
                                <td>
                                    <?php 
                                    if ($row['enseignant_nom']) {
                                        echo $row['enseignant_prenom'] . " " . $row['enseignant_nom'];
                                    } else {
                                        echo "<span class='text-muted'>Aucun enseignant assigné</span>";
                                    }
                                    ?>
                                </td>
                                <td>
                                <form action="mod_matieres.php" method="GET" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['matiere_id'] ?>">
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </form>
                                    <a href="?action=delete&id=<?php echo $row['matiere_id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette matière ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Aucun résultat trouvé pour "<?php echo htmlspecialchars($searchQuery); ?>".</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <footer class="text-center mt-5 py-3 bg-light">
        <p>&copy; 2025 Gestion Scolaire. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>