<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



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