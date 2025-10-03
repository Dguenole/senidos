<?php
session_start();

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Vérification du rôle de l'utilisateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: ../../index.php");
        exit;
}

// Déclaration des variables pour la recherche
$searchResult = null;
$searchId = null;

// Vérifier si un utilisateur a été ajouté avec succès
if (isset($_GET['success']) && $_GET['success'] === 'add' && isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    // Récupérer les informations du nouvel utilisateur
    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $newUser = $result->fetch_assoc();
}

// Recherche d'utilisateur par ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search_id']) && is_numeric($_GET['search_id'])) {
    $searchId = $_GET['search_id'];

    // Recherche dans la base de données
    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $searchId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $searchResult = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/GestionScolaireGroupe/Public/css/style.css">
</head>
<body>
      <!-- Sidebar -->
      <div class="sidebar">
        <h4>Gestion Scolaire</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/matieres/gerer_matieres.php"><i class="fas fa-book"></i> Matières</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/emploiesDuTemps/gerer_emploiesdutemps.php"><i class="fas fa-calendar-alt"></i> Emplois du temps</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/utilisateurs/gerer_utilisateurs.php"><i class="fas fa-users"></i> Utilisateurs</a>
            <a class="nav-link" href="/GestionScolaireGroupe/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </div>

    <div class="content">
        <h2>Gestion des Utilisateurs</h2>

        <?php if (isset($newUser)): ?>
        <div class="alert alert-success">
            <strong>Nouvel utilisateur ajouté avec succès !</strong>
            <ul>
                <li><strong>ID :</strong> <?php echo $newUser['id']; ?></li>
                <li><strong>Nom :</strong> <?php echo $newUser['nom']; ?></li>
                <li><strong>Prénom :</strong> <?php echo $newUser['prenom']; ?></li>
                <li><strong>Email :</strong> <?php echo $newUser['email']; ?></li>
                <li><strong>Rôle :</strong> <?php echo ucfirst($newUser['role']); ?></li>
            </ul>
        </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-dark" id="search-user-btn">Rechercher un utilisateur</button>
            <a href="ajouter_utilisateurs.php" class="btn btn-success">Ajouter un utilisateur</a>
        </div>

        <div id="search-user-form" class="card p-3 mb-3" style="display: none;">
            <form action="" method="get">
                <div class="mb-3">
                    <label for="search_id" class="form-label">ID de l'utilisateur :</label>
                    <input type="number" id="search_id" name="search_id" class="form-control" placeholder="Entrez l'ID de l'utilisateur" required>
                </div>
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
        </div>

        <?php if ($searchResult): ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Utilisateur trouvé</h5>
                <p><strong>ID:</strong> <?php echo $searchResult['id']; ?></p>
                <p><strong>Nom :</strong> <?php echo $searchResult['nom']; ?></p>
                <p><strong>Prénom :</strong> <?php echo $searchResult['prenom']; ?></p>
                <p><strong>Date de Naissance:</strong> <?php echo $searchResult['DateNaissance']; ?></p>
                <p><strong>Email:</strong> <?php echo $searchResult['email']; ?></p>
                <p><strong>Rôle:</strong> <?php echo ucfirst($searchResult['role']); ?></p>

                <a href="modifier_utilisateurs.php?id=<?php echo $searchResult['id']; ?>" class="btn btn-warning">Modifier</a>
                <a href="../../../controllers/AdminController.php?action=deleteUser&id=<?php echo $searchResult['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
            </div>
        </div>
        <?php elseif (isset($searchId)): ?>
        <div class="alert alert-danger mt-3">
            Aucun utilisateur trouvé avec l'ID <?php echo htmlspecialchars($searchId); ?>.
        </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Gestion Scolaire. Tous droits réservés.</p>
    </footer>

    <!-- Intégration des scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('search-user-btn').addEventListener('click', function() {
            const form = document.getElementById('search-user-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });
    </script>
</body>
</html>
