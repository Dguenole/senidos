<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Intégration de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #d7e8f7, #9ac2e6, #6788c0);
            background-size: 400% 400%;
            animation: gradientAnimation 8s ease infinite;
            font-family: Arial, sans-serif;
            color: #333;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .login-container {
            background-color: #ffffff;
            color: #333;
            max-width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #357ABD;
        }
        .form-control {
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            box-shadow: 0px 0px 5px rgba(53, 122, 189, 0.8);
            border-color: #357ABD;
        }
        .btn-primary {
            width: 100%;
            border-radius: 5px;
            background-color: #357ABD;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #285F8F;
        }
        .navbar {
            background-color: #357ABD;
        }
        .navbar-brand {
            font-weight: bold;
            color: #fff !important;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f1f1f1;
            color: #333;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">SENIDOS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav"></div>
        </div>
    </nav>

    <div class="d-flex align-items-center justify-content-center" style="width: 100%; height: 100%;">
        <div class="login-container">
            <h2>Connexion</h2>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email :</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Entrez votre email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe :</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Entrez votre mot de passe" required>
                </div>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Gestion Scolaire. Tous droits réservés.</p>
    </footer>

    <!-- Intégration de Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
