<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';


// Vérification et suppression de l'utilisateur
if (isset($_GET['action']) && $_GET['action'] === 'deleteUser' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Vérification que l'ID est valide (un entier)
    if (is_numeric($id)) {
        $stmt = $conn->prepare("DELETE FROM Utilisateurs WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: /GestionScolaireGroupe/Models/Admin/utilisateurs/gerer_utilisateurs.php?success=delete");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Erreur de suppression de l'utilisateur. Veuillez réessayer.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>ID invalide. Veuillez vérifier l'ID de l'utilisateur.</div>";
    }
}

// Vérification et mise à jour de l'utilisateur
if (isset($_GET['action']) && $_GET['action'] === 'updateUser' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $dateNaissance = $_POST['date_naissance'];
    $role = $_POST['role'];

    // Vérification que l'ID est valide
    if (is_numeric($id)) {
        $stmt = $conn->prepare("UPDATE Utilisateurs SET nom = ?, prenom = ?,DateNaissance = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nom, $prenom,$dateNaissance, $email, $role, $id);

        if ($stmt->execute()) {
            header("Location: /GestionScolaireGroupe/Models/Admin/utilisateurs/gerer_utilisateurs.php?success=update");?>
            <div class="alert alert-success mt-4">La note a été enregistrée avec succès.</div>
        <?php
            exit;
        } else {
            echo "<div class='alert alert-danger'>Erreur de mise à jour. Veuillez réessayer.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>ID invalide. Veuillez vérifier l'ID de l'utilisateur.</div>";
    }
}
//Ajouter un utilisateur
if (isset($_GET['action']) && $_GET['action'] === 'addUser' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $dateNaissance = $_POST['date_naissance'];
    $role = $_POST['role'];
    $subject = $role === 'enseignant' ? $_POST['subject'] : null;

    // Définir le mot de passe par défaut en fonction du rôle
    $defaultPassword = '';
    switch ($role) {
        case 'administrateur':
            $defaultPassword = 'admin2025';
            break;
        case 'enseignant':
            $defaultPassword = 'prof2025';
            break;
        case 'etudiant':
            $defaultPassword = 'etu2025';
            break;
    }

    // Hacher le mot de passe par défaut pour le stockage sécurisé
    $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

    // Préparer la requête pour insérer l'utilisateur
    $stmt = $conn->prepare("INSERT INTO Utilisateurs (nom, prenom, email, role, matiere, motDePasse,DateNaissance) VALUES (?, ?, ?, ?, ?, ? , ?)");
    $stmt->bind_param("sssssss", $nom, $prenom, $email, $role, $subject, $hashedPassword,$dateNaissance);

    if ($stmt->execute()) {
        $lastInsertId = $conn->insert_id; // ID du dernier utilisateur inséré



        header("Location: /GestionScolaireGroupe/Models/Admin/utilisateurs/gerer_utilisateurs.php?success=add&userId=".$lastInsertId);
        exit;
        } 
    else {
        echo "Erreur lors de l'ajout de l'utilisateur : " . $stmt->error;
        exit;
    }
}



// Affichage du message de succès
if (isset($_GET['success'])) {
    echo "<div class='alert alert-success'>L'action a été effectuée avec succès.</div>";
}

// Vérification et suppression de Emplois
if (isset($_GET['action']) && $_GET['action'] === 'deleteEmplois' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Vérification que l'ID est valide (un entier)
    if (is_numeric($id)) {
        $stmt = $conn->prepare("DELETE FROM EmploisDuTemps WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: /GestionScolaireGroupe/Models/Admin/emploiesDuTemps/gerer_emploiesdutemps.php?success=delete");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Erreur de suppression de l'utilisateur. Veuillez réessayer.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>ID invalide. Veuillez vérifier l'ID de l'utilisateur.</div>";
    }
}


// Vérification et mise à jour de l'utilisateur
if (isset($_GET['action']) && $_GET['action'] === 'UpdateEmplois' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $classe = htmlspecialchars($_POST['classe']);
    $matiere = htmlspecialchars($_POST['matiere']);
    $enseignant = htmlspecialchars($_POST['enseignant']);
    $salle = htmlspecialchars($_POST['salle']);
    $jour = htmlspecialchars($_POST['jour']);
    $heure_debut = htmlspecialchars($_POST['heure_debut']);
    $heure_fin = htmlspecialchars($_POST['heure_fin']);

    // Vérification que l'ID est valide
    if (is_numeric($id)) {
        $stmt = $conn->prepare("UPDATE EmploisDuTemps SET classe = ?, matiere_id = ?, enseignant_id = ?, salle = ?, jour = ?,heure_debut = ?,heure_fin = ?  WHERE id = ?");
        $stmt->bind_param("sssssssi", $classe, $matiere,$enseignant, $salle, $jour, $heure_debut,$heure_fin,$id);

        if ($stmt->execute()) {
            header("Location: /GestionScolaireGroupe/Models/Admin/emploiesDuTemps/mod_emploiesdutemps.php?success=update");?>
            <div class="alert alert-success mt-4">La note a été enregistrée avec succès.</div>
        <?php
            exit;
        } else {
            echo "<div class='alert alert-danger'>Erreur de mise à jour. Veuillez réessayer.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>ID invalide. Veuillez vérifier l'ID de l'utilisateur.</div>";
    }
}
?>
