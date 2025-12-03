<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>HealthCare+ Profile & Settings</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f9fafb;
      color: #1f2937;
    }
    .container {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 240px;
      background: #1f2937;
      color: #f3f4f6;
      padding: 20px;
    }
    .sidebar h2 {
      font-size: 20px;
      margin-bottom: 30px;
    }
    .nav-link {
      display: block;
      padding: 10px 0;
      color: #f3f4f6;
      text-decoration: none;
      border-left: 4px solid transparent;
    }
    .nav-link.active {
      background: #374151;
      border-left: 4px solid #22c55e;
    }
    .main {
      flex: 1;
      padding: 40px;
      background: #fff;
    }
    .section {
      margin-bottom: 40px;
    }
    .section h3 {
      margin-bottom: 20px;
      font-size: 18px;
      color: #111827;
    }
    .profile-header {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .profile-header img {
      width: 80px;
      height: 80px;
      border-radius: 12px;
      object-fit: cover;
    }
    .profile-header .info {
      line-height: 1.4;
    }
    .btn {
      padding: 10px 16px;
      background: #22c55e;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }
    .form-group {
      margin-bottom: 16px;
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-size: 14px;
      color: #374151;
    }
    input, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 14px;
    }
    .checkbox-group {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .checkbox-group label {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
    }
    .checkbox-group input[type="checkbox"] {
      width: 16px;
      height: 16px;
    }
  </style>
</head>
<body>
  <div class="container"> --> 
    <!-- Sidebar 
    <aside class="sidebar">
      <h2>HealthCare+</h2>
      <a href="#" class="nav-link">Dashboard</a>
      <a href="#" class="nav-link">Appointments</a>
      <a href="#" class="nav-link">Analytics</a>
      <a href="#" class="nav-link active">Profile</a>
      <a href="#" class="nav-link">Help</a>
      <a href="#" class="nav-link">Logout</a>
    </aside>-->

    <!-- Main Content 
    <main class="main">-->
      <!-- Account Settings 
      <section class="section">
        <h3>Account Settings</h3>
        <div class="profile-header">
          <img src="https://images.unsplash.com/photo-1603415526960-f8f0a1f1b4b0?q=80&w=200&auto=format&fit=crop" alt="Profile Photo" />
          <div class="info">
            <div><strong>Kim Min Gyu</strong></div>
            <div>mingyu.kim@gmail.com</div>
          </div>
          <button class="btn">Change Photo</button>
        </div>
      </section>-->

      <!-- Personal Information 
      <section class="section">
        <h3>Personal Information</h3>
        <form>
          <div class="form-group">
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" value="Kim Min Gyu" />
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" value="mingyu.kim@gmail.com" />
          </div>
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" value="09095622886" />
          </div>
          <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" value="1997-04-06" />
          </div>
          <div class="form-group">
            <label for="blood">Blood Type</label>
            <input type="text" id="blood" value="AB+" />
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" value="Seoul, South Korea" />
          </div>
          <button class="btn">Save Changes</button>
        </form>
      </section>-->

      <!-- Change Password 
      <section class="section">
        <h3>Change Password</h3>
        <form>
          <div class="form-group">
            <label for="currentPwd">Current Password</label>
            <input type="password" id="currentPwd" />
          </div>
          <div class="form-group">
            <label for="newPwd">New Password</label>
            <input type="password" id="newPwd" />
          </div>
          <div class="form-group">
            <label for="confirmPwd">Confirm New Password</label>
            <input type="password" id="confirmPwd" />
          </div>
          <button class="btn">Update Password</button>
        </form>
      </section>-->

      <!-- Notification Preferences 
      <section class="section">
        <h3>Notification Preferences</h3>
        <form class="checkbox-group">
          <label>
            <input type="checkbox" checked />
            Email Reminders: Receive appointment reminders via email
          </label>
          <label>
            <input type="checkbox" checked />
            SMS Reminders: Receive appointment reminders via text message
          </label>
          <label>
            <input type="checkbox" />
            Promotional Emails: Receive updates about new services and offers
          </label>
        </form>
      </section>
    </main>
  </div>
</body>
</html> -->
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
$user = $stmt->fetch();

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>HealthCare+ Profile Settings</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f3f4f6;
      color: #1f2937;
    }
    .layout {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 240px;
      background: #1f2937;
      color: #f9fafb;
      padding: 20px;
    }
    .sidebar h2 {
      font-size: 20px;
      margin-bottom: 30px;
    }
    .nav-link {
      display: block;
      padding: 10px 0;
      color: #f9fafb;
      text-decoration: none;
      border-left: 4px solid transparent;
    }
    .nav-link.active {
      background: #374151;
      border-left: 4px solid #22c55e;
    }

    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px 24px;
      background: #fff;
      border-bottom: 1px solid #e5e7eb;
    }
    .search-bar {
      display: flex;
      align-items: center;
      background: #f3f4f6;
      padding: 8px 12px;
      border-radius: 8px;
      border: 1px solid #d1d5db;
    }
    .search-bar input {
      border: none;
      background: transparent;
      outline: none;
      font-size: 14px;
    }
    .top-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .notif {
      position: relative;
    }
    .notif .dot {
      position: absolute;
      top: -2px;
      right: -2px;
      width: 8px;
      height: 8px;
      background: red;
      border-radius: 50%;
    }
    .avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
    }

    .content {
      display: flex;
      padding: 40px;
      gap: 40px;
      flex-wrap: wrap;
    }
    .panel {
      background: #fff;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      flex: 1;
      min-width: 300px;
    }
    .panel h3 {
      margin-bottom: 20px;
      font-size: 18px;
      color: #111827;
    }
    .profile-info {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .profile-info img {
      width: 80px;
      height: 80px;
      border-radius: 12px;
      object-fit: cover;
    }
    .profile-info .meta {
      line-height: 1.4;
    }
    .btn {
      margin-top: 20px;
      padding: 10px 16px;
      background: #22c55e;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }
    .form-group {
      margin-bottom: 16px;
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-size: 14px;
      color: #374151;
    }
    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2>HealthCare+</h2>
      <a href="#" class="nav-link">Dashboard</a>
      <a href="#" class="nav-link">Appointments</a>
      <a href="#" class="nav-link">Analytics</a>
      <a href="#" class="nav-link active">Profile</a>
      <a href="#" class="nav-link">Help</a>
      <a href="#" class="nav-link">Logout</a>
    </aside>

    <!-- Main Content -->
    <div class="main">
      <!-- Topbar -->
      <div class="topbar">
        <div class="search-bar">
          <input type="text" placeholder="Search..." />
        </div>
        <div class="top-actions">
          <div class="notif">
            <img src="https://img.icons8.com/ios-filled/24/000000/bell.png" alt="Notifications" />
            <div class="dot"></div>
          </div>
          <img class="avatar" src="https://images.unsplash.com/photo-1603415526960-f8f0a1f1b4b0?q=80&w=200&auto=format&fit=crop" alt="User" />
        </div>
      </div>

      <!-- Profile Panels -->
      <div class="content">
        <!-- Left Panel -->
        <div class="panel">
          <h3>Account Settings</h3>
          <div class="profile-info">
            <img src="https://images.unsplash.com/photo-1603415526960-f8f0a1f1b4b0?q=80&w=200&auto=format&fit=crop" alt="Profile" />
            <div class="meta">
              <div><strong>Kim Min Gyu</strong></div>
              <div>mingyu.kim@gmail.com</div>
            </div>
          </div>
          <button class="btn">Change Photo</button>
        </div>

        <!-- Right Panel -->
        <div class="panel">
          <h3>Personal Information</h3>
          <form>
            <div class="form-group">
              <label for="fullName">Full Name</label>
              <input type="text" id="fullName" value="Kim Min Gyu" />
            </div>
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" value="mingyu.kim@gmail.com" />
            </div>
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" value="09095622886" />
            </div>
            <div class="form-group">
              <label for="dob">Date of Birth</label>
              <input type="date" id="dob" value="1997-04-06" />
            </div>
            <div class="form-group">
              <label for="blood">Blood Type</label>
              <input type="text" id="blood" value="AB+" />
            </div>
            <div class="form-group">
              <label for="address">Address</label>
              <input type="text" id="address" value="Seoul, South Korea" />
            </div>
            <button class="btn">Save Changes</button>
          </form>
        </div>

    <section class="card">
        <h2>Change Password</h2>
        <form>
        <div class="form-row">
            <div class="form-group">
            <label for="currentPwd">Current Password</label>
            <input type="password" id="currentPwd" />
            </div>
            <div class="form-group">
            <label for="newPwd">New Password</label>
            <input type="password" id="newPwd" />
            </div>
            <div class="form-group">
            <label for="confirmPwd">Confirm New Password</label>
            <input type="password" id="confirmPwd" />
            </div>
        </div>
        <button class="btn">Update Password</button>
        </form>
    </section>

      </div>
    
    </div>
  </div>
    <section class="card">
    <h2>Notification Preferences</h2>
    <form class="checkbox-group">
      <div class="checkbox-item">
        <div>
          <label for="emailReminders">Email Reminders</label>
          <small>Receive appointment reminders via email</small>
        </div>
        <input type="checkbox" id="emailReminders" checked />
      </div>

      <div class="checkbox-item">
        <div>
          <label for="smsReminders">SMS Reminders</label>
          <small>Receive appointment reminders via text message</small>
        </div>
        <input type="checkbox" id="smsReminders" checked />
      </div>

      <div class="checkbox-item">
        <div>
          <label for="promoEmails">Promotional Emails</label>
          <small>Receive updates about new services and offers</small>
        </div>
        <input type="checkbox" id="promoEmails" />
      </div>
    </form>
  </section>
</body>
</html>
