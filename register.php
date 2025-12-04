<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'] ?? 'patient';
    $password = $_POST['password'];
    $confirm = $_POST['confirmPassword'];

    if ($password !== $confirm) {
        echo '<div style="color:red;">Passwords do not match!</div>';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, role, password_hash) VALUES (?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$fullName, $email, $phone, $role, $hash]);

            // Auto-login after registration
            $newUserId = $pdo->lastInsertId();
            $_SESSION['uid'] = $newUserId;
            $_SESSION['role'] = $role;

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;

        } catch (PDOException $e) {
            echo '<div style="color:red;">Email already exists!</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>HealthCare+ — Create Account</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #f6f8fb;
    --card: #ffffff;
    --text: #1f2937;
    --muted: #6b7280;
    --primary: #2563eb;
    --border: #e5e7eb;
    --radius: 12px;
    --shadow: 0 10px 30px rgba(17,24,39,0.08);
  }

  * { box-sizing: border-box; }

  body {
    margin: 0;
    font-family: Inter, system-ui, sans-serif;
    background: linear-gradient(180deg, #7fa7cf 0%, var(--bg) 100%);
    color: var(--text);
  }

  .container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 24px;
  }

  .card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    max-width: 720px;
    width: 100%;
    padding: 24px;
  }

  .brand {
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 16px;
  }

  h1 {
    text-align: center;
    font-size: 24px;
    margin: 0 0 16px;
  }

  p.hint {
    text-align: center;
    color: var(--muted);
    margin-bottom: 40px;
  }

  form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }

  label {
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 4px;
  }

  input,
  select {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid var(--border);
    font-size: 14px;
  }

  input:focus, select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(147,197,253,0.4);
  }

  .checkbox-row {
    margin-top: 30px;
    display: flex;
    align-items: center;
    gap: 8px;
    grid-column: 1 / -1;
  }

  .checkbox-row input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin: 0;
    accent-color: var(--primary);
  }

  .checkbox-row label {
    color: var(--text);
    font-weight: 400;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
  }

  a.link {
    color: #2563eb;
    text-decoration: underline;
  }

  .actions {
    grid-column: 1 / -1;
    text-align: center;
    margin-top: 16px;
  }

  .btn-primary {
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 12px 25px;
    font-weight: 700;
    cursor: pointer;
    font-size: 15px;
    width: 650px;
  }

  .btn-primary:hover { background: #1d4ed8; }

  .footer-links {
    grid-column: 1 / -1;
    text-align: center;
    margin-top: 12px;
  }

  .back {
    display: block;
    text-align: center;
    margin-top: 24px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
    color: var(--text);
    text-decoration: none;
    font-size: 14px;
  }

  .back:hover { text-decoration: underline; }

  @media (max-width: 720px) {
    form { grid-template-columns: 1fr; }
  }

  #role {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%2300000" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px 16px;
  }
</style>
</head>

<body>
  <div class="container">
    <div class="card">
      <div class="brand">HealthCare+</div>

      <h1>Create account</h1>
      <p class="hint">Join us to start managing your healthcare</p>

      <form method="POST">
        <div>
          <label for="fullName">Full name</label>
          <input id="fullName" name="fullName" type="text" placeholder="John Doe" required />
        </div>

        <div>
          <label for="email">Email address</label>
          <input id="email" name="email" type="email" placeholder="email@example.com" required />
        </div>

        <div>
          <label for="phone">Phone number</label>
          <input id="phone" name="phone" type="tel" placeholder="(555) 123-4567" />
        </div>

        <div>
          <label for="role">Role</label>
          <select id="role" name="role" required>
            <option value="" disabled selected>Select your role</option>
            <option value="patient">Patient</option>
            <option value="caregiver">Caregiver</option>
            <option value="provider">Healthcare Provider</option>
            <option value="admin">Administrator</option>
          </select>
        </div>

        <div>
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Create a strong password" required />
        </div>

        <div>
          <label for="confirmPassword">Confirm password</label>
          <input id="confirmPassword" name="confirmPassword" type="password" placeholder="Confirm your password" required />
        </div>

        <div class="checkbox-row">
          <input id="tos" name="tos" type="checkbox" required />
          <label for="tos">I agree to the <a href="#" class="link">Terms of Service</a> and <a href="#" class="link">Privacy Policy</a>.</label>
        </div>

        <div class="actions">
          <button type="submit"><a href="dashboard.php"  class="btn-primary">Create account </a></button>
        </div>

        <div class="footer-links">
          Already have an account?
          <a href="login.php" class="link">Sign in here</a>
        </div>
      </form>

      <a href="frontPage.php" class="back">&#8592; Back to Home</a>
    </div>
  </div>
</body>
</html>