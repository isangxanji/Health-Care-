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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HealthCare+ — Appointments</title>
  <style>
    :root {
      --bg:  linear-gradient(180deg, #4287e9e5 0%, #ffffffe5 100%);
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
      background: linear-gradient(180deg, #4287e9e5 0%, #ffffffe5 100%);
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
      background: linear-gradient(180deg, #4287e9e5 0%, #ffffffe5 100%);
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
        <div class="header-left">Book Appointment</div>
        <div class="header-center">
          <input type="text" placeholder="Search..." />
        </div>
        <!-- <div class="header-right">
          <img src="https://i.imgur.com/8Km9tLL.jpg" alt="Profile" />
        </div> -->
        <div class="header-right">
          <div class="bell">🔔<span class="dot"></span></div>
          <img src="c:\Users\PRINCESS\Downloads\mingyu.jpg" alt="Profile" class="profile-pic" />
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
