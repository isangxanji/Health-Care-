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
    :root{
      --bg:#f6f9ff;
      --sidebar:#0f1724;
      --accent:#2563eb;
      --muted:#64748b;
      --card:#ffffff;
      --border:#e6eefb;
      --radius:12px;
      --gap:1rem;
      --max-width:1200px;
      --shadow: 0 8px 24px rgba(12,20,40,0.06);
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background:var(--bg);
      color:#0f1724;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      line-height:1.45;
    }

    .wrap{
      max-width:var(--max-width);
      margin:2rem auto;
      padding:1rem;
      display:grid;
      grid-template-columns:260px 1fr 320px;
      gap:1.25rem;
      align-items:start;
    }

    /* Sidebar */
    .sidebar{
      background:linear-gradient(180deg,#071028 0%,var(--sidebar) 100%);
      color:#e6eefb;
      padding:1rem;
      border-radius:var(--radius);
      display:flex;
      flex-direction:column;
      gap:0.75rem;
      min-height:320px;
    }
    .brand{
      font-weight:700;
      font-size:1.05rem;
      letter-spacing:0.2px;
    }
    .brand small{display:block;color:var(--muted);font-weight:500;margin-top:0.25rem}

    nav.primary{display:flex;flex-direction:column;gap:0.35rem;margin-top:0.5rem}
    nav.primary a{
      display:flex;align-items:center;gap:0.6rem;padding:0.6rem;border-radius:10px;color:inherit;text-decoration:none;
      transition:background .12s;
    }
    nav.primary a:hover{background:rgba(255,255,255,0.03)}
    nav.primary a.active{background:rgba(37,99,235,0.16);color:#fff}

    .sidebar .footer-links{margin-top:auto;display:flex;flex-direction:column;gap:0.35rem}
    .sidebar a.small{font-size:0.95rem;color:var(--muted);text-decoration:none}
    .sidebar a.small:hover{color:#fff}

    /* Main content */
    .main{
      display:grid;
      gap:var(--gap);
    }
    .page-header{
      display:flex;
      justify-content:space-between;
      gap:1rem;
      align-items:flex-start;
    }
    .page-header h1{margin:0;font-size:1.25rem}
    .page-header p{margin:0;color:var(--muted)}

    .card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:var(--radius);
      padding:1rem;
      box-shadow:var(--shadow);
    }

    /* Search */
    .search {
      display:flex;
      gap:0.5rem;
      margin-top:0.5rem;
    }
    .search input[type="search"]{
      flex:1;
      padding:0.6rem 0.75rem;
      border-radius:10px;
      border:1px solid #dbeafe;
      font-size:0.95rem;
    }
    .search button{
      background:var(--accent);
      color:#fff;
      border:none;
      padding:0.6rem 0.9rem;
      border-radius:10px;
      cursor:pointer;
      font-weight:600;
    }

    /* FAQ layout */
    .faq-grid{
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:0.75rem;
      margin-top:0.75rem;
    }
    .faq-category h3{margin:0 0 0.5rem 0;font-size:1rem}
    details{
      background:#fbfdff;
      border-radius:10px;
      padding:0.6rem;
      margin-bottom:0.5rem;
      border:1px solid #eef6ff;
    }
    summary{
      cursor:pointer;
      list-style:none;
      font-weight:600;
      color:#0f1724;
      outline:none;
    }
    details[open] summary{color:var(--accent)}
    .answer{margin-top:0.5rem;color:var(--muted);font-size:0.95rem}

    /* Right column (contact) */
    .aside{
      display:flex;
      flex-direction:column;
      gap:0.75rem;
    }
    .contact .title{font-weight:700;margin-bottom:0.25rem}
    .contact a{color:var(--accent);text-decoration:none;font-weight:600}
    .hours{font-size:0.95rem;color:var(--muted);margin-top:0.5rem}
    .quick-links ul{list-style:none;padding:0;margin:0;display:grid;gap:0.4rem}
    .quick-links a{color:var(--accent);text-decoration:none}
    .muted{color:var(--muted)}

    /* Small helpers */
    .sr-only{position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden}

    /* Responsive */
    @media (max-width:1100px){
      .wrap{grid-template-columns:1fr 320px}
      .faq-grid{grid-template-columns:repeat(2,1fr)}
    }
    @media (max-width:820px){
      .wrap{grid-template-columns:1fr; padding:0.75rem}
      .faq-grid{grid-template-columns:1fr}
      .aside{order:3}
      .sidebar{order:1;position:sticky;top:0;z-index:20}
    }
  </style>
</head>
<body>
  <div class="wrap" role="application" aria-label="Help and support">
    <!-- Sidebar -->
    <aside class="sidebar" aria-label="Primary navigation">
      <div class="brand">HealthCare+<small>Appointment System</small></div>

      <nav class="primary" aria-label="Main menu">
        <a href="#" aria-current="false">🏠 Dashboard</a>
        <a href="#" aria-current="false">📅 Appointments</a>
        <a href="#" aria-current="false">📊 Analytics</a>
        <a href="#" aria-current="false">👤 Profile</a>
        <a href="#" class="active" aria-current="page">❓ Help</a>
      </nav>

      <div class="footer-links">
        <a class="small" href="#">🔒 Logout</a>
      </div>
    </aside>

    <!-- Main content -->
    <main class="main" id="content" tabindex="-1">
      <div class="page-header">
        <div>
          <h1>Help &amp; Support</h1>
          <p class="muted">How can we help you?</p>

          <div class="search" role="search" aria-label="Search FAQ">
            <input id="faqSearch" type="search" placeholder="Search our FAQ or contact support for assistance" aria-label="Search FAQs" />
            <button id="clearSearch" type="button" title="Clear search">Clear</button>
          </div>
        </div>

        <div style="min-width:220px">
          <div class="card" style="padding:0.6rem 0.9rem">
            <div style="font-weight:700">Need help now?</div>
            <div class="muted" style="font-size:0.95rem;margin-top:0.35rem">Contact support via email or phone — available during support hours.</div>
          </div>
        </div>
      </div>

      <!-- FAQ categories -->
      <section class="card" aria-labelledby="faq-heading">
        <h2 id="faq-heading" style="margin:0 0 0.75rem 0;font-size:1.05rem">Frequently asked questions</h2>

        <div class="faq-grid" id="faqGrid" role="list">
          <!-- Booking -->
          <div class="faq-category" role="listitem">
            <h3>Booking</h3>

            <details>
              <summary>How do I book an appointment?</summary>
              <div class="answer">Use the "Book New Appointment" action in your dashboard, choose a provider, date and time, then confirm the booking.</div>
            </details>

            <details>
              <summary>Can I book appointments for family members?</summary>
              <div class="answer">Yes. When booking, select the patient profile or add a family member's details to schedule on their behalf.</div>
            </details>

            <details>
              <summary>How far in advance can I book?</summary>
              <div class="answer">Booking windows vary by provider. Typically you can book several weeks in advance; check provider availability when scheduling.</div>
            </details>
          </div>

          <!-- Cancellation -->
          <div class="faq-category" role="listitem">
            <h3>Cancellation</h3>

            <details>
              <summary>How do I cancel an appointment?</summary>
              <div class="answer">Open the appointment in your dashboard and choose "Cancel". Follow the prompts to confirm the cancellation.</div>
            </details>

            <details>
              <summary>Is there a cancellation fee?</summary>
              <div class="answer">Cancellation policies differ by provider. Review the Cancellation Policy in Quick Links or contact support for details.</div>
            </details>
          </div>

          <!-- Technical -->
          <div class="faq-category" role="listitem">
            <h3>Technical</h3>

            <details>
              <summary>I forgot my password. What should I do?</summary>
              <div class="answer">Use the "Forgot Password" link on the sign-in page to request a password reset via email.</div>
            </details>

            <details>
              <summary>How do I update my profile information?</summary>
              <div class="answer">Go to "Profile" in the main menu, edit your details, and save changes. Contact support if you need help updating sensitive information.</div>
            </details>
          </div>
        </div>
      </section>

      <!-- Additional resources -->
      <section class="card" aria-labelledby="resources-heading">
        <h2 id="resources-heading" style="margin:0 0 0.75rem 0;font-size:1.05rem">More resources</h2>

        <div style="display:grid;gap:0.6rem">
          <div class="muted">If you can't find an answer, contact support or review our policies below.</div>

          <div class="quick-links">
            <strong>Quick Links</strong>
            <ul>
              <li><a href="/privacy">Privacy Policy</a></li>
              <li><a href="/terms">Terms of Service</a></li>
              <li><a href="/cancellation-policy">Cancellation Policy</a></li>
              <li><a href="/patient-rights">Patient Rights</a></li>
            </ul>
          </div>
        </div>
      </section>
    </main>

    <!-- Right column: Contact support -->
    <aside class="aside" aria-label="Contact support">
      <div class="card contact" role="region" aria-labelledby="contact-title">
        <div id="contact-title" class="title">Contact Support</div>
        <div class="muted">Email or call our support team for help</div>

        <div style="margin-top:0.6rem">
          <div><strong>Email</strong></div>
          <div><a href="mailto:support@healthcare.com">support@healthcare.com</a></div>
        </div>

        <div style="margin-top:0.6rem">
          <div><strong>Phone</strong></div>
          <div><a href="tel:+639537848286">09537848286</a></div>
        </div>

        <div class="hours" style="margin-top:0.8rem">
          <strong>Support Hours</strong>
          <div>Monday - Friday: 8:00 AM - 8:00 PM</div>
          <div>Saturday: 9:00 AM - 5:00 PM</div>
          <div>Sunday: Closed</div>
        </div>
      </div>

      <div class="card" role="region" aria-labelledby="help-links">
        <div id="help-links" style="font-weight:700;margin-bottom:0.5rem">Need immediate assistance</div>
        <div class="muted">Use the contact details above or visit the Help section in your dashboard for guided steps.</div>
      </div>
    </aside>
  </div>

  <script>
    // FAQ search/filter
    (function () {
      const searchInput = document.getElementById('faqSearch');
      const clearBtn = document.getElementById('clearSearch');
      const faqGrid = document.getElementById('faqGrid');

      function normalize(text) {
        return (text || '').toLowerCase().trim();
      }

      function filterFAQs(query) {
        const q = normalize(query);
        const categories = faqGrid.querySelectorAll('.faq-category');
        let anyVisible = false;

        categories.forEach(cat => {
          let catHas = false;
          const details = cat.querySelectorAll('details');
          details.forEach(d => {
            const summary = d.querySelector('summary').textContent;
            const answer = d.querySelector('.answer') ? d.querySelector('.answer').textContent : '';
            const text = normalize(summary + ' ' + answer);
            const match = q === '' || text.includes(q);
            d.style.display = match ? '' : 'none';
            if (match) catHas = true;
          });
          cat.style.display = catHas ? '' : 'none';
          if (catHas) anyVisible = true;
        });

        // If nothing matches, show a friendly message
        let noMatch = document.getElementById('noMatch');
        if (!anyVisible) {
          if (!noMatch) {
            noMatch = document.createElement('div');
            noMatch.id = 'noMatch';
            noMatch.className = 'muted';
            noMatch.style.marginTop = '0.75rem';
            noMatch.textContent = 'No results found. Try different keywords or contact support.';
            faqGrid.parentElement.appendChild(noMatch);
          }
        } else if (noMatch) {
          noMatch.remove();
        }
      }

      searchInput.addEventListener('input', (e) => filterFAQs(e.target.value));
      clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        filterFAQs('');
        searchInput.focus();
      });

      // Keyboard accessibility for summary elements
      document.querySelectorAll('summary').forEach(s => {
        s.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            const details = s.parentElement;
            details.open = !details.open;
          }
        });
      });
    })();
  </script>
</body>
</html>
