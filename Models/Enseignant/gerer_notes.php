<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'enseignant') {
    header("Location: ../../index.php");
    exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

if (isset($_POST['delete'])) {
    $note_id = $_POST['note_id'];
    $query = $conn->prepare("DELETE FROM Notes WHERE id = ?");
    $query->bind_param("i", $note_id);
    $query->execute();
}

if (isset($_POST['update'])) {
    $note_id = $_POST['note_id'];
    $note = $_POST['note'];
    $query = $conn->prepare("UPDATE Notes SET note = ? WHERE id = ?");
    $query->bind_param("di", $note, $note_id);
    $query->execute();
}

$result = $conn->query("SELECT Notes.id, note, date_attribution, 
    CONCAT(U.prenom, ' ', U.nom) AS etudiant, M.nom AS matiere
    FROM Notes
    INNER JOIN Utilisateurs U ON Notes.etudiant_id = U.id
    INNER JOIN Matieres M ON Notes.matiere_id = M.id
    WHERE M.enseignant_id = {$_SESSION['id']}");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Notes</title>
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
        <h2>Gérer les Notes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Étudiant</th>
                    <th>Matière</th>
                    <th>Note</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['etudiant']; ?></td>
                        <td><?php echo $row['matiere']; ?></td>
                        <td><?php echo $row['note']; ?></td>
                        <td><?php echo $row['date_attribution']; ?></td>
                        <td>
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="note_id" value="<?php echo $row['id']; ?>">
                                <input type="number" step="0.01" name="note" required>
                                <button type="submit" name="update" class="btn btn-success btn-sm">Modifier</button>
                            </form>
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="note_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>