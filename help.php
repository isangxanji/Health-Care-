<?php
session_start();
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>HealthCare+ — Help & Support</title>
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
    /* Main Content */
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .content-area {
      display: flex;
      padding: 40px;
      gap: 40px;
    }

    .support-content {
      flex: 2;
    }

    .support-content h1 {
      font-size: 26px;
      margin-bottom: 10px;
      color: #2563eb;
    }

    .support-content p {
      font-size: 15px;
      color: #555;
      margin-bottom: 30px;
    }

    .search-bar input {
      width: 100%;
      padding: 12px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 30px;
    }

    .faq-category {
      margin-bottom: 30px;
    }

    .faq-category h3 {
      font-size: 18px;
      color: #2563eb;
      margin-bottom: 10px;
    }

    .faq-category ul {
      list-style: none;
      padding-left: 0;
    }

    .faq-category li {
      margin-bottom: 8px;
      font-size: 15px;
      cursor: pointer;
    }

    .faq-category li:hover {
      text-decoration: underline;
    }

    /* Right Panel */
    .support-info {
      flex: 1;
      background-color: #e0f2ff;
      padding: 30px;
      border-radius: 10px;
    }

    .support-info h3 {
      font-size: 18px;
      color: #2563eb;
      margin-bottom: 15px;
    }

    .support-info p {
      font-size: 14px;
      margin-bottom: 10px;
    }

    .quick-links ul {
      list-style: none;
      padding-left: 0;
    }

    .quick-links li {
      margin-bottom: 8px;
      font-size: 14px;
      color: #2563eb;
      cursor: pointer;
    }

    .quick-links li:hover {
      text-decoration: underline;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 20px;
      background-color: #1e3a8a;
      color: white;
      font-size: 14px;
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

    <!-- Main content -->
   <div class="main">

      <!-- Header Bar -->
      <div class="header-bar">
        <div class="header-left">Help & Support</div>
        <div class="header-center">
          <input type="text" placeholder="Search..." />
        </div>
        <div class="header-right">
          <div class="bell">🔔<span class="dot"></span></div>
          <img src="c:\Users\PRINCESS\Downloads\mingyu.jpg" alt="Profile" class="profile-pic" />
        </div>
      </div>

      <!-- Content Area -->
      <div class="content-area">
        <div class="support-content">
            <h1>How can we help you?</h1>
            <p>Search our FAQ or contact support for assistance</p>

            <div class="search-bar">
                <input type="text" placeholder="Search for answers..." />
        </div>


          <div class="faq-category">
            <h3>Booking</h3>
            <ul>
              <li>How do I book an appointment?</li>
              <li>Can I book appointments for family members?</li>
              <li>How far in advance can I book?</li>
            </ul>
          </div>

          <div class="faq-category">
            <h3>Cancellation</h3>
            <ul>
              <li>How do I cancel an appointment?</li>
              <li>Is there a cancellation fee?</li>
            </ul>
          </div>

          <div class="faq-category">
            <h3>Technical</h3>
            <ul>
              <li>I forgot my password. What should I do?</li>
              <li>How do I update my profile information?</li>
            </ul>
          </div>
        </div>

        <!-- Right Panel -->
        <div class="support-info">
          <h3>Contact Support</h3>
          <p>Can’t find what you’re looking for? Get in touch with our support team.</p>
          <p><strong>Email Us:</strong> support@healthcare.com</p>
          <p><strong>Call Us:</strong> 0957-584286</p>

          <h3>Support Hours</h3>
          <p>Monday - Friday: 8:00 AM - 4:00 PM</p>
          <p>Saturday: 8:00 AM - 5:00 PM</p>
          <p>Sunday: Closed</p>

          <h3>Quick Links</h3>
          <div class="quick-links">
            <ul>
              <li>Privacy Policy</li>
              <li>Terms of Service</li>
              <li>Cancellation Policy</li>
              <li>Patient Rights</li>
            </ul>
          </div>
        </div>
      </div> <!-- end content-area -->

      <!-- Footer -->
      <footer>
        © 2025 HealthCare+. All rights reserved.
      </footer>
    </div> <!-- end main -->
  </div> <!-- end container -->

</body>
</html>
