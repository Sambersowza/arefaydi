<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact | RFID Attendance System</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('images/room.jpg') no-repeat center center fixed;
      background-size: cover;
      padding: 0;
      display: flex;
      flex-direction: column;
      color: white;
      position: relative;
      overflow-x: hidden;
    }

    /* Animated background elements */
    body::before {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
      z-index: -1;
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Header / Menu Bar */
    .header-menu {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
      background: rgba(41, 128, 185, 0.8);
      color: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .header-menu h1 {
      font-size: 24px;
    }

    .nav-links {
      display: flex;
    }

    .nav-link {
      padding: 12px 18px;
      color: white;
      text-decoration: none;
      font-size: 16px;
      margin-left: 20px;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
      border-radius: 6px;
    }

    .nav-link:hover {
      background-color: #1f6fa2;
      transform: translateY(-2px);
    }

    .nav-link img {
      width: 20px;
      height: 20px;
      margin-right: 8px;
    }

    /* Main Content */
    .main-content {
      padding: 40px;
      overflow-y: auto;
      position: relative;
      z-index: 1;
      margin-top: 0;
    }

    .contact-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      display: flex;
      max-width: 1300px; /* Increased from 1100px to 1300px */
      overflow: hidden;
      flex-wrap: wrap;
      margin: 0 auto 20px auto; /* Changed from 50px to 20px for bottom margin */
      position: relative;
      border: 2px solid white;
      animation: float 4s ease-in-out infinite;
    }

    .contact-image {
      flex: 1 1 500px; /* Increased from 400px to 500px */
      min-height: 400px; /* Increased from 350px to 400px */
      position: relative;
      overflow: hidden;
      border: 2px solid white; /* White outline for the picture container */
      border-radius: 15px;
      margin: 10px;
    }

    .contact-image video {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
      border: 4px solid black; /* Thick black outline for the video */
      border-radius: 10px; /* Slightly smaller radius to fit within parent */
    }

    .contact-image:hover img {
      transform: scale(1.05);
    }

    .contact-text {
      flex: 2 1 500px;
      padding: 30px;
      color: #333;
    }

    .contact-text h2 {
      margin-bottom: 20px;
      font-size: 28px;
      color: #2c3e50;
      position: relative;
      padding-bottom: 10px;
    }

    .contact-text h2::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 3px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 3px;
    }

    .contact-text p {
      line-height: 1.6;
      font-size: 16px;
      margin-bottom: 10px;
    }

    .contact-text ul {
      margin-bottom: 15px;
      padding-left: 20px;
    }

    .contact-text li {
      margin-bottom: 8px;
    }

    /* Contact Info Cards */
    .contact-cards {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 30px;
    }

    .contact-card {
      flex: 1;
      min-width: 200px;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      text-align: center;
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.3);
      position: relative;
      overflow: hidden;
      cursor: default;
    }

    .contact-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: 0.5s;
    }

    .contact-card:hover::before {
      left: 100%;
    }

    .contact-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .contact-card.email-card {
      background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
      color: white;
    }

    .contact-card.phone-card {
      background: linear-gradient(135deg, #27ae60 0%, #219653 100%);
      color: white;
    }

    .contact-card.location-card {
      background: white;
      color: black;
      padding: 20px;
      height: 200px;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      position: relative;
      border: 2px solid black;
    }
    
    /* GitHub Link Boxes */
    .github-container {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      margin-top: 30px;
      width: 100%;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .github-box {
      flex: 1;
      background: white;
      color: #333;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      text-align: center;
      transition: all 0.3s ease;
      border: 1px solid rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
      cursor: pointer;
      min-height: 150px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    
    .github-box::before {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: 0.5s;
    }
    
    .github-box:hover::before {
      left: 100%;
    }
    
    .github-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .github-box img {
      width: 50px;
      height: 50px;
      margin-bottom: 15px;
      background: transparent;
    }
    
    .github-box h3 {
      font-size: 18px;
      margin-bottom: 10px;
      color: #2c3e50;
    }
    
    .github-box p {
      font-size: 14px;
      color: #7f8c8d;
    }
    
    /* Specific styling for GitHub containers with background images */
    .github-box.sam-box {
      background: url('images/samgit.png') no-repeat center center;
      background-size: cover;
      color: white;
    }
    
    .github-box.sam-box h3,
    .github-box.sam-box p {
      color: white;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
    }
    
    .github-box.bas-box {
      background: url('images/bascodegit.png') no-repeat center center;
      background-size: cover;
      color: white;
    }
    
    .github-box.bas-box h3,
    .github-box.bas-box p {
      color: white;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
    }
    
    .github-box.nashy-box {
      background: url('images/nashgit.png') no-repeat center center;
      background-size: cover;
      color: white;
    }
    
    .github-box.nashy-box h3,
    .github-box.nashy-box p {
      color: white;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
    }

    .contact-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      display: flex;
      max-width: 1300px; /* Increased from 1100px to 1300px */
      overflow: hidden;
      flex-wrap: wrap;
      margin: 0 auto 50px auto;
      position: relative;
      border: 2px solid white;
      animation: float 4s ease-in-out infinite;
    }

    .contact-card.location-card .map-background {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border-radius: 10px;
      overflow: hidden;
      z-index: 1;
    }

    .contact-card.location-card .map-background iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    .contact-card.location-card .content {
      position: relative;
      z-index: 2;
      background: rgba(255, 255, 255, 0.7);
      padding: 15px;
      border-radius: 10px;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .contact-card.location-card img {
      width: 50px;
      height: 50px;
      margin-bottom: 10px;
      position: relative;
      z-index: 2;
    }

    .contact-card.location-card h3,
    .contact-card.location-card p {
      color: black;
      position: relative;
      z-index: 2;
    }

    .contact-card:not(.location-card) img {
      width: 50px;
      height: 50px;
      margin-bottom: 15px;
    }

    .contact-card h3 {
      font-size: 18px;
      margin-bottom: 10px;
      color: #2c3e50;
    }

    .contact-card p {
      font-size: 14px;
      color: #7f8c8d;
    }

    .contact-card.email-card h3,
    .contact-card.email-card p,
    .contact-card.phone-card h3,
    .contact-card.phone-card p {
      color: white;
    }

    /* Meet The Team */
    .team-section {
      padding: 30px 30px;
      text-align: center;
    }

    .team-section h2 {
      font-size: 36px;
      margin-bottom: 30px;
      color: #fff;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
      position: relative;
      display: inline-block;
    }

    .team-section h2::after {
      content: "";
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 2px;
    }

    .team-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .team-member {
      border-radius: 12px;
      padding: 15px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.3);
      background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
      color: white;
      height: 300px;
      perspective: 1000px;
    }

    .team-member::before {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: 0.5s;
    }

    .team-member:hover::before {
      left: 100%;
    }

    .team-member:hover {
      transform: translateY(-10px) scale(1.03);
      box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    /* Flip card container */
    .flip-card {
      background-color: transparent;
      width: 100%;
      height: 100%;
      perspective: 1000px;
      cursor: pointer;
    }

    .flip-card-inner {
      position: relative;
      width: 100%;
      height: 100%;
      text-align: center;
      transition: transform 0.8s;
      transform-style: preserve-3d;
    }

    .flip-card.flipped .flip-card-inner {
      transform: rotateY(180deg);
    }

    .flip-card-front, .flip-card-back {
      position: absolute;
      width: 100%;
      height: 100%;
      -webkit-backface-visibility: hidden;
      backface-visibility: hidden;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      border-radius: 12px;
      padding: 15px;
    }

    .flip-card-front {
      background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
    }

    .flip-card-back {
      background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
      transform: rotateY(180deg);
      color: white;
    }

    .team-member img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      background: #f4f4f4;
      border-radius: 10px;
      margin-bottom: 10px;
      transition: transform 0.3s ease;
    }

    .flip-card-back img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      background: #f4f4f4;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    .team-member:hover img {
      transform: scale(1.05);
    }

    .team-member .name {
      font-weight: 600;
      font-size: 16px;
      margin-bottom: 5px;
    }

    .team-member .link a {
      font-size: 14px;
      color: white;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .team-member .link a:hover {
      color: #1f6fa2;
      text-decoration: underline;
    }

    /* Copyright Box */
    .copyright-box {
      background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
      color: white;
      text-align: center;
      padding: 20px;
      border-radius: 10px;
      margin: 0 auto; /* Changed from margin: 20px auto 0 to remove bottom margin */
      max-width: 1300px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .copyright-box p {
      margin: 0;
      font-size: 14px;
      line-height: 1.6;
    }

    /* Floating animation keyframes */
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }

    .floating {
      animation: float 4s ease-in-out infinite;
    }

    /* Pulse animation for team members */
    @keyframes pulse {
      0% { box-shadow: 0 6px 12px rgba(0,0,0,0.1); }
      50% { box-shadow: 0 6px 20px rgba(0,0,0,0.2); }
      100% { box-shadow: 0 6px 12px rgba(0,0,0,0.1); }
    }

    .team-member.pulse {
      animation: pulse 2s infinite;
    }

    /* Responsive */
    @media (max-width: 1024px) {
      .team-grid {
        grid-template-columns: repeat(3, 1fr);
      }
      
      .contact-container {
        max-width: 900px;
      }
    }

    @media (max-width: 768px) {
      .team-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .contact-container {
        flex-direction: column;
        max-width: 700px;
      }
      
      .contact-image {
        min-height: 250px;
      }
      
      .header-menu {
        flex-direction: column;
        gap: 15px;
      }
      
      .nav-links {
        flex-wrap: wrap;
        justify-content: center;
      }
      
      .nav-link {
        margin: 5px;
      }
    }

    @media (max-width: 480px) {
      .team-grid {
        grid-template-columns: 1fr;
      }
      
      .main-content {
        padding: 20px;
      }
      
      .contact-text {
        padding: 20px;
      }
      
      .team-section {
        padding: 20px 15px;
      }
      
      .contact-container {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

  <!-- Header / Menu Bar -->
  <div class="header-menu">
    <h1>RFID Attendance System</h1>
    <div class="nav-links">
      <a class="nav-link" href="index.php"><img src="images/homm.png" alt="Home"> Home</a>
      <a class="nav-link" href="about.php"><img src="images/bout.png" alt="About"> About</a>
      <a class="nav-link" href="contact.php"><img src="images/contact.png" alt="Contact"> Contact</a>
      <a class="nav-link" href="time.php"><img src="images/time.png" alt="Time"> Time In / Out</a>
      <a class="nav-link" href="admin.php"><img src="images/admin.png" alt="Admin"> Admin</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Contact Content -->
    <div class="contact-container">
      <div class="contact-image">
        <video autoplay loop muted>
          <source src="images/logo.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
      <div class="contact-text">
        <h2>Contact Us</h2>
        <p>If you have any questions or need support, feel free to reach out to us! You can contact us through the following ways:</p>    
        <ul>
          <li><strong>Email:</strong> duremdeslester4@gmail.com</li>
          <li><strong>Phone:</strong> +63 962 813 7889</li>
          <li><strong>Address:</strong> 1571 Triangle Bldg., Doa Soledad Avenue, Better Living Subd, Parañaque, 1709 Metro Manila</li>
        </ul>
        <p>We are here to help and look forward to hearing from you.</p>
        
        <div class="contact-cards">
          <div class="contact-card email-card">
            <img src="images/email.png" alt="Email">
            <h3>Email</h3>
            <p>duremdeslester4@gmail.com</p>
          </div>
          <div class="contact-card phone-card">
            <img src="images/phone.png" alt="Phone">
            <h3>Phone</h3>
            <p>+63 962 813 7889</p>
          </div>
          <div class="contact-card location-card" onclick="window.open('https://www.google.com/maps/place/Asian+Institute+of+Computer+Studies+Bicutan/@14.4853621,121.0381363,17z/data=!3m1!4b1!4m6!3m5!1s0x3397cf04650e6f59:0x893dd455108479ae!8m2!3d14.4853621!4d121.0407112!16s%2Fg%2F1tk9n92n?entry=ttu&g_ep=EgoyMDI1MTAwNy4wIKXMDSoASAFQAw%3D%3D', '_blank')">
            <div class="map-background">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.774579474143!2d121.0381363!3d14.4853621!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397cf04650e6f59%3A0x893dd455108479ae!2sAsian%20Institute%20of%20Computer%20Studies%20Bicutan!5e0!3m2!1sen!2sph!4v1697030442123!5m2!1sen!2sph" allowfullscreen="" loading="lazy"></iframe>
            </div>
            <div class="content">
              <img src="images/location.png" alt="Location">
              <h3>Location</h3>
              <p>Parañaque, Metro Manila</p>
            </div>
          </div>
        </div>
        
        <!-- GitHub Links Section -->
        <div class="github-container">
          <div class="github-box sam-box" onclick="window.open('https://github.com/Sambersowza', '_blank')">
            <img src="images/github.png" alt="GitHub">
            <h3>Sambersowza</h3>
            <p>GitHub Profile</p>
          </div>
          <div class="github-box bas-box" onclick="window.open('https://github.com/Bascode-040612V1', '_blank')">
            <img src="images/github.png" alt="GitHub">
            <h3>Bascode-040612V1</h3>
            <p>GitHub Profile</p>
          </div>
          <div class="github-box nashy-box" onclick="window.open('https://github.com/Nashy-dot', '_blank')">
            <img src="images/github.png" alt="GitHub">
            <h3>Nashy-dot</h3>
            <p>GitHub Profile</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Meet The Team Section -->
    <div class="team-section">
      <h2>Meet The Team</h2>
      <div class="team-grid">

        <!-- Team Member 1 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/1.jpg" alt="Lester Sam B. Duremdes" class="flip-trigger">
                <div class="name">Lester Sam B. Duremdes</div>
                <div class="link"><a href="https://www.facebook.com/lester.sam.duremdes" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb1.jpg" alt="Lester Sam B. Duremdes" class="flip-trigger">
                <div class="name">Lester Sam B. Duremdes</div>
                <p>Team Leader</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 2 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/2.jpg" alt="Joshua P. Basco" class="flip-trigger">
                <div class="name">Joshua P. Basco</div>
                <div class="link"><a href="https://www.facebook.com/joshua.pavia.basco" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb2.jpg" alt="Joshua P. Basco" class="flip-trigger">
                <div class="name">Joshua P. Basco</div>
                <p>Developer</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 3 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/3.jpg" alt="John Lloyd Figuracion" class="flip-trigger">
                <div class="name">John Lloyd Figuracion</div>
                <div class="link"><a href="https://www.facebook.com/johnlloyd.figuracion.9" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb3.jpg" alt="John Lloyd Figuracion" class="flip-trigger">
                <div class="name">John Lloyd Figuracion</div>
                <p>Designer</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 4 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/4.jpg" alt="Oliver Burro" class="flip-trigger">
                <div class="name">Oliver Burro</div>
                <div class="link"><a href="https://www.facebook.com/oliver.burro.3" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb4.jpg" alt="Oliver Burro" class="flip-trigger">
                <div class="name">Oliver Burro</div>
                <p>Tester</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 5 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/5.jpg" alt="Nashria M. Macalatas" class="flip-trigger">
                <div class="name">Nashria M. Macalatas</div>
                <div class="link"><a href="https://www.facebook.com/jesse.latee" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb5.jpg" alt="Nashria M. Macalatas" class="flip-trigger">
                <div class="name">Nashria M. Macalatas</div>
                <p>Analyst</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 6 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/6.jpg" alt="Mike Obumani" class="flip-trigger">
                <div class="name">Mike Obumani</div>
                <div class="link"><a href="https://www.facebook.com/mike.obumani" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb6.jpg" alt="Mike Obumani" class="flip-trigger">
                <div class="name">Mike Obumani</div>
                <p>Coordinator</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 7 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/7.jpg" alt="Rolly Cagampang" class="flip-trigger">
                <div class="name">Rolly Cagampang</div>
                <div class="link"><a href="https://www.facebook.com/ff0010" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb7.jpg" alt="Rolly Cagampang" class="flip-trigger">
                <div class="name">Rolly Cagampang</div>
                <p>Documenter</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 8 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/8.jpg" alt="Mark Aldren Abonales" class="flip-trigger">
                <div class="name">Mark Aldren Abonales</div>
                <div class="link"><a href="https://www.facebook.com/Markaldren04" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb8.jpg" alt="Mark Aldren Abonales" class="flip-trigger">
                <div class="name">Mark Aldren Abonales</div>
                <p>Researcher</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 9 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/9.jpg" alt="Kathleen B. De Guzman" class="flip-trigger">
                <div class="name">Kathleen B. De Guzman</div>
                <div class="link"><a href="https://www.facebook.com/kathleen.deguzman.7737" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb9.jpg" alt="Kathleen B. De Guzman" class="flip-trigger">
                <div class="name">Kathleen B. De Guzman</div>
                <p>Planner</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 10 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/10.jpg" alt="Edgar Rivas Jr." class="flip-trigger">
                <div class="name">Edgar Rivas Jr.</div>
                <div class="link"><a href="https://www.facebook.com/lala.bye.332" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb10.jpg" alt="Edgar Rivas Jr." class="flip-trigger">
                <div class="name">Edgar Rivas Jr.</div>
                <p>Support</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 11 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/11.jpg" alt="Edrian E. Andam" class="flip-trigger">
                <div class="name">Edrian E. Andam</div>
                <div class="link"><a href="https://www.facebook.com/edrian.andam.5" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb11.jpg" alt="Edrian E. Andam" class="flip-trigger">
                <div class="name">Edrian E. Andam</div>
                <p>Developer</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 12 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/12.jpg" alt="Joshua O. Matia-ong" class="flip-trigger">
                <div class="name">Joshua O. Matia-ong</div>
                <div class="link"><a href="https://www.facebook.com/joshua.mattiaong" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb12.jpg" alt="Joshua O. Matia-ong" class="flip-trigger">
                <div class="name">Joshua O. Matia-ong</div>
                <p>Tester</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 13 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/13.jpg" alt="Reygie Sales" class="flip-trigger">
                <div class="name">Reygie Sales</div>
                <div class="link"><a href="https://www.facebook.com/reygie.sales" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb13.jpg" alt="Reygie Sales" class="flip-trigger">
                <div class="name">Reygie Sales</div>
                <p>Designer</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 14 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/14.jpg" alt="Ken M. Viogela" class="flip-trigger">
                <div class="name">Ken M. Viogela</div>
                <div class="link"><a href="https://www.facebook.com/kmviogela" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb14.jpg" alt="Ken M. Viogela" class="flip-trigger">
                <div class="name">Ken M. Viogela</div>
                <p>Analyst</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 15 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/15.jpg" alt="Jeff Christian Gumba" class="flip-trigger">
                <div class="name">Jeff Christian Gumba</div>
                <div class="link"><a href="https://www.facebook.com/jeffchristian.gumba.1" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb15.jpg" alt="Jeff Christian Gumba" class="flip-trigger">
                <div class="name">Jeff Christian Gumba</div>
                <p>Coordinator</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 16 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/16.jpg" alt="Jerome Bautista" class="flip-trigger">
                <div class="name">Jerome Bautista</div>
                <div class="link"><a href="https://www.facebook.com/daemonrie" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb16.jpg" alt="Jerome Bautista" class="flip-trigger">
                <div class="name">Jerome Bautista</div>
                <p>Documenter</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Member 17 with Flip Effect -->
        <div class="team-member floating">
          <div class="flip-card">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <img src="images/17.jpg" alt="Vincent Meynard Macabasag" class="flip-trigger">
                <div class="name">Vincent Meynard Macabasag</div>
                <div class="link"><a href="https://www.facebook.com/vincentmeynard.macabasag.3" target="_blank">Facebook Profile</a></div>
              </div>
              <div class="flip-card-back">
                <img src="images/fb17.jpg" alt="Vincent Meynard Macabasag" class="flip-trigger">
                <div class="name">Vincent Meynard Macabasag</div>
                <p>Researcher</p>
              </div>
            </div>
          </div>
        </div>

      </div>  
    </div>
    
    <!-- Copyright Box -->
    <div class="copyright-box">
      <p>© 2025 DEVSYST. All Rights Reserved. Built with XAMPP, VS Code, and RFID Technology. Developed by DEVSYST.</p>
    </div>
  </div>

  <script>
    // JavaScript to handle flip card click
    document.addEventListener('DOMContentLoaded', function() {
      const flipCards = document.querySelectorAll('.flip-card');
      
      flipCards.forEach(card => {
        const flipTriggers = card.querySelectorAll('.flip-trigger');
        
        flipTriggers.forEach(trigger => {
          trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            card.classList.toggle('flipped');
          });
        });
      });
    });
  </script>

</body>
</html>