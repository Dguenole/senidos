<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Inclure la configuration de la base de données
require '/Applications/XAMPP/xamppfiles/htdocs/GestionScolaireGroupe/Models/config_bd/bd_config.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des entrées utilisateur avec validation
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if ($email && $password) {
        // Recherche de l'utilisateur
        $sql = "SELECT * FROM Utilisateurs WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Validation du mot de passe haché
            if  (password_verify($password, $user['motDePasse'])) {
                // Initialisation des variables de session
                $_SESSION['id'] = $user['id'];
                $_SESSION['nom'] = htmlspecialchars($user['nom']);
                $_SESSION['prenom'] = htmlspecialchars($user['prenom']);
                $_SESSION['role'] = $user['role'];

                // Si l'utilisateur est un enseignant, on ajoute la matière à la session
                if ($user['role'] === 'enseignant') {
                    $_SESSION['matiere'] = htmlspecialchars($user['matiere']);
                }

                // Redirection selon le rôle
                switch ($user['role']) {
                    case 'administrateur':
                        header("Location: /GestionScolaireGroupe/models/Admin/dashboard.php");
                        break;
                    case 'enseignant':
                        header("Location: /GestionScolaireGroupe/models/Enseignant/dashboard.php");
                        break;
                    case 'etudiant':
                        header("Location: /GestionScolaireGroupe/Models/Etudiant/dashboard.php");
                        break;
                }
                exit;
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Aucun utilisateur trouvé avec cet email.";
        }
    } else {
        $error = "Veuillez entrer un email et un mot de passe valides.";
    }
}
?>
