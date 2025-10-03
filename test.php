<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('pexels-pixabay-207691.jpg') no-repeat center center/cover;
        }

        .left-section {
            color: white;
            text-align: left;
            max-width: 400px;
        }

        .left-section h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .left-section p {
            font-size: 1rem;
            line-height: 1.5;
        }

        .signup-container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin-left: 20px;
        }

        .signup-container h2 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: black;
            border: none;
            width: 100%;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #444;
        }

        .divider {
            text-align: center;
            margin: 15px 0;
            font-size: 0.9rem;
            color: #aaa;
            position: relative;
        }

        .divider:before,
        .divider:after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background-color: #ddd;
        }

        .divider:before {
            left: 0;
        }

        .divider:after {
            right: 0;
        }
    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center w-100 px-3">
        <!-- Left Section -->
        <div class="left-section">
            <h1>Building the Future...</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>

        <!-- Sign Up Form -->
        <div class="signup-container">
            <h2>Create an Account</h2>
            <form method="POST" action="login.php">
                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
            <div class="divider">Or</div>
            <button class="btn btn-secondary w-100">Continue with Google</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
