<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../../index.php");
    exit();
}
require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

// Récupérer les notes de l'étudiant connecté
$etudiant_id = $_SESSION['id'];
$query = $conn->prepare("
    SELECT mat.nom AS matiere, n.note 
    FROM Notes n
    JOIN Matieres mat ON n.matiere_id = mat.id
    WHERE n.etudiant_id = ?
");
$query->bind_param("i", $etudiant_id);
$query->execute();
$result = $query->get_result();
$notes = $result->fetch_all(MYSQLI_ASSOC);

// Calculer la moyenne générale de l'étudiant connecté
$etudiant_id = $_SESSION['id'];
$query = $conn->prepare("SELECT AVG(note) AS moyenne_generale FROM Notes WHERE etudiant_id = ?");
$query->bind_param("i", $etudiant_id);
$query->execute();
$result = $query->get_result();
$moyenne_generale = $result->fetch_assoc()['moyenne_generale'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/GestionScolaireGroupe/Public/css/style.css"></head>
<style>
    body {
    background-color: #f8f9fa;
}
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.card-title {
    font-size: 1.5rem;
}
</style>
<body>
<div class="sidebar">
        <h4>Gestion Scolaire</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
            <a class="nav-link" href="consulter_notes.php"><i class="fas fa-book"></i> Mes Notes</a>
            <a class="nav-link" href="emploi_du_temps.php"><i class="fa-regular fa-file"></i>Emplois du Temps</a>
            <a class="nav-link" href="modifier_mdp.php"><i class="fas fa-key"></i> modifier MDP</a>
            <a class="nav-link" href="/GestionScolaireGroupe/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </div>
    <div class="content">
        <h1>Mes Notes</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($note['matiere']); ?></td>
                        <td><?php echo htmlspecialchars($note['note']); ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
        <div class="container mt-4">
        <h1>Ma Moyenne Générale</h1>
        <p>Votre moyenne générale est : <strong><?php echo number_format($moyenne_generale, 2); ?></strong></p>
    </div>
    </div>

</body>
</html>