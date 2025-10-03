<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Vérification si l'utilisateur est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: ../../index.php");
    exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Récupération des matières pour la liste déroulante
$result = $conn->query("SELECT id, nom FROM matieres");
$subjects = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/GestionScolaireGroupe/Public/css/style.css">
</head>

<body>
    <!-- Sidebar -->
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
    <h1 class="text-center mb-4">Ajouter un utilisateur</h1>

    <form action="../../../controllers/AdminController.php?action=addUser" method="post" class="p-4 shadow rounded bg-light">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom :</label>
            <input type="text" name="nom" id="nom" class="form-control" placeholder="Entrez le nom" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom :</label>
            <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Entrez le prénom" required>
        </div>
        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance :</label>
            <input type="date" name="date_naissance" id="date_naissance" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Entrez l'email" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Rôle :</label>
            <select name="role" id="role" class="form-select" required onchange="toggleSubjectField()">
                <option value="" selected disabled>Choisissez un rôle</option>
                <option value="administrateur">administrateur</option>
                <option value="enseignant">Enseignant</option>
                <option value="etudiant">Étudiant</option>
            </select>
        </div>
        <div class="mb-3" id="subject-field" style="display: none;">
            <label for="subject" class="form-label">Matière (enseignant) :</label>
            <select name="subject" id="subject" class="form-select">
                <option value="" selected disabled>Choisissez une matière</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?php echo $subject['id']; ?>">
                        <?php echo htmlspecialchars($subject['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-dark w-100">Ajouter l'utilisateur</button>
    </form>

    <a href="gerer_utilisateurs.php" class="btn btn-dark mt-3">Retour à la gestion des utilisateurs</a>
</div>
<footer>
        <p>&copy; 2024 Gestion Scolaire. Tous droits réservés.</p>
    </footer>

     <!-- Intégration des scripts -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSubjectField() {
    const role = document.getElementById('role').value;
    const subjectField = document.getElementById('subject-field');
    subjectField.style.display = role === 'enseignant' ? 'block' : 'none';
}
</script>
</body>
</html>