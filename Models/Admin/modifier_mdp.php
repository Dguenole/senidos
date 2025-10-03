<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: ../../index.php");
    exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Message d'erreur ou de succès
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_utilisateur = $_SESSION['id'];
    $ancien_mdp = $_POST['ancien_mdp'];
    $nouveau_mdp = $_POST['nouveau_mdp'];
    $confirmer_mdp = $_POST['confirmer_mdp'];

    // Vérification que les champs sont remplis
    if (!empty($ancien_mdp) && !empty($nouveau_mdp) && !empty($confirmer_mdp)) {
        // Vérification que le nouveau mot de passe est confirmé
        if ($nouveau_mdp === $confirmer_mdp) {
            // Récupération de l'ancien mot de passe dans la base de données
            $query = $conn->prepare("SELECT motDePasse FROM utilisateurs WHERE id = ?");
            $query->bind_param('i', $id_utilisateur);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Vérification de l'ancien mot de passe
                if (password_verify($ancien_mdp, $row['motDePasse'])) {
                    // Hashage du nouveau mot de passe
                    $nouveau_mdp_hash = password_hash($nouveau_mdp, PASSWORD_BCRYPT);
                    
                    // Mise à jour dans la base de données
                    $update_query = $conn->prepare("UPDATE utilisateurs SET motDePasse = ? WHERE id = ?");
                    $update_query->bind_param('si', $nouveau_mdp_hash, $id_utilisateur);

                    if ($update_query->execute()) {
                        $message = "Mot de passe modifié avec succès.";
                    } else {
                        $message = "Une erreur est survenue. Veuillez réessayer.";
                    }
                } else {
                    $message = "L'ancien mot de passe est incorrect.";
                }
            } else {
                $message = "Utilisateur introuvable.";
            }
        } else {
            $message = "Les nouveaux mots de passe ne correspondent pas.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/GestionScolaireGroupe/Public/css/style.css">
        <!-- Style personnalisé -->
       
</head>
<body>
      <!-- Sidebar -->
      <div class="sidebar">
        <h4>Gestion Scolaire</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/matieres/gerer_matieres.php"><i class="fas fa-book"></i> Matières</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/emploiesDuTemps/gerer_emploiesdutemps.php"><i class="fas fa-calendar-alt"></i> Emplois du temps</a>
            <a class="nav-link" href="/GestionScolaireGroupe/Models/Admin/utilisateurs/gerer_utilisateurs.php"><i class="fas fa-users"></i> Utilisateurs</a>
            <a class="nav-link" href="/GestionScolaireGroupe/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </div>
    <div class="content">
        <h2 class="text-center">Modifier le mot de passe</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="" class="mt-4">
            <div class="mb-3">
                <label for="ancien_mdp" class="form-label">Ancien mot de passe :</label>
                <input type="password" name="ancien_mdp" id="ancien_mdp" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nouveau_mdp" class="form-label">Nouveau mot de passe :</label>
                <input type="password" name="nouveau_mdp" id="nouveau_mdp" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirmer_mdp" class="form-label">Confirmer le nouveau mot de passe :</label>
                <input type="password" name="confirmer_mdp" id="confirmer_mdp" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Modifier</button>
        </form>
    </div>
</body>
</html>