<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HealthCare+ | Online Doctor Appointments | Book Healthcare</title>
  <meta name="description" content="Book appointments with top healthcare professionals in seconds. HealthCare+ offers 24/7 access to expert doctors, easy booking, and secure health management online.">
  <meta name="keywords" content="healthcare, doctor appointments, online booking, medical consultation, health management, telemedicine">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="HealthCare+ | Online Doctor Appointments | Book Healthcare">
  <meta property="og:description" content="Book appointments with top healthcare professionals in seconds. Manage your health journey with ease and confidence.">
  
  <style>
    /* Reset and base styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.6;
      color: #111827;
      background: linear-gradient(180deg, #b6d5ffe5 0%, #ffffffe5 100%);
    }
    
    /* Layout components */
    .main-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
      min-height: 100vh;
    }
    
    .content-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
      flex: 1;
    }
    
    .section {
      width: 100%;
      padding: 32px 16px;
    }
    
    /* Header */
    .header {
      background-color: #ffffff;
      width: 100%;
      padding: 12px 18px;
    }
    
    .header-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      margin-top: 8px;
    }
    
    .logo {
      width: 120px;
      height: auto;
      margin-top: 8px;
    }
    
    .header-buttons {
      display: none;
      gap: 16px;
      align-items: center;
    }
    
    .btn-login {
      background-color: #ffffff;
      color: #2563eb;
      border: 2px solid #1d5ff1;
      border-radius: 14px;
      padding: 12px 20px;
      font-size: 14px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .btn-get-started {
      background-color: #2563eb;
      color: #ffffff;
      border: none;
      border-radius: 14px;
      padding: 12px 18px;
      font-size: 14px;
      font-weight: 400;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .btn-login:hover {
      background-color: #2563eb;
      color: #ffffff;
      transform: translateY(-2px);
    }
    
    .btn-get-started:hover {
      background-color: #1d4ed8;
      transform: translateY(-2px);
    }
    
    /* Hero Section */
    .hero-section {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
      padding: 32px 16px;
      margin-top: 32px;
    }
    
    .hero-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
      gap: 32px;
    }
    
    .hero-text {
      display: flex;
      flex-direction: column;
      gap: 22px;
      align-items: center;
      text-align: center;
      width: 100%;
    }
    
    .hero-title {
      font-size: 32px;
      font-weight: 700;
      line-height: 1.1;
      color: #111827;
      text-align: center;
    }
    
    .hero-description {
      font-size: 16px;
      font-weight: 400;
      line-height: 1.4;
      color: #4b5563;
      text-align: center;
      max-width: 90%;
    }
    
    .hero-cta {
      background-color: #2563eb;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      padding: 12px 20px;
      font-size: 14px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
      margin-top: 16px;
    }
    
    .hero-cta:hover {
      background-color: #1d4ed8;
      transform: translateY(-2px);
    }
    
    .hero-image-container {
      position: relative;
      width: 280px;
      height: 300px;
      margin-bottom: 6px;
    }
    
    .hero-image-stack {
      position: relative;
      width: 100%;
      height: 100%;
    }
    
    .doctor-background {
      position: absolute;
      top: 40px;
      left: 0;
      width: 100%;
      height: 260px;
      background-color: #6da0e4;
      border-radius: 180px;
      border: 1px solid #2563eb;
      padding: 8px;
    }
    
    .doctor-image {
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 180px;
      height: 300px;
      object-fit: cover;
      border-radius: 38px;
      z-index: 2;
    }
    
    /* Features Section */
    .features-section {
      padding: 64px 16px;
      margin-top: 64px;
    }
    
    .features-content {
      display: flex;
      flex-direction: column;
      gap: 44px;
      align-items: center;
      width: 100%;
    }
    
    .section-title {
      font-size: 20px;
      font-weight: 700;
      line-height: 1.2;
      color: #111827;
      text-align: center;
    }
    
    .features-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 32px;
      width: 100%;
      max-width: 800px;
    }
    
    .feature-item {
      display: flex;
      flex-direction: column;
      gap: 14px;
      align-items: center;
      text-align: center;
    }
    
    .feature-icon {
      width: 64px;
      height: 64px;
      border-radius: 32px;
      padding: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .icon-blue { background-color: #dbeafe; }
    .icon-yellow { background-color: #fef9c3; }
    .icon-green { background-color: #dcfce7; }
    .icon-purple { background-color: #f3e8ff; }
    
    .feature-title {
      font-size: 16px;
      font-weight: 600;
      color: #000000;
    }
    
    .feature-description {
      font-size: 12px;
      font-weight: 400;
      color: #4b5563;
      line-height: 1.3;
    }
    
    /* How It Works Section */
    .how-it-works-section {
      padding: 64px 16px;
      margin-top: 64px;
    }
    
    .how-it-works-content {
      display: flex;
      flex-direction: column;
      gap: 40px;
      align-items: center;
      width: 100%;
      max-width: 900px;
      margin: 0 auto;
    }
    
    .steps-container {
      display: flex;
      flex-direction: column;
      gap: 32px;
      width: 100%;
    }
    
    .step-item {
      display: flex;
      flex-direction: column;
      gap: 14px;
      align-items: center;
      text-align: center;
      padding: 0 14px;
    }
    
    .step-number {
      width: 48px;
      height: 48px;
      background-color: #2563eb;
      color: #ffffff;
      border-radius: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
      font-weight: 700;
    }
    
    .step-title {
      font-size: 16px;
      font-weight: 600;
      color: #000000;
    }
    
    .step-description {
      font-size: 12px;
      font-weight: 400;
      color: #4b5563;
      line-height: 1.3;
    }
    
    /* CTA Section */
    .cta-section {
      padding: 64px 16px;
      margin-top: 64px;
      text-align: center;
    }
    
    .cta-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
      max-width: 600px;
      margin: 0 auto;
    }
    
    .cta-title {
      font-size: 20px;
      font-weight: 700;
      color: #111827;
      margin-bottom: 8px;
    }
    
    .cta-description {
      font-size: 15px;
      font-weight: 400;
      color: #4b5563;
      line-height: 1.2;
      margin-bottom: 16px;
    }
    
    .cta-button {
      background-color: #2563eb;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      padding: 14px 30px;
      font-size: 15px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .cta-button:hover {
      background-color: #1d4ed8;
      transform: translateY(-2px);
    }
    
    /* Footer */
    .footer {
      background-color: #111827;
      width: 100%;
      padding: 22px;
      margin-top: 64px;
      text-align: center;
    }
    
    .footer-text {
      font-size: 13px;
      font-weight: 400;
      color: #ffffff;
      line-height: 1.3;
    }
    
    /* Responsive media queries */
    @media (min-width: 640px) {
      .hero-title {
        font-size: 42px;
      }
      
      .hero-description {
        font-size: 18px;
        max-width: 80%;
      }
      
      .hero-image-container {
        width: 320px;
        height: 340px;
      }
      
      .doctor-image {
        width: 200px;
        height: 340px;
      }
      
      .section-title {
        font-size: 22px;
      }
      
      .features-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
      }
      
      .steps-container {
        flex-direction: row;
        justify-content: center;
        gap: 24px;
      }
      
      .step-item {
        width: 200px;
      }
    }
    
    @media (min-width: 768px) {
      .header-buttons {
        display: flex;
      }
      
      .btn-login {
        padding: 20px 30px;
        font-size: 16px;
      }
      
      .btn-get-started {
        padding: 20px 26px;
        font-size: 16px;
      }
      
      .logo {
        width: 148px;
      }
      
      .hero-content {
        flex-direction: row;
        align-items: center;
        gap: 64px;
      }
      
      .hero-text {
        align-items: flex-start;
        text-align: left;
        width: 60%;
      }
      
      .hero-title {
        font-size: 52px;
        text-align: left;
      }
      
      .hero-description {
        text-align: left;
        max-width: 100%;
      }
      
      .hero-image-container {
        width: 388px;
        height: 396px;
      }
      
      .doctor-image {
        width: 242px;
        height: 388px;
      }
      
      .section-title {
        font-size: 25px;
      }
      
      .feature-title {
        font-size: 17px;
      }
      
      .feature-description {
        font-size: 13px;
      }
      
      .step-title {
        font-size: 17px;
      }
      
      .step-description {
        font-size: 13px;
      }
      
      .cta-title {
        font-size: 25px;
      }
      
      .cta-description {
        font-size: 17px;
      }
    }
    
    @media (min-width: 1024px) {
      .section {
        padding: 64px 56px;
      }
      
      .hero-title {
        font-size: 64px;
        line-height: 64px;
      }
      
      .hero-description {
        font-size: 20px;
        line-height: 28px;
      }
      
      .features-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 150px;
      }
      
      .steps-container {
        gap: 32px;
      }
      
      .step-item {
        width: 246px;
      }
    }
    
    @media (min-width: 1280px) {
      .content-wrapper {
        max-width: 1440px;
        margin: 0 auto;
      }
    }
  </style>
  
  <script type="module" async src="https://static.rocket.new/rocket-web.js?_cfg=https%3A%2F%2Fhealthcare6192back.builtwithrocket.new&_be=https%3A%2F%2Fapplication.rocket.new&_v=0.1.10"></script>
  <script type="module" defer src="https://static.rocket.new/rocket-shot.js?v=0.0.1"></script>
  </head>
<body>
  <div class="main-container">
    <div class="content-wrapper">
      <header class="header">
        <div class="header-content">
          <img src="logo.png" alt="HealthCare+ Logo" class="logo">
          <div class="header-buttons">
            <a href="login.php" class="btn-login">Login</a>
            <a href="register.php" class="btn-get-started">Get Started</a>
          </div>
        </div>
      </header>

      <main>
        <section class="hero-section">
          <div class="hero-content">
            <div class="hero-text">
              <h1 class="hero-title">Your Health, Our Priority</h1>
              <p class="hero-description">Book appointments with top healthcare professionals in seconds. Manage your health journey with ease and confidence.</p>
              <a href="login.php" class="hero-cta">Book Your Appointment Today</a>
            </div>
            <div class="hero-image-container">
              <div class="hero-image-stack">
                <div class="doctor-background"></div>
                <img src="woman.png" alt="Professional healthcare doctor ready to help patients" class="doctor-image">
              </div>
            </div>
          </div>
        </section>

        <section class="features-section">
          <div class="features-content">
            <h2 class="section-title">Why Choose HealthCare+?</h2>
            <div class="features-grid">
              <div class="feature-item">
                <div class="feature-icon icon-blue">
                  <img src="../assets/images/img_frame.svg" alt="Easy booking icon" width="32" height="32">
                </div>
                <h3 class="feature-title">Easy Booking</h3>
                <p class="feature-description">Schedule appointments in just a few clicks</p>
              </div>
              <div class="feature-item">
                <div class="feature-icon icon-green">
                  <img src="../assets/images/img_frame_green_700.svg" alt="24/7 access icon" width="32" height="32">
                </div>
                <h3 class="feature-title">24/7 Access</h3>
                <p class="feature-description">Book appointments anytime, anywhere</p>
              </div>
              <div class="feature-item">
                <div class="feature-icon icon-yellow">
                  <img src="../assets/images/img_frame_orange_700.svg" alt="Expert doctors icon" width="32" height="32">
                </div>
                <h3 class="feature-title">Expert Doctors</h3>
                <p class="feature-description">Access to qualified healthcare professionals</p>
              </div>
              <div class="feature-item">
                <div class="feature-icon icon-purple">
                  <img src="../assets/images/img_frame_deep_purple_a200.svg" alt="Secure and private icon" width="32" height="32">
                </div>
                <h3 class="feature-title">Secure & Private</h3>
                <p class="feature-description">Your health data is safe with us</p>
              </div>
            </div>
          </div>
        </section>

        <section class="how-it-works-section">
          <div class="how-it-works-content">
            <h2 class="section-title">How It Works</h2>
            <div class="steps-container">
              <div class="step-item">
                <div class="step-number">1</div>
                <h3 class="step-title">Create Account</h3>
                <p class="step-description">Sign up with your email and basic information</p>
              </div>
              <div class="step-item">
                <div class="step-number">2</div>
                <h3 class="step-title">Choose Doctor</h3>
                <p class="step-description">Select your preferred doctor and time slot</p>
              </div>
              <div class="step-item">
                <div class="step-number">3</div>
                <h3 class="step-title">Get Confirmation</h3>
                <p class="step-description">Receive instant confirmation and reminders</p>
              </div>
            </div>
          </div>
        </section>

        <section class="cta-section">
          <div class="cta-content">
            <h2 class="cta-title">Ready to Get Started?</h2>
            <p class="cta-description">Join thousands of satisfied patients managing their healthcare online</p>
            <a href="register.php" class="cta-button">Create Free Account</a>
          </div>
        </section>
      </main>

      <footer class="footer">
        <p class="footer-text">© 2025 HealthCare+. All rights reserved.</p>
      </footer>
    </div>
  </div>
</body>
</html>