<?php
session_start();
include 'db.php';

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['uid'];

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio']);
    $profile_pic = $user['profile_pic'] ?? 'default.jpg';

    // Handle file upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $file = $_FILES['profile_pic'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png']) && $file['size'] < 2000000) {
            $new_name = "uploads/" . time() . "_" . basename($file['name']);
            if (move_uploaded_file($file['tmp_name'], $new_name)) {
                $profile_pic = $new_name;
            }
        }
    }

    // Update DB
    $update = $pdo->prepare("UPDATE users SET bio = ?, profile_pic = ? WHERE id = ?");
    $update->execute([$bio, $profile_pic, $user_id]);

    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HealthCare+ — Appointments</title>
  <style>
    :root {
      --bg: linear-gradient(to bottom, #dbeafe, #eef4ff);
      --sidebar: #ffffff;
      --accent: #2563eb;
      --accent-hover: #1e40af;
      --card: #ffffff;
      --text: #1e293b;
      --muted: #6b7280;
      --border: #e2e8f0;
      --radius: 12px;
      --shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      background: var(--bg);
      color: var(--text);
      line-height: 1.5;
    }

    /* BRAND (HealthCare+ Logo + Subtitle) */
.brand {
  font-size: 22px;
  font-weight: 700;
  color: var(--accent);
  margin-bottom: 4px;
}

.brand-subtitle {
  font-size: 13px;
  color: var(--muted);
  margin-bottom: 20px;
}

    /* Layout */
    .layout {
      display: grid;
      grid-template-columns: 240px 1fr;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      background: var(--sidebar);
      border-right: 1px solid var(--border);
      padding: 1.5rem 1.2rem;
      display: flex;
      flex-direction: column;
      gap: 14px;
      width: 240px;
    }

    nav a {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #475569;
      text-decoration: none;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      font-weight: 500;
      transition: 0.2s;
    }

    nav a.active {
      background: #eaf2ff;
      border-left: 4px solid var(--accent);
      color: var(--accent);
    }

    nav a:hover {
      background: #f5f9ff;
    }

    /* Main Area */
    .main {
      background: var(--bg);
      padding: 2rem 2.5rem;
    }

    /* Header Card */
    .header {
      background: linear-gradient(to right, #a4c7ff, #c8d9ff);
      padding: 26px 30px;
      border-radius: 14px;
      box-shadow: var(--shadow);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header h1 {
      margin: 0;
      font-size: 30px;
      font-weight: 700;
    }

    .header p {
      margin: 5px 0 0;
      color: var(--muted);
    }

    /* Button */
    .btn {
      background: var(--accent);
      padding: 12px 20px;
      border-radius: 8px;
      border: none;
      color: white;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
    }

    .btn:hover {
      background: var(--accent-hover);
    }

    /* Filters */
    .filters {
      display: flex;
      gap: 1rem;
      margin: 1.7rem 0;
      align-items: center;
    }

    .filters input,
    .filters select {
      background: white;
      padding: 14px 16px;
      border-radius: 12px;
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      font-size: 14px;
    }

    .filters input { flex: 1; }

    /* Table */
    .table-wrap {
      width: 100%;
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      background: #f1f5f9;
      color: var(--muted);
      padding: 1rem 1.2rem;
      font-size: 14px;
      text-align: left;
    }

    td {
      padding: 1rem 1.2rem;
      border-bottom: 1px solid var(--border);
      font-size: 14px;
    }

    tbody tr:hover td {
      background: #f8fbff;
    }

    /* Status Badge */
    .status {
      padding: 6px 10px;
      background: #eaf2ff;
      color: var(--accent);
      font-size: 12px;
      font-weight: 600;
      border-radius: 8px;
    }

    /* Footer */
    .footer {
      display: flex;
      justify-content: space-between;
      margin-top: 1.3rem;
      font-size: 14px;
      color: var(--muted);
    }

    .pagination button {
      border: 1px solid var(--border);
      background: white;
      border-radius: 8px;
      padding: 7px 14px;
      cursor: pointer;
      transition: 0.2s;
    }

    .pagination button:hover {
      background: #eaf2ff;
      color: var(--accent);
      border-color: var(--accent);
    }

    /* upper search bar */
    .topbar {
      position: relative;
      z-index: 3;
      width: 100%;
      margin-bottom: 1.5rem;
      padding: 0 0.4rem;
    }

    .topbar-inner {
      background: #ffffff;
      border-radius: 14px;
      padding: 12px 20px;
      width: 100%;
      box-shadow: 0 6px 20px rgba(0,0,0,0.06);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }

    .topbar-title {
      font-size: 18px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .topbar-title::before {
      content: "";
      width: 6px;
      height: 28px;
      background: var(--accent);
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(37,99,235,0.2);
    }

    .topbar-search {
      display: flex;
      align-items: center;
      gap: 10px;
      background: #ffffff;
      border: 1px solid var(--border);
      padding: 10px 14px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
      min-width: 300px;
    }

    .topbar-search input {
      border: none;
      outline: none;
      background: transparent;
      font-size: 14px;
      width: 100%;
      color: var(--muted);
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 18px;
    }

    .topbar-bell {
      position: relative;
      font-size: 20px;
      cursor: pointer;
    }

    .topbar-bell::after {
      content: "";
      position: absolute;
      top: -2px;
      right: -4px;
      width: 8px;
      height: 8px;
      background: #ef4444;
      border-radius: 50%;
      border: 2px solid white;
    }

    .topbar-avatar img {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      object-fit: cover;
    }

        /* Main */
    .main {
      flex: 1;
      padding: 30px;
    }

    /* Header Bar */
    .header-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fff;
      padding: 12px 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .header-left {
      font-size: 18px;
      font-weight: bold;
    }
    .header-center input {
      width: 300px;
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .header-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .bell {
      position: relative;
      font-size: 20px;
      cursor: pointer;
    }
    .dot {
      position: absolute;
      top: -4px;
      right: -4px;
      width: 10px;
      height: 10px;
      background: red;
      border-radius: 50%;
    }
    .user-icon {
      font-size: 20px;
      cursor: pointer;
    }

    /* Content layout */
    .content {
      display: grid;
      grid-template-columns: 300px 1fr;
      gap: 30px;
      margin-bottom: 30px;
    }

    /* Profile Card */
    .profile-card {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .profile-card img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      margin-bottom: 10px;
    }
    .profile-card h2 {
      margin: 10px 0 5px;
    }
    .profile-card p {
      color: #666;
      margin-bottom: 15px;
    }
    .profile-card button {
      padding: 8px 12px;
      border: 1px solid #2563eb;
      background: #fff;
      color: #2563eb;
      border-radius: 6px;
      cursor: pointer;
    }
    .profile-card button:hover {
      background: #2563eb;
      color: #fff;
    }
    .profile-pic {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ccc;
    cursor: pointer;
    }


    /* Personal Info Form */
    .form-section {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .form-section h2 {
      margin-top: 0;
      margin-bottom: 20px;
    }
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .form-row {
      display: flex;
      flex-direction: column;
    }
    .form-row label {
      font-weight: bold;
      margin-bottom: 6px;
      font-size: 14px;
      color: #333;
    }
    .form-row input,
    .form-row select {
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background: #f9f9f9;
    }
    .form-actions {
      text-align: right;
      margin-top: 20px;
    }
    .form-actions button {
      background: #2563eb;
      color: #fff;
      border: none;
      padding: 10px 16px;
      border-radius: 6px;
      cursor: pointer;
    }
    .form-actions button:hover {
      background: #1e4fd7;
    }

    /* Password Section */
    .password-section {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }
    .password-form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .password-form .form-row {
      display: flex;
      flex-direction: column;
    }
    .password-form label {
      font-weight: bold;
      margin-bottom: 6px;
      font-size: 14px;
      color: #333;
    }
    .password-form input {
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background: #f9f9f9;
    }
    .password-form .form-actions {
      text-align: right;
      margin-top: 10px;
    }
    .password-form .form-actions button {
      background: #2563eb;
      color: #fff;
      border: none;
      padding: 10px 16px;
      border-radius: 6px;
      cursor: pointer;
    }
    .password-form .form-actions button:hover {
      background: #1e4fd7;
    }

    /* Notification Section */
    /* .notification-section {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .notification-option {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 12px 16px;
      margin-bottom: 12px;
      background: #f9f9f9;
    }
    .notification-option p {
      margin: 0;
      font-weight: bold;
    }
    .notification-option small {
      color: #666;
    } */

    .notification-section {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .notification-section h2 {
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 18px;
    }

    .notification-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .notification-text p {
    margin: 0;
    font-weight: bold;
    font-size: 15px;
    color: #333;
    }

    .notification-text small {
    color: #666;
    font-size: 13px;
    }

    /* Responsive Fix */
    @media (max-width: 820px) {
      .layout {
        grid-template-columns: 1fr;
      }
      .sidebar {
        position: sticky;
        top: 0;
        z-index: 10;
      }
      .topbar-inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 16px;
      }
      .topbar-search {
        width: 100%;
        min-width: unset;
      }
    }
.icon-img {
      width: 20px;
      height: 20px;
      object-fit: contain;
      display: inline-block;
      margin-right: 0.6rem;
    }
</style>
</head>
<body>
  <div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="brand">HealthCare+</div>
      <div class="brand-subtitle">Appointment System</div>

      <nav class="nav" aria-label="Primary">

        <a href="dashboard.php" class="active">
          <img src="img/dashboard-76.png" class="icon-img"> Dashboard</a>

        <a href="appointment.php">
          <img src="img/appointment-4.png" class="icon-img"> Appointment</a>

        <a href="analytics.php">
          <img src="img/analytics-7.png" class="icon-img"> Analytics</a>

        <a href="profile.php">
          <img src="img/user-profile.png" class="icon-img"> Profile</a>

        <div class="help-logout">
          <a href="help.php">
            <img src="img/help-85.png" class="icon-img"> Help</a>
            
          <a href="logout.php" id="logout">
            <img src="img/logout-24.png" class="icon-img"> Logout</a>
        </div>
      </nav>
    </aside>





    <!-- Main Content -->
    <main class="main">

      <!-- Header Bar -->
      <div class="header-bar">
        <div class="header-left">Profile & Settings</div>
        <div class="header-center">
          <input type="text" placeholder="Search..." />
        </div>
        <div class="header-right">
          <div class="bell">🔔<span class="dot"></span></div>
          <img src="img/user.jpg" alt="Profile" class="profile-pic" />
        </div>
      </div>

      <div class="content">

        <!-- Profile Card -->
        <section class="profile-card">
          <img src="img/user.jpg" alt="Kim Min Gyu" />
          <h2><?php echo htmlspecialchars($user['full_name'] ?? ''); ?></h2>
          <p><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
          <button>Change Photo</button>
        </section>

        <!-- Personal Info Form -->
        <section class="form-section">
          <h2>Personal Information</h2>
          <form>
            <div class="form-grid">
              <div class="form-row">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" />
              </div>
              <div class="form-row">
                <label for="email">Email Address</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" />
              </div>
              <div class="form-row">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" />
              </div>
              <div class="form-row">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>" />
              </div>
              <div class="form-row">
                <label for="blood">Blood Type</label>
                <select id="blood">
                  <option>O+</option>
                  <option>A+</option>
                  <option>B+</option>
                  <option selected>AB+</option>
                </select>
              </div>
              <div class="form-row">
                <label for="address">Address</label>
                <input type="text" id="address" value="Seoul, South Korea" />
              </div>
            </div>
            <div class="form-actions">
              <button type="submit">Save Changes</button>
            </div>
          </form>
        </section>
      </div>

      <!-- Change Password -->
    <section class="password-section">
        <h2>Change Password</h2>
        <form class="password-form">
    <div class="form-row">
      <label for="currentPass">Current Password</label>
      <input type="password" id="currentPass" />
    </div>
    <div class="form-row">
      <label for="newPass">New Password</label>
      <input type="password" id="newPass" />
    </div>
    <div class="form-row">
      <label for="confirmPass">Confirm New Password</label>
      <input type="password" id="confirmPass" />
    </div>
    <div class="form-actions">
      <button type="submit">Update Password</button>
    </div>
  </form>
</section>


    <!-- Notification Preferences -->
    <section class="notification-section">
    <h2>Notification Preferences</h2>
    <div class="notification-card">
        <div class="notification-text">
        <p>Email Reminders</p>
        <small>Receive appointment reminders via email</small>
        </div>
        <input type="checkbox" checked />
    </div>
    <div class="notification-card">
        <div class="notification-text">
        <p>SMS Reminders</p>
        <small>Receive appointment reminders via text message</small>
        </div>
        <input type="checkbox" checked />
    </div>
    <div class="notification-card">
        <div class="notification-text">
        <p>Promotional Emails</p>
        <small>Receive updates about new services and offers</small>
        </div>
        <input type="checkbox" />
    </div>
    </section>


    </main>
  </div>
</body>
</html>
