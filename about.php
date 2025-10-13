<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About | RFID Attendance System</title>
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
      height: 100vh;
      color: white;
    }

    /* Header / Menu Bar */
    .header-menu {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
      background: rgba(41, 128, 185, 0.8); /* Blue background with transparency */
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
      transition: background 0.3s;
    }

    .nav-link:hover {
      background-color: #1f6fa2;
      border-radius: 6px;
    }

    .nav-link img {
      width: 20px;
      height: 20px;
      margin-right: 8px;
    }

    /* About Content */
    .main-content {
      padding: 40px;
      position: relative;
      z-index: 1;
      margin-top: 40px; /* Reduced space to bring boxes closer to header */
    }

    .about-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .about-box {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      height: 100%;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      animation: float 4s ease-in-out infinite;
    }

    .about-box::before {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: 0.5s;
      z-index: 1;
    }

    .about-box:hover::before {
      left: 100%;
    }

    .about-box:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    .about-box-image {
      flex: 1 1 150px;
      min-height: 150px;
      position: relative;
      overflow: hidden;
    }

    .about-box-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .about-box:hover .about-box-image img {
      transform: scale(1.05);
    }

    .about-box-text {
      flex: 2 1 auto;
      padding: 20px;
      color: #333;
    }

    .about-box-text h3 {
      margin-bottom: 10px;
      font-size: 20px;
      color: #2c3e50;
    }

    .about-box-text p {
      line-height: 1.5;
      font-size: 14px;
    }

    /* Modal Styles */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(5px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .modal-content {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
      transform: translateY(-50px);
      transition: transform 0.3s ease;
      padding: 30px;
    }

    .modal-overlay.active .modal-content {
      transform: translateY(0);
    }

    .modal-close {
      position: absolute;
      top: 15px;
      right: 15px;
      background: #e74c3c;
      color: white;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      border: none;
      font-size: 16px;
      cursor: pointer;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: all 0.3s ease;
    }

    .modal-close:hover {
      background: #c0392b;
      transform: scale(1.1);
    }

    .modal-image {
      width: 100%;
      height: 200px;
      border-radius: 10px;
      overflow: hidden;
      margin-bottom: 20px;
    }

    .modal-image img, .modal-image video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .modal-text h3 {
      font-size: 28px;
      color: #2c3e50;
      margin-bottom: 15px;
    }

    .modal-text p {
      line-height: 1.6;
      font-size: 16px;
      margin-bottom: 15px;
      color: #333;
    }

    .modal-text ul {
      color: #333;
    }

    .modal-text ol {
      color: #333;
    }

    .modal-text li {
      color: #333;
      margin-bottom: 8px;
    }

    @media (max-width: 1024px) {
      .about-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .about-grid {
        grid-template-columns: 1fr;
      }
      
      .header-menu {
        flex-direction: column;
        align-items: center;
        padding: 15px;
      }

      .nav-links {
        flex-direction: column;
        margin-top: 20px;
      }

      .nav-link {
        margin: 10px 0;
      }
    }
  </style>
</head>
<body>

  <!-- Header / Menu Bar -->
  <div class="header-menu">
    <h1>About</h1>
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
    <!-- About Content -->
    <div class="about-grid">
      <div class="about-box" onclick="openModal('rfid')">
        <div class="about-box-image">
          <img src="images/rfid.jpg" alt="RFID Technology">
        </div>
        <div class="about-box-text">
          <h3>RFID Technology</h3>
          <p>
            Advanced RFID tracking for student attendance with unique card identification.
          </p>
        </div>
      </div>
      
      <div class="about-box" onclick="openModal('attendance')">
        <div class="about-box-image">
          <img src="images/trak.jpg" alt="Attendance Tracking">
        </div>
        <div class="about-box-text">
          <h3>Attendance Tracking</h3>
          <p>
            Automatic time-in and time-out recording with real-time dashboard monitoring.
          </p>
        </div>
      </div>
      
      <div class="about-box" onclick="openModal('system')">
        <div class="about-box-image">
          <img src="images/syst.jpg" alt="System Features">
        </div>
        <div class="about-box-text">
          <h3>System Features</h3>
          <p>
            Comprehensive dashboard with reporting, exportable data, and flexible options.
          </p>
        </div>
      </div>
      
      <div class="about-box" onclick="openModal('security')">
        <div class="about-box-image">
          <img src="images/security.jpg" alt="Security">
        </div>
        <div class="about-box-text">
          <h3>Security & Privacy</h3>
          <p>
            Advanced encryption and secure authentication to protect sensitive data.
          </p>
        </div>
      </div>
      
      <div class="about-box" onclick="openModal('violation')">
        <div class="about-box-image">
          <img src="images/violation.jpg" alt="Violation Dashboard">
        </div>
        <div class="about-box-text">
          <h3>Violation Dashboard</h3>
          <p>
            Mobile application integration for real-time violation monitoring and reporting.
          </p>
        </div>
      </div>
      
      <div class="about-box" onclick="openModal('howto')">
        <div class="about-box-image">
          <img src="images/howto.jpg" alt="How to Use">
        </div>
        <div class="about-box-text">
          <h3>How to Use It</h3>
          <p>
            Step-by-step guide for students, teachers, and administrators.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Overlay -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal-content">
      <button class="modal-close" onclick="closeModal()">&times;</button>
      <div class="modal-image">
        <img id="modalImage" src="" alt="Modal Image">
        <video id="modalVideo" style="display: none;" autoplay loop muted></video>
      </div>
      <div class="modal-text">
        <h3 id="modalTitle"></h3>
        <div id="modalDescription"></div>
      </div>
    </div>
  </div>

  <script>
    function openModal(type) {
      const modal = document.getElementById('modalOverlay');
      const modalImage = document.getElementById('modalImage');
      const modalVideo = document.getElementById('modalVideo');
      const modalTitle = document.getElementById('modalTitle');
      const modalDescription = document.getElementById('modalDescription');
      
      // Hide both image and video by default
      modalImage.style.display = 'none';
      modalVideo.style.display = 'none';
      
      // Set content based on the box clicked
      switch(type) {
        case 'rfid':
          // Check if video exists, otherwise fallback to image
          if (checkVideoExists('images/rfid.mp4')) {
            modalVideo.src = 'images/rfid.mp4';
            modalVideo.style.display = 'block';
          } else {
            modalImage.src = 'images/rfid.jpg';
            modalImage.style.display = 'block';
          }
          modalTitle.textContent = 'RFID Technology';
          modalDescription.innerHTML = '<p style="color: #333;">Our system uses advanced RFID technology to track student attendance. Each student is issued a unique RFID card that is scanned upon entry and exit, providing accurate and real-time attendance data. The RFID cards contain encrypted information that is securely transmitted to our database, ensuring that each student\'s attendance is recorded with precision. This technology eliminates the possibility of attendance fraud and provides administrators with reliable data for analysis.</p>';
          break;
        case 'attendance':
          // Check if video exists, otherwise fallback to image
          if (checkVideoExists('images/trak.mp4')) {
            modalVideo.src = 'images/trak.mp4';
            modalVideo.style.display = 'block';
          } else {
            modalImage.src = 'images/trak.jpg';
            modalImage.style.display = 'block';
          }
          modalTitle.textContent = 'Attendance Tracking';
          modalDescription.innerHTML = '<p style="color: #333;">The system automatically records time-in and time-out for each student, eliminating manual attendance taking and reducing errors. Teachers can easily view and manage attendance records through an intuitive dashboard. The system generates comprehensive reports that can be exported in various formats, making it easy for educators to analyze attendance patterns and identify students who may need additional support. Real-time notifications can be configured to alert teachers of attendance issues.</p>';
          break;
        case 'system':
          // Check if video exists, otherwise fallback to image
          if (checkVideoExists('images/syst.mp4')) {
            modalVideo.src = 'images/syst.mp4';
            modalVideo.style.display = 'block';
          } else {
            modalImage.src = 'images/syst.jpg';
            modalImage.style.display = 'block';
          }
          modalTitle.textContent = 'System Features';
          modalDescription.innerHTML = '<p style="color: #333;">Our RFID Attendance System offers a comprehensive dashboard for administrators with real-time reporting capabilities. The system supports exportable data in multiple formats including CSV, Excel, and PDF. Manual entry of RFID codes is available for flexibility when cards are lost or damaged. The system also includes photo identification features to ensure the person using the card is the rightful owner. Advanced filtering options allow administrators to quickly find specific attendance records.</p>';
          break;
        case 'security':
          // Check if video exists, otherwise fallback to image
          if (checkVideoExists('images/security.mp4')) {
            modalVideo.src = 'images/security.mp4';
            modalVideo.style.display = 'block';
          } else {
            modalImage.src = 'images/security.jpg';
            modalImage.style.display = 'block';
          }
          modalTitle.textContent = 'Security & Privacy';
          modalDescription.innerHTML = '<p style="color: #333;">All data is securely stored and protected with advanced encryption both in transit and at rest. Access to the system is controlled through secure authentication with role-based permissions to ensure only authorized personnel can view sensitive information. Regular security audits are performed to identify and address potential vulnerabilities. Data backup procedures are in place to prevent loss of information. The system complies with educational privacy regulations to protect student information.</p>';
          break;
        case 'violation':
          // Check if video exists, otherwise fallback to image
          if (checkVideoExists('images/violation.mp4')) {
            modalVideo.src = 'images/violation.mp4';
            modalVideo.style.display = 'block';
          } else {
            modalImage.src = 'images/violation.jpg';
            modalImage.style.display = 'block';
          }
          modalTitle.textContent = 'Violation Dashboard';
          modalDescription.innerHTML = '<p style="color: #333;">The Violation Dashboard provides real-time monitoring of student violations through our mobile application. Key features include:</p>' +
            '<ul style="padding-left: 20px; margin-bottom: 15px;">' +
            '<li style="color: #333;">Instant notifications for new violations</li>' +
            '<li style="color: #333;">Detailed violation history for each student</li>' +
            '<li style="color: #333;">Automated reporting to guardians</li>' +
            '<li style="color: #333;">Violation categorization and severity levels</li>' +
            '<li style="color: #333;">Integration with attendance records</li>' +
            '</ul>' +
            '<p style="color: #333;">Access the dashboard through the mobile app to manage and track student behavior effectively.</p>';
          break;
        case 'howto':
          // Check if video exists, otherwise fallback to image
          if (checkVideoExists('images/howto.mp4')) {
            modalVideo.src = 'images/howto.mp4';
            modalVideo.style.display = 'block';
          } else {
            modalImage.src = 'images/howto.png';
            modalImage.style.display = 'block';
          }
          modalTitle.textContent = 'How to Use It';
          modalDescription.innerHTML = '<p style="color: #333;">Our RFID Attendance System is simple to use:</p>' +
            '<ol style="padding-left: 20px; margin-bottom: 15px;">' +
            '<li style="color: #333;"><strong>Student Registration:</strong> Register each student with their personal details and assign an RFID card</li>' +
            '<li style="color: #333;"><strong>Daily Attendance:</strong> Students tap their RFID card on the reader when entering and leaving campus</li>' +
            '<li style="color: #333;"><strong>Monitoring:</strong> Teachers can view real-time attendance through the dashboard</li>' +
            '<li style="color: #333;"><strong>Violations:</strong> Report violations through the mobile app</li>' +
            '<li style="color: #333;"><strong>Reports:</strong> Generate attendance and violation reports as needed</li>' +
            '<li style="color: #333;"><strong>Administration:</strong> Admins can manage students, view analytics, and configure system settings</li>' +
            '</ol>' +
            '<p style="color: #333;">For detailed instructions, refer to the user manual or contact our support team.</p>';
          break;
        case 'howto':
          // Check if video exists, otherwise fallback to image
          if (checkVideoExists('images/howto.mp4')) {
            modalVideo.src = 'images/howto.mp4';
            modalVideo.style.display = 'block';
          } else {
            modalImage.src = 'images/howto.png';
            modalImage.style.display = 'block';
          }
          modalTitle.textContent = 'How to Use It';
          modalDescription.innerHTML = '<p style="color: #333;">Our RFID Attendance System is simple to use:</p>' +
            '<ol style="padding-left: 20px; margin-bottom: 15px;">' +
            '<li style="color: #333;"><strong>Student Registration:</strong> Register each student with their personal details and assign an RFID card</li>' +
            '<li style="color: #333;"><strong>Daily Attendance:</strong> Students tap their RFID card on the reader when entering and leaving campus</li>' +
            '<li style="color: #333;"><strong>Monitoring:</strong> Teachers can view real-time attendance through the dashboard</li>' +
            '<li style="color: #333;"><strong>Violations:</strong> Report violations through the mobile app with photo evidence</li>' +
            '<li style="color: #333;"><strong>Reports:</strong> Generate attendance and violation reports as needed</li>' +
            '<li style="color: #333;"><strong>Administration:</strong> Admins can manage students, view analytics, and configure system settings</li>' +
            '</ol>' +
            '<p style="color: #333;">For detailed instructions, refer to the user manual or contact our support team.</p>';
          break;
      }
      
      modal.classList.add('active');
    }
    
    function closeModal() {
      const modal = document.getElementById('modalOverlay');
      modal.classList.remove('active');
    }
    
    // Close modal when clicking outside the content
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
      if (e.target === this) {
        closeModal();
      }
    });
    
    // Function to check if video file exists
    function checkVideoExists(url) {
      const http = new XMLHttpRequest();
      http.open('HEAD', url, false);
      http.send();
      return http.status !== 404;
    }
  </script>

</body>
</html>