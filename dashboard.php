<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Get user info
$stmt = $pdo->prepare("SELECT full_name, role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['uid']]);
$user = $stmt->fetch();

// Get appointment stats
$stmt = $pdo->prepare("SELECT 
    COUNT(*) AS total,
    SUM(status='upcoming') AS upcoming,
    SUM(status='completed') AS completed,
    SUM(status='cancelled') AS cancelled
FROM appointments WHERE user_id = ?");
$stmt->execute([$_SESSION['uid']]);
$stats = $stmt->fetch();



// Get upcoming appointments
$stmt = $pdo->prepare("SELECT appointment_date, appointment_time, status 
                       FROM appointments 
                       WHERE user_id = ? AND status = 'upcoming' 
                       ORDER BY appointment_date ASC");
$stmt->execute([$_SESSION['uid']]);
$upcomingAppointments = $stmt->fetchAll();
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
      --sidebar: #93c5fd;
      --sidebar-text: #eeeff1;
      --sidebar-muted: #e6e9ef;
      --primary: #2563eb;
      --primary-hover: #1e40af;
      --card: #ffffff;
      --text: #02050b;
      --muted: #64748b;
      --border: #e5e7eb;
      --focus: #93c5fd;
      --success: #16a34a;
      --warning: #d97706;
      --danger: #dc2626;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      background: linear-gradient(180deg, #4287e9e5 0%, #ffffffe5 100%);
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

    /* Main */

    .main-wrapper {
      background: #ffffff;
      margin: 1.5rem;
      padding: 2rem;
      border-radius: 18px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }
    .main {
      padding: 1.5rem;
      display: grid;
      gap: 1.5rem;
    }

    .dashboard-container {
      max-width: 1350px;
      margin: 0 auto;
      width: 100%;
      padding: 1rem 2rem;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
    }
    .topbar .title-wrap {
      display: grid;
      gap: 0.25rem;
    }
    .topbar h2 {
      margin: 0;
      font-size: 1.25rem;
    }
    .topbar p {
      margin: 0;
      color: var(--muted);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      background: var(--card);
      border: 1px solid var(--border);
      padding: 0.5rem 0.75rem;
      border-radius: 12px;
    }
    .avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #c7d2fe;
      display: grid;
      place-items: center;
      font-weight: 700;
      color: #1e293b;
    }
    .user-info .name {
      font-weight: 600;
    }

    /* Stats */
    .grid {
      display: grid;
      gap: 1rem;
    }

    .stats {
      grid-template-columns: repeat(4, minmax(180px, 1fr));
      gap: 1.25rem; 
    }

    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1rem;
      box-shadow: 0 8px 28px rgba(0,0,0,0.06);
    }

    .stat {
      display: grid;
      gap: 0.35rem;
    }
    .stat .label {
      color: var(--muted);
      font-size: 0.9rem;
    }
    .stat .value {
      font-size: 1.8rem;
      font-weight: 700;
      letter-spacing: 0.5px;
    }
    .stat.total .value { color: var(--text); }
    .stat.upcoming .value { color: var(--primary); }
    .stat.completed .value { color: var(--success); }
    .stat.cancelled .value { color: var(--danger); }

    /* Quick actions */
    .actions {
      grid-template-columns: repeat(3, minmax(220px, 1fr));
      align-items: stretch;
    }

    .btn {
      appearance: none;
      border: 1px solid var(--border);
      background: #f8fafc;
      color: var(--text);
      padding: 0.9rem 1rem;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: border-color 0.15s, transform 0.02s, background 0.15s;
      text-align: left;
    }
    .btn:hover {
      background: #eef2ff;
      border-color: #c7d2fe;
    }
    .btn:active { transform: translateY(1px); }
    .btn.primary {
      background: var(--primary);
      color: #fff;
      border-color: var(--primary);
    }
    .btn.primary:hover { background: var(--primary-hover); border-color: var(--primary-hover); }

    /* Upcoming appointments */
    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
    }
    .section-header h3 {
      margin: 0;
      font-size: 1.1rem;
    }
    .link {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
    }

    .link:hover { text-decoration: underline; }

    .empty {
      display: grid;
      place-items: center;
      gap: 0.5rem;
      padding: 2rem;
      color: var(--muted);
      border: 2px dashed #cbd5e1;
      border-radius: 12px;
      background: #f8fafc;
    }

    /* Responsive */
    @media (max-width: 1080px) {
      .stats { grid-template-columns: repeat(2, 1fr); }
      .actions { grid-template-columns: 1fr; }
    }
    @media (max-width: 820px) {
      .layout { grid-template-columns: 1fr; }
      .sidebar { position: sticky; top: 0; z-index: 20; }
    }


.nav a:hover .icon-img,
.nav a.active .icon-img {
  filter: none;             /* restore full color on hover/active */
  transform: scale(1.1);    /* subtle zoom for feedback */
}
  </style>
</head>
<body>
  <div class="layout">
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


    <!-- Main -->
    <main class="main">
      <div class="topbar">
        <div class="title-wrap">
          <h2>Dashboard</h2>
          <p>Welcome back, <?php echo htmlspecialchars($user['full_name']); ?>! Here's an overview of your appointments and activity</p>
        </div>
        <div class="user-info" aria-label="Current user">
          <div class="avatar" aria-hidden="true">AM</div>
          <div>
            <div class="name"><?php echo htmlspecialchars($user['full_name']); ?></div>
            <small class="muted" style="color: var(--muted);"><?php echo htmlspecialchars($user['role']); ?></small>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <section class="grid stats" aria-labelledby="stats-title">
        <h2 id="stats-title" class="sr-only" style="position:absolute;left:-9999px;">Appointment statistics</h2>

        <div class="card stat total" aria-label="Total appointments">
          <div class="label">Total Appointments</div>
          <div class="value" id="stat-total"><?php echo str_pad($stats['total'], 2, '0', STR_PAD_LEFT); ?></div>
        </div>

        <div class="card stat upcoming" aria-label="Upcoming appointments">
          <div class="label">Upcoming</div>
          <div class="value" id="stat-upcoming"><?php echo $stats['upcoming']; ?></div>
        </div>

        <div class="card stat completed" aria-label="Completed appointments">
          <div class="label">Completed</div>
          <div class="value" id="stat-completed"><?php echo str_pad($stats['completed'], 2, '0', STR_PAD_LEFT); ?></div>
        </div>

        <div class="card stat cancelled" aria-label="Cancelled appointments">
          <div class="label">Cancelled</div>
          <div class="value" id="stat-cancelled"><?php echo $stats['cancelled']; ?></div>
        </div>
      </section>

      <!-- Quick actions -->
      <section class="card">
        <div class="section-header">
          <h3>Quick Actions</h3>
        </div>
        <div class="grid actions">
          <a href="bookAppointment.php" class="btn primary">Book New Appointment</a>
    <a href="appointment.php" class="btn">View All Appointments</a>
    <a href="profile.php" class="btn">Update Profile</a>
      </section>

      <!-- Upcoming appointments -->
      <section class="card" aria-labelledby="upcoming-title">
  <div class="section-header">
    <h3 id="upcoming-title">Upcoming Appointments</h3>
    <?php if (!empty($upcomingAppointments)): ?>
      <a href="appointments.php" class="link" id="view-all">View All</a>
    <?php endif; ?>
  </div>

  <?php if (!empty($upcomingAppointments)): ?>
    <div id="upcoming-list">
      <?php foreach ($upcomingAppointments as $appt): ?>
        <div class="appointment">
          <strong><?= htmlspecialchars($appt['appointment_date']) ?></strong>
          at <?= htmlspecialchars($appt['appointment_time']) ?>
          (<?= ucfirst(htmlspecialchars($appt['status'])) ?>)
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="empty" id="upcoming-empty">
      <strong>No upcoming appointments</strong>
      <span>When you book one, it will appear here.</span>
      <a href="appointments_new.php" class="btn primary" id="book-inline">Book Now</a>
    </div>
  <?php endif; ?>
</section>
    </main>
  </div>

  <script>
    // Demo handlers (replace with real routes)
    const toast = (msg) => alert(msg);

    document.getElementById('action-book').addEventListener('click', () => {
      toast('Navigate to: /appointments/new');
      // window.location.href = '/appointments/new';
    });
    document.getElementById('action-view').addEventListener('click', () => {
      toast('Navigate to: /appointments');
      // window.location.href = '/appointments';
    });
    document.getElementById('action-profile').addEventListener('click', () => {
      toast('Navigate to: /profile');
      // window.location.href = '/profile';
    });
    document.getElementById('book-inline').addEventListener('click', () => {
      toast('Navigate to: /appointments/new');
      // window.location.href = '/appointments/new';
    });
    document.getElementById('view-all').addEventListener('click', (e) => {
      e.preventDefault();
      toast('Navigate to: /appointments');
      // window.location.href = '/appointments';
    });

    document.getElementById('logout').addEventListener('click', (e) => {
      e.preventDefault();
      const ok = confirm('Are you sure you want to logout?');
      if (!ok) return;
      toast('Logging out...');
      // fetch('/api/logout', { method: 'POST' }).then(() => window.location.href = '/signin');
    });

    // Example: populate stats from API
    async function loadStats() {
      // const res = await fetch('/api/stats');
      // const stats = await res.json();
      const stats = { total: 0, upcoming: 0, completed: 0, cancelled: 0 }; // demo
      document.getElementById('stat-total').textContent = String(stats.total).padStart(2, '0');
      document.getElementById('stat-upcoming').textContent = stats.upcoming;
      document.getElementById('stat-completed').textContent = String(stats.completed).padStart(2, '0');
      document.getElementById('stat-cancelled').textContent = stats.cancelled;

      if (stats.upcoming > 0) {
        document.getElementById('upcoming-empty').style.display = 'none';
        const list = document.getElementById('upcoming-list');
        list.style.display = 'grid';
        list.style.gap = '0.75rem';
        // render items...
      }
    }
    loadStats();
  </script>
</body>
</html>
