<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];

// Calea către fișierul JSON
$jsonFile = 'data/users.json';

// Funcție pentru a citi utilizatorii din JSON
function getUsers($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    return json_decode($content, true) ?? [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $errors[] = 'Please fill in all fields.';
    }

    if (empty($errors)) {
        $users = getUsers($jsonFile);
        $found = false;
        
        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                // Redirect spre dashboard după login (sau pagina cerută anterior)
                $redirect = isset($_GET['redirect']) && $_GET['redirect'] === 'dashboard'
                    ? 'dashboard.php'
                    : 'dashboard.php';
                header('Location: ' . $redirect);
                exit;
            }
        }
        
        $errors[] = 'Incorrect email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — NeuroLogo AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .auth-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 100px 24px 60px;
        }
        .auth-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 36px 32px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255,255,255,0.03) inset;
        }
        .auth-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 20px;
            text-align: center;
            margin-bottom: 8px;
            color: var(--text);
        }
        .auth-logo span { color: var(--purple2); }
        .auth-subtitle {
            text-align: center;
            font-size: 13px;
            color: var(--text3);
            margin-bottom: 28px;
        }
        .auth-title {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 6px;
            text-align: center;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px;
        }
        .form-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text2);
            letter-spacing: 0.2px;
        }
        .form-input {
            background: var(--card2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            outline: none;
            width: 100%;
            transition: border-color 0.2s;
        }
        .form-input::placeholder { color: var(--text3); }
        .form-input:focus { border-color: rgba(124, 92, 252, 0.5); }
        .auth-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--purple), var(--blue));
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            margin-top: 8px;
        }
        .auth-btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .auth-footer {
            text-align: center;
            font-size: 13px;
            color: var(--text3);
            margin-top: 20px;
        }
        .auth-footer a {
            color: var(--purple2);
            text-decoration: none;
            font-weight: 500;
        }
        .auth-footer a:hover { text-decoration: underline; }
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #f87171;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .divider {
            height: 1px;
            background: var(--border);
            margin: 20px 0;
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-logo">Neuro<span>Logo</span> AI</div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="#">Features</a></li>
        <li><a href="#">Pricing</a></li>
    </ul>
    <a href="register.php"><button class="nav-btn">Sign Up</button></a>
</nav>

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-logo">Neuro<span>Logo</span> AI</div>
        <div class="auth-subtitle">Welcome back! Continue creating.</div>

        <div class="divider"></div>

        <div class="auth-title">Sign In</div>

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <?php foreach ($errors as $err): ?>
                    <div>• <?= htmlspecialchars($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php<?= isset($_GET['redirect']) ? '?redirect=' . htmlspecialchars($_GET['redirect']) : '' ?>">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input class="form-input" type="email" name="email"
                       placeholder="you@example.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input class="form-input" type="password" name="password"
                       placeholder="Your password">
            </div>

            <button class="auth-btn" type="submit">Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="register.php">Sign up for free</a>
        </div>

    </div>
</div>

</body>
</html>