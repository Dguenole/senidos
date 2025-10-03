<?php
session_start();

// Vérification du rôle de l'utilisateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: ../../index.php");
        exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Vérifier si l'ID de l'utilisateur est présent dans l'URL
if (!isset($_GET['id'])) {
    echo "ID utilisateur manquant.";
    exit;
}

$id = $_GET['id'];

// Récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Utilisateur introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
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
        <h2 class="text-center mb-4">Modifier l'Utilisateur: <?php echo $user['nom']; ?></h2>

        <form action="/GestionScolaireGroupe/Controllers/AdminController.php?action=updateUser" method="post" class="p-4 shadow rounded bg-light">
            <div class="mb-3">
                <label for="username" class="form-label">Nom :</label>
                <input type="text" name="nom" id="username" class="form-control" value="<?php echo $user['nom']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Prenom :</label>
                <input type="text" name="prenom" id="username" class="form-control" value="<?php echo $user['prenom']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="date_naissance" class="form-label">Date de naissance :</label>
                <input type="date"  name="date_naissance" id="date_naissance" class="form-control"value="<?php echo $user['DateNaissance']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle :</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="enseignant" <?php if ($user['role'] == 'enseignant') echo 'selected'; ?>>Enseignant</option>
                    <option value="etudiant" <?php if ($user['role'] == 'etudiant') echo 'selected'; ?>>Étudiant</option>
                </select>
            </div>
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <button type="submit" class="btn btn-primary w-100">Mettre à jour l'utilisateur</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Gestion Scolaire. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>