<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'enseignant') {
    header("Location: ../../index.php");
        exit;
}

require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = $_POST['etudiant_id'];
    $matiere_id = $_POST['matiere_id'];
    $note = $_POST['note'];
    $date_attribution = date('Y-m-d'); // Date actuelle

    $query = $conn->prepare("INSERT INTO Notes (etudiant_id, matiere_id, note, date_attribution) VALUES (?, ?, ?, ?)");
    $query->bind_param("iids", $etudiant_id, $matiere_id, $note, $date_attribution);

    if ($query->execute()) {
        $success = "Note ajoutée avec succès.";
    } else {
        $error = "Erreur lors de l'ajout de la note.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Note</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/GestionScolaireGroupe/Public/css/style.css">


</head>
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
        <h2>Ajouter une Note</h2>
        <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form action="ajout_note.php" method="POST">
            <div class="mb-3">
                <label for="etudiant_id" class="form-label">Étudiant</label>
                <select class="form-select" name="etudiant_id" id="etudiant_id" required>
                    <option value="">-- Choisir un étudiant --</option>
                    <?php
                    $result = $conn->query("SELECT id, nom, prenom FROM Utilisateurs WHERE role = 'etudiant'");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['prenom']} {$row['nom']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="matiere_id" class="form-label">Matière</label>
                <select class="form-select" name="matiere_id" id="matiere_id" required>
                    <option value="">-- Choisir une matière --</option>
                    <?php
                    $result = $conn->query("SELECT id, nom FROM Matieres WHERE enseignant_id = {$_SESSION['id']}");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nom']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <input type="number" step="0.01" class="form-control" name="note" id="note" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</body>
</html>