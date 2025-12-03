<?php
session_start();
include 'db.php';
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['uid'];

// Example: count total users and appointments
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalAppointments = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HealthCare+ — Analytics Dashboard</title>
  <style>
    :root {
      --bg: #f7fbff;
      --sidebar: #0f172a;
      --accent: #2563eb;
      --accent-hover: #1e40af;
      --card: #ffffff;
      --text: #0f172a;
      --muted: #64748b;
      --border: #e5e7eb;
      --radius: 12px;
      --shadow: 0 8px 24px rgba(0,0,0,0.06);
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      background: var(--bg);
      color: var(--text);
      line-height: 1.5;
    }

    .layout {
      display: grid;
      grid-template-columns: 260px 1fr;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      background: var(--sidebar);
      color: #e2e8f0;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .brand {
      font-weight: 700;
      font-size: 1.2rem;
    }

    nav {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    nav a {
      color: inherit;
      text-decoration: none;
      padding: 0.6rem;
      border-radius: 10px;
      transition: background 0.15s;
    }

    nav a:hover {
      background: rgba(255,255,255,0.05);
    }

    nav a.active {
      background: rgba(37,99,235,0.2);
      color: #fff;
    }

    .main {
      padding: 2rem;
      display: grid;
      gap: 1.5rem;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .topbar input[type="search"] {
      padding: 0.6rem 0.75rem;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 1rem;
      background: #fff;
      width: 100%;
      max-width: 300px;
    }

    .icons {
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    .icon {
      font-size: 1.2rem;
      cursor: pointer;
    }

    .header h1 {
      margin: 0;
      font-size: 1.5rem;
    }

    .header p {
      margin: 0;
      color: var(--muted);
    }

    .grid {
      display: grid;
      gap: 1rem;
    }

    .chart, .tile {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1rem;
      box-shadow: var(--shadow);
    }

    .chart {
      height: 240px;
      display: grid;
      place-items: center;
      color: var(--muted);
      font-size: 0.95rem;
    }

    .status-list {
      display: grid;
      gap: 0.5rem;
    }

    .status-item {
      display: flex;
      justify-content: space-between;
      font-weight: 500;
      color: var(--text);
    }

    .departments ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: grid;
      gap: 0.4rem;
    }

    .departments li {
      padding: 0.4rem 0.6rem;
      background: #f1f5f9;
      border-radius: 8px;
      font-weight: 500;
    }

    .tiles {
      grid-template-columns: repeat(3, 1fr);
    }

    @media (max-width: 820px) {
      .layout {
        grid-template-columns: 1fr;
      }
      .sidebar {
        position: sticky;
        top: 0;
        z-index: 10;
      }
      .tiles {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="brand">HealthCare+</div>
      <nav>
        <a href="#">🏠 Dashboard</a>
        <a href="#">📅 Appointments</a>
        <a href="#" class="active">📊 Analytics</a>
        <a href="#">👤 Profile</a>
        <a href="#">❓ Help</a>
        <a href="#">🔒 Logout</a>
      </nav>
    </aside>

    <!-- Main -->
    <main class="main">
      <div class="topbar">
        <input type="search" placeholder="Search..." />
        <div class="icons">
          <div class="icon">🔔</div>
          <div class="icon">👤</div>
        </div>
      </div>

      <div class="header">
        <h1>Analytics Dashboard</h1>
        <p>Track appointment trends and insights</p>
      </div>

      <section class="chart">[Monthly Appointments Chart Placeholder]</section>

      <section class="grid">
        <div class="tile">
          <h3>Appointment Status</h3>
          <div class="status-list">
            <div class="status-item"><span>Confirmed</span><span>—</span></div>
            <div class="status-item"><span>Pending</span><span>—</span></div>
            <div class="status-item"><span>Completed</span><span>—</span></div>
            <div class="status-item"><span>Cancelled</span><span>—</span></div>
          </div>
        </div>

        <div class="tile departments">
          <h3>Popular Departments</h3>
          <ul>
            <li>Cardiology</li>
            <li>Orthopedics</li>
            <li>Dermatology</li>
            <li>Pediatrics</li>
            <li>General Medicine</li>
          </ul>
        </div>
      </section>

      <section class="grid tiles">
        <div class="tile">
          <h3>Average Daily Appointments</h3>
          <p style="color: var(--muted);">[Data Placeholder]</p>
        </div>
        <div class="tile">
          <h3>Patient Satisfaction</h3>
          <p style="color: var(--muted);">[Data Placeholder]</p>
        </div>
        <div class="tile">
          <h3>Cancellation Rate</h3>
          <p style="color: var(--muted);">[Data Placeholder]</p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
