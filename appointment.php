<?php
session_start();
include 'db.php';

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['uid'];
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $doctor = trim($_POST['doctor']);
    $department = trim($_POST['department']);
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    $reason = trim($_POST['reason']);
    $status = 'upcoming';

    $stmt = $pdo->prepare("
        INSERT INTO appointments 
        (user_id, full_name, doctor, department, appointment_date, appointment_time, reason, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $full_name, $doctor, $department, $date, $time, $reason, $status]);

    $message = '<div style="color:green;">Appointment booked!</div>';
}

// Fetch appointments
$stmt = $pdo->prepare("
    SELECT 
        id,
        full_name,
        doctor,
        department,
        appointment_date,
        appointment_time,
        status
    FROM appointments
    WHERE user_id = ?
    ORDER BY appointment_date ASC
");
$stmt->execute([$user_id]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HealthCare+ — Appointments</title>
<style>
    :root {
      --bg: linear-gradient(180deg, #4287e9e5 0%, #ffffffe5 100%);
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

        <a href="dashboard.php">
          <img src="img/dashboard-76.png" class="icon-img"> Dashboard</a>

        <a href="appointment.php" class="active">
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

    <!-- Main -->
    <main class="main">

      <!-- TOPBAR -->
      <div class="topbar">
        <div class="topbar-inner">
          <div class="topbar-title">Appointments</div>

          <div class="topbar-search">
            🔍
            <input type="search" placeholder="Search..." />
          </div>

          <div class="topbar-right">
            <div class="topbar-bell">🔔</div>
            <div class="topbar-avatar">
              <img src="img/user.jpg" alt="User" />
            </div>
          </div>
        </div>
      </div>

      <div class="header">
        <div>
          <h1>Appointments</h1>
          <p>All Appointments – Manage and view all your appointments</p>
        </div>
            <a href="bookAppointment.php" class="btn primary">Book New Appointment</a>
      </div>

      <?php if ($message) echo $message; ?>

      <div class="filters">
        <input type="search" placeholder="Search by patient name or doctor..." />
        <select>
          <option>All Status</option>
          <option>Upcoming</option>
          <option>Completed</option>
          <option>Cancelled</option>
        </select>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Department</th>
              <th>Date & Time</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          
    <tbody>
        <?php if (empty($appointments)): ?>
            <tr><td colspan="6" style="text-align:center;">No appointments found.</td></tr>
        <?php else: ?>
            <?php foreach ($appointments as $appt): ?>
                <tr>
                    <td><?= htmlspecialchars($appt['full_name']); ?></td>
                    <td><?= htmlspecialchars($appt['doctor']); ?></td>
                    <td><?= htmlspecialchars($appt['department']); ?></td>
                    <td><?= htmlspecialchars($appt['appointment_date']) . ' ' . htmlspecialchars($appt['appointment_time']); ?></td>
                    <td><span class="status <?= htmlspecialchars(strtolower($appt['status'])); ?>"><?= htmlspecialchars($appt['status']); ?></span></td>
                    <td>
                        <a href="edit_appointment.php?id=<?= $appt['id']; ?>">Edit</a> |
                        <a href="cancel_appointment.php?id=<?= $appt['id']; ?>">Cancel</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>

        </table>
      </div>

      <div class="footer">
        <div>Showing 0 of 0 appointments</div>
        <div class="pagination">
          <button>Previous</button>
          <button>Next</button>
        </div>
      </div>

    </main>
  </div>
</body>
</html>
