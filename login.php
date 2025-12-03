<?php
session_start();
include 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['uid'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = '<div class="alert alert-danger">Invalid email or password!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HealthCare+ — Sign In</title>
  <style>
    :root {
      --bg: #e0f2fe;
      --card: #ffffff;
      --text: #1f2937;
      --muted: #6b7280;
      --primary: #2563eb;
      --primary-hover: #1e40af;
      --border: #d1d5db;
      --focus: #93c5fd;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      background: linear-gradient(#83BAFF, white) ;
      color: #2563EB;
      line-height: 1.5;
    }

    .container {
      max-width: 400px;
      margin: 5rem auto;
      padding: 1rem;
    }

    .brand {
      text-align: center;
      margin-bottom: 2rem;
    }

    .brand h1 {
      margin: 0;
      font-size: 1.75rem;
      font-weight: 700;
      line-height: 2;
    }

    .brand p {
      margin: 0.5rem 0 0;
      color: gray;
      line-height: 2;
    }

    .card {
      background: var(--card);
      border-radius: 12px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.06);
      padding: 1.5rem;
      border: 1px solid var(--border);
    }

    .card h2 {
      margin: 0 0 1rem;
      font-size: 1.25rem;
      color: rgb(47, 42, 42);
      text-align: center;
      line-height: 15px;
    }

    form {
      display: grid;
      gap: 1rem;
    }

    .field {
      display: grid;
      gap: 0.4rem;
    }

    label {
      font-size: 0.9rem;
      color: var(--muted);
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.7rem 0.8rem;
      border: 1px solid var(--border);
      border-radius: 10px;
      background: #fff;
      font-size: 1rem;
      transition: border-color 0.15s, box-shadow 0.15s;
    }

    input:focus {
      outline: none;
      border-color: var(--focus);
      box-shadow: 0 0 0 3px rgba(147,197,253,0.4);
    }

    .checkbox-row {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .checkbox-row input[type="checkbox"] {
      width: 1rem;
      height: 1rem;
      accent-color: var(--primary);
    }

    .actions {
      display: grid;
      gap: 0.75rem;
    }

    .btn {
      appearance: none;
      border: none;
      border-radius: 10px;
      padding: 0.8rem 1rem;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      background: var(--primary);
      color: #fff;
      transition: 0.15s, transform 0.02s;
    }

    .btn:hover { background: var(--primary-hover); }
    .btn:active { transform: translateY(1px); }

    .link {
      color: var(--primary);
      text-decoration: none;
      font-size: 0.90rem;
    }

    .f-link {
      color: var(--primary);
      text-decoration: none;
      font-size: 0.90rem;
      left: 300px;
    }

    .f-link:hover { text-decoration: underline; }
    .link:hover { text-decoration: underline; }

    .footer-links {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.95rem;
    }

    .back {
      color: gray;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
    }

    .back:hover { color: var(--text); }

    @media (max-width: 420px) {
      .container { margin: 2rem auto; }
    }
  </style>
</head>
<body>
  <main class="container" aria-labelledby="title">
    <section class="card" aria-labelledby="signin">
      <header class="brand">
      <h1 id="title">HealthCare+</h1>
      <h2 id="signin">Welcome Back</h2>

      <p>Sign in to manage your appointments</p>
      </header>

      <form method="POST" >
        <div class="field">
          <label for="email">Email address</label>
          <input id="email" name="email" type="email" placeholder="your.email@example.com" autocomplete="email" required />
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required />
        </div>

        <div class="checkbox-row">
          <input id="remember" name="remember" type="checkbox" />
          <label for="remember">Remember me</label>
          <a class="f-link" href="#">Forgot Password?</a>
        </div>

        <div class="actions">
          <button class="btn" type="submit">Sign In</button>

          <div class="footer-links" aria-label="Account links">
            <a class="link" href="register.php">Don't have an account? Register here</a>
          </div>

          <a class="back" href="frontPage.php">&#8592; Back to home</a>
        </div>
      </form>
    </section>
  </main>
</body>
</html>