<?php
session_start();
include 'db.php';
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['uid'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $reason = trim($_POST['reason']);
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, date, reason) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $date, $reason]);
    $message = '<div style="color:green;">Appointment booked!</div>';
}

$stmt = $pdo->prepare("SELECT * FROM appointments WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HealthCare+ — Appointments</title>
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

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .header h1 {
      margin: 0;
      font-size: 1.5rem;
    }

    .header p {
      margin: 0;
      color: var(--muted);
    }

    .btn {
      background: var(--accent);
      color: #fff;
      border: none;
      padding: 0.75rem 1rem;
      border-radius: var(--radius);
      font-weight: 600;
      cursor: pointer;
      transition: background 0.15s;
    }

    .btn:hover {
      background: var(--accent-hover);
    }

    .filters {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .filters input[type="search"],
    .filters select {
      padding: 0.6rem 0.75rem;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 1rem;
      background: #fff;
    }

    .table-wrap {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 720px;
    }

    th, td {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid var(--border);
      text-align: left;
    }

    th {
      background: #f1f5f9;
      font-weight: 600;
      color: var(--muted);
    }

    td {
      color: var(--text);
    }

    .status {
      font-weight: 600;
      color: var(--accent);
    }

    .actions button {
      background: none;
      border: none;
      color: var(--accent);
      cursor: pointer;
      font-weight: 600;
    }

    .footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 1rem;
      font-size: 0.95rem;
      color: var(--muted);
    }

    .pagination {
      display: flex;
      gap: 0.5rem;
    }

    .pagination button {
      background: #fff;
      border: 1px solid var(--border);
      padding: 0.4rem 0.75rem;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 500;
    }

    .pagination button:hover {
      background: #f1f5f9;
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
        <a href="#" class="active">📅 Appointments</a>
        <a href="#">📊 Analytics</a>
        <a href="#">👤 Profile</a>
        <a href="#">❓ Help</a>
        <a href="#">🔒 Logout</a>
      </nav>
    </aside>

    <!-- Main -->
    <main class="main">
      <div class="header">
        <div>
          <h1>Appointments</h1>
          <p>All Appointments – Manage and view all your appointments</p>
        </div>
        <button class="btn">Book New Appointment</button>
      </div>

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
            <!-- Empty state -->
            <tr>
              <td colspan="6" style="text-align:center; padding:2rem; color:var(--muted);">
                No appointments found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="footer">
        <div>Showing 5 of 5 appointments</div>
        <div class="pagination">
          <button>Previous</button>
          <button>Next</button>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
