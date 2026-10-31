<?php
/**
 * Page de Connexion Administrateur - MY TAXI
 */
require_once __DIR__ . '/../config.php';

$error = '';

if (isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? cleanInput($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($username) && !empty($password)) {
        $pdo = getDb();
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['mytaxi_admin_id'] = $admin['id'];
            $_SESSION['mytaxi_admin_user'] = $admin['username'];
            $_SESSION['mytaxi_admin_name'] = $admin['display_name'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Identifiant ou mot de passe incorrect.';
        }
    } else {
        $error = 'Veuillez saisir votre identifiant et votre mot de passe.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration · MY TAXI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 50% 0%, #1f2029 0%, #0d0e12 100%);
            padding: 24px;
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            background: rgba(22, 23, 29, 0.95);
            border: 1px solid rgba(235, 129, 37, 0.25);
            border-radius: 18px;
            padding: 40px 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 40px rgba(235, 129, 37, 0.08);
            backdrop-filter: blur(12px);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-logo img {
            max-width: 220px;
            height: auto;
            filter: drop-shadow(0 4px 12px rgba(235, 129, 37, 0.3));
        }
        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 8px;
        }
        .login-header p {
            color: #9ca3af;
            font-size: 14px;
            margin: 0;
        }
        .login-alert {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid #ef4444;
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: #d1d5db;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background: #111216;
            border: 1px solid #2d2f39;
            border-radius: 10px;
            color: #ffffff;
            font-size: 14px;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #eb8125;
            box-shadow: 0 0 0 3px rgba(235, 129, 37, 0.25);
            background: #16171d;
        }
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #eb8125 0%, #ff9534 100%);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 15px rgba(235, 129, 37, 0.35);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(235, 129, 37, 0.45);
        }
        .login-hint {
            margin-top: 24px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 8px;
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
            border: 1px dashed rgba(255, 255, 255, 0.1);
        }
        .login-hint strong {
            color: #eb8125;
        }
        .back-site {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
            font-size: 13px;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-site:hover {
            color: #eb8125;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-logo">
                <img src="../assets/logo-white.png" alt="MY TAXI Logo">
            </div>
            <div class="login-header">
                <h1>Espace Administration</h1>
                <p>Gestion des courses & dispatching MY TAXI</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="login-alert">
                    <span>⚠️</span>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" autocomplete="off">
                <div class="form-group">
                    <label for="username">Identifiant Administrateur</label>
                    <input type="text" id="username" name="username" placeholder="admin" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-login">Se connecter au tableau de bord ↗</button>
            </form>

            <div class="login-hint">
                Compte par défaut : <strong>admin</strong> / <strong>taxi2026</strong>
            </div>

            <a href="../" class="back-site">← Retour au site public MY TAXI</a>
        </div>
    </div>
</body>
</html>
