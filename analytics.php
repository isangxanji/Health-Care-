<?php
session_start();
include 'db.php'; // make sure $pdo is defined here

// Redirect if not logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['uid'];

// Initialize variables to avoid warnings
$userAppointments = 0;
$statusData = [];
$doctorData = [];
$avgDaily = 0;
$cancellationRate = 0;
$totalUsers = 0;
$totalAppointments = 0;

/* Total appointments for this user */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE user_id = ?");
$stmt->execute([$user_id]);
$userAppointments = (int)$stmt->fetchColumn();

/* Status breakdown */
$stmt = $pdo->prepare("SELECT status, COUNT(*) as count 
                       FROM appointments 
                       WHERE user_id = ? 
                       GROUP BY status");
$stmt->execute([$user_id]);
$statusData = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Appointments per doctor */
$stmt = $pdo->prepare("SELECT doctor, COUNT(*) as count 
                       FROM appointments 
                       WHERE user_id = ? 
                       GROUP BY doctor");
$stmt->execute([$user_id]);
$doctorData = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Average daily appointments */
$stmt = $pdo->prepare("
    SELECT AVG(daily_count)
    FROM (
        SELECT COUNT(*) as daily_count
        FROM appointments
        WHERE user_id = ?
        GROUP BY appointment_date
    ) as sub
");
$stmt->execute([$user_id]);
$avgDaily = (float)($stmt->fetchColumn() ?? 0);

/* Cancellation rate */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE user_id = ? AND status = 'Cancelled'");
$stmt->execute([$user_id]);
$cancelled = (int)$stmt->fetchColumn();
$cancellationRate = $userAppointments > 0 ? round(($cancelled / $userAppointments) * 100, 2) : 0;

/* System-wide totals */
$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalAppointments = (int)$pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HealthCare+ — Appointments</title>
<style>
    .material-symbols-rounded {
    font-variation-settings:
        'FILL' 0,
        'wght' 300,
        'GRAD' 0,
        'opsz' 24;
    }

    :root {
        --bg: linear-gradient(180deg, #4287e9e5 0%, #ffffffe5 100%);;
        --sidebar-bg: #ffffff;
        --sidebar-text: #0f172a;
        --sidebar-active-bg: #e5edff;
        --accent: #2563eb;
        --card: #ffffff;
        --text: #0f172a;
        --muted: #64748b;
        --border: #e2e8f0;
        --radius: 16px;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }
    
  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    background: var(--bg);
    color: var(--text);
  }

  .layout {
    display: grid;
    grid-template-columns: 240px 1fr;
    min-height: 100vh;
  }

  /* Sidebar */
  .sidebar {
    background: var(--sidebar-bg);
    border-right: 1px solid var(--border);
    padding: 1.5rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 500px;
  }

  .brand {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--accent);
  }

  nav {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
  }

  nav a {
    color: var(--sidebar-text);
    text-decoration: none;
    padding: 0.7rem 1rem;
    border-radius: 12px;
    font-weight: 500;
    transition: 0.2s;
    display: flex;
    align-items: center;
    gap: 0.6rem;
  }

  nav a:hover {
    background: #f4f7ff;
  }

  nav a.active {
    background: var(--sidebar-active-bg);
    color: var(--accent);
    font-weight: 600;
  }

  /* Main layout */
  .main {
    padding: 2rem 2.5rem;
    display: grid;
    gap: 1.5rem;
  }

  .topbar {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 1rem;
  }

    .topbar-wrapper {
        height: 80px;
        margin-left: 190px;
        background: #ffffff;
        padding: 1rem 1.5rem;
        border-radius: 0px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 4px;
        }



  .topbar input[type="search"] {
    padding: 0.6rem 0.75rem;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.95rem;
    width: 280px;
   
    box-shadow: var(--shadow);
    padding-left: 48px;
  }

  .icons {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .icon {
    cursor: pointer;
    font-size: 1.3rem;
    transition: 0.2s;
  }

  .icon:hover {
    transform: scale(1.1);
    color: var(--accent);
  }

  /* Header */
  .header h1 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
  }

  .header p {
    margin: 0;
    margin-top: 0.2rem;
    color: var(--muted);
    font-size: 0.95rem;
  }

  /* Cards & layout blocks */
  .chart,
  .tile {
    background: var(--card);
    border-radius: 5px;
    padding: 1.4rem;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
  }

  .chart {
    height: 300px;
    display: grid;
    place-content: center;
    color: var(--muted);
    font-size: 0.95rem;
    margin-left: 18px;
    position: relative;
    padding: 1.6rem;
    background: var(--card);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
  }
  .chart-title {position: absolute;
    top: 16px;
    left: 20px;
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text);

}

  .grid {
    display: grid;
    gap: 1.5rem;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  }

  .status-list {
    display: grid;
    gap: 1rem;
  }

  .status-item {
    display: flex;
    flex-direction: column;
    color: var(--text);
    font-weight: 500;
    gap: 0.3rem;
  }

  .status-item span:last-child {
    color: transparent;
    display: block;
    width: 100%;
    height: 10px;
    background: #e5e7eb;
    border-radius: 4px;
  }

 .departments ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: 0.5rem;
}

.departments li {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  padding: 0.6rem 1rem;
  background: #ffffff;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  color: #1e293b;
  position: relative;
}

/* Numbered circle */
.departments li::before {
  counter-increment: dept;
  content: counter(dept);
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #dbeafe;        /* Light Blue */
  color: #1e3a8a;             /* Darker blue text */
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
}

/* Reset the counter */
.departments ul {
  counter-reset: dept;
}

  /* Bottom tiles */
  .tiles {
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  }

  @media (max-width: 820px) {
    .layout {
      grid-template-columns: 1fr;
    }

    .tiles {
      grid-template-columns: 1fr;
    }
  }
    
  
.search-box {
  position: relative;
  display: inline-block;
}

/* Search Input */
.search-box input {
  width: 380px;          /* You can adjust the width */
  height: 42px;          /* Adjust height if needed */
  padding-left: 48px;    /* IMPORTANT: space for icon */
  border: 1px solid var(--border);
  border-radius: 14px;   /* Adjust radius */
  font-size: 1rem;
  background: #fff;
}

/* Icon inside input */
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


    <!-- Main -->
    <main class="main">
      <div class="topbar-wrapper">
  <div class="header">
    <h1>Analytics</h1>
  </div>

  <div class="topbar">
    <div class="search-box">
        <span class="material-symbols-rounded search-icon">search</span>
        <input type="search" placeholder="Search..." />
    </div>


    <div class="icons">
      <div class="material-symbols-rounded">notifications</div>
      <div class="material-symbols-rounded">account_circle</div>
    </div>
  </div>
</div>
<strong style="color:#0f172a; font-size: 20px; margin-top: 10px;">Analytics Dashboard</strong>
<p style="color: var(--muted); margin-top: -20px;">Track appointment trends and insights</p>

    <section class="chart">
        <h3 class="chart-title">Monthly Appointments</h3>
    </section>

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
          <h4>Average Daily Appointments</h4>
          <p style="color: var(--muted);"><?php echo $avgDaily; ?></p>
        </div>
        <div class="tile">
          <h4>Patient Satisfaction</h4>
          <p style="color: var(--muted);"><?php echo $avgDaily; ?></p>
        </div>
        <div class="tile">
          <h4>Cancellation Rate</h4>
          <p style="color: var(--muted);"><?php echo $cancellationRate; ?>%</p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
