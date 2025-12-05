<?php
session_start();
include 'db.php'; // your database connection

// Optional: redirect if not logged in
if (!isset($_SESSION['uid'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['uid'];
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullName = $_POST['fullName'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $department = $_POST['department'];
  $doctor = $_POST['doctor'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $reason = $_POST['reason'];

  // Save to database
  $stmt = $pdo->prepare("INSERT INTO appointments 
    (user_id, full_name, email, phone, department, doctor, appointment_date, appointment_time, reason, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'upcoming')");
  $stmt->execute([$user_id, $fullName, $email, $phone, $department, $doctor, $date, $time, $reason]);

  $message = '<div style="background:#d4edda;color:#155724;padding:1rem;border-radius:4px;margin-bottom:1rem;">
    Appointment booked successfully!
  </div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Book Appointment – HealthCare+</title>
  <style>
    body {
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
  /* Replace plain color with gradient */
  background: linear-gradient(180deg, #4287e9 0%, #ffffff 100%);
  color: #333;
    }


    .container {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 240px;
      background: #fff;
      border-right: 1px solid #ddd;
      padding: 20px;
    }

    .sidebar-header {
      margin-bottom: 30px;
    }

    .sidebar-header h1 {
      margin: 0;
      font-size: 22px;
      color: #2563eb;
    }

    .sidebar-header p {
      margin: 4px 0 0;
      font-size: 14px;
      color: #666;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar li {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px;
      margin-bottom: 8px;
      border-radius: 6px;
      cursor: pointer;
      color: #333;
    }

    .sidebar li:hover {
      background: #f0f4ff;
    }

    .sidebar li.active {
      background: #2563eb;
      color: #fff;
    }

    .sidebar li.logout {
      color: #d32f2f;
    }

    .sidebar li.logout:hover {
      background: #ffe5e5;
    }

    .sidebar .icon {
      font-size: 18px;
      width: 24px;
      text-align: center;
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
      margin-bottom: 30px;
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

    .header-right img {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #ccc;
      cursor: pointer;
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

    /* Appointment Form */
    .form-section {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      max-width: 900px;
      margin: auto;
    }

    .form-section h2 {
      margin-top: 0;
      margin-bottom: 30px;
      font-size: 24px;
      color: #2563eb;
      text-align: center;
    }

    .form-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      margin-bottom: 30px;
    }

    .form-subgroup {
      display: flex;
      flex-direction: column;
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
    .form-row select,
    .form-row textarea {
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background: #f9f9f9;
    }

    .form-row textarea {
      resize: vertical;
      min-height: 100px;
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
      font-weight: bold;
      margin-left: 10px;
    }

    .form-actions .cancel {
      background: #ccc;
      color: #333;
    }

    .form-actions .cancel:hover {
      background: #bbb;
    }

    .form-actions .submit:hover {
      background: #1e4fd7;
    }

    @media (max-width: 768px) {
      .form-group {
        grid-template-columns: 1fr;
      }
    }

        /* Sidebar */
    .sidebar {
      background: #ffffff;
      color: var(--sidebar-text);
      padding: 1rem;
      display: grid;
      grid-template-rows: auto 1fr auto;
      gap: 1rem;
      border-right: 1px solid #030711;
      color: inherit;
      text-decoration: none;
      padding: 0.6rem;
      border-radius: 10px;
      transition: background 0.15s;
    }

    .brand {
      display: grid;
      gap: 0.35rem;
      padding: 0.5rem 0.5rem 0.5rem 0.25rem;
    }
    .brand h1 {
      margin: 0;
      font-size: 1.25rem;
      letter-spacing: 0.2px;
      color: #2563EB;
    }
    .brand small {
      color: var(--sidebar-muted);
      color: #64748b;
    }

    .nav {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .nav a {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.65rem 0.75rem;
      border-radius: 10px;
      text-decoration: none ;
      transition: background 0.15s, color 0.15s;
      color: #1e293b;
    }
    .icon-img {
      width: 20px;
      height: 20px;
      object-fit: contain;
      display: inline-block;
      margin-right: 0.6rem;
    }

    .nav a.active {
      background: rgba(37, 99, 235, 0.18);
      color: #1e293b;
    }
    .nav a:hover {
      background: rgba(148, 163, 184, 0.12);
    }

    .help-logout {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .help-logout a {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.65rem 0.75rem;
      border-radius: 10px;
      text-decoration: none ;
      transition: background 0.15s, color 0.15s;
      color: #1e293b;
    }

    .help-logout a:hover { background: rgba(148, 163, 184, 0.12); }

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
  <div class="container">
    <!-- Sidebar -->
      <aside class="sidebar" aria-label="Sidebar navigation">
      <div class="brand">
        <h1>HealthCare+</h1>
        <small>Appointment System</small>
      </div>

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
        <div class="header-left">Book Appointment</div>
        <div class="header-center">
          <input type="text" placeholder="Search..." />
        </div>
        <!-- <div class="header-right">
          <img src="https://i.imgur.com/8Km9tLL.jpg" alt="Profile" />
        </div> -->
        <div class="header-right">
          <div class="bell">🔔<span class="dot"></span></div>
          <img src="img/user.jpg" alt="Profile" class="profile-pic" />
        </div>
      </div>

      <!-- Appointment Form -->
      <section class="form-section">
        <h2>New Appointment</h2>
        <form>
          <div class="form-group">

            <!-- Patient Info -->
            <div class="form-subgroup">
              <div class="form-row">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" value=>
              </div>
              <div class="form-row">
                <label for="email">Email Address</label>
                <input type="email" id="email" value= >
              </div>
              <div class="form-row">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" value=>
              </div>
            </div>

            <!-- Appointment Details -->
            <div class="form-subgroup">
              <div class="form-row">
                <label for="department">Department</label>
                <select id="department">
                  <option>Select Department</option>
                  <option>Cardiology</option>
                  <option>Dermatology</option>
                  <option>Neurology</option>
                </select>
              </div>
              <div class="form-row">
                <label for="doctor">Doctor</label>
                <select id="doctor">
                  <option>Select Doctor</option>
                  <option>Dr. Smith</option>
                  <option>Dr. Lee</option>
                  <option>Dr. Patel</option>
                </select>
              </div>
              <div class="form-row">
                <label for="date">Preferred Date</label>
                <input type="date" id="date" />
              </div>
              <div class="form-row">
                <label for="time">Preferred Time</label>
                <select id="time">
                  <option>Select Time</option>
                  <option>09:00 AM</option>
                  <option>10:00 PM</option>
                  <option>11:00 AM</option>
                  <option>01:00 PM</option>
                  <option>02:00 PM</option>
                  <option>03:00 PM</option>
                  <option>04:00 PM</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-row">
            <label for="reason">Reason for Visit</label>
            <textarea id="reason" placeholder="Please describe your symptoms or reason for the appointment..."></textarea>
          </div>

          <div class="form-actions">
            <button type="button" class="cancel">Cancel</button>
            <button type="submit" class="submit">Book Appointment</button>
          </div>
        </form>
      </section>
    </main>
    </div>
</body>
</html>
