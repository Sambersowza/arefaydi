<?php
// Start session to store admin status
session_start();

// Admin credentials (for demo; replace with DB in production)
$valid_rfid = "3870770196";
$valid_password = "admin123"; // Change this to your actual admin password

// Admin name (for welcome message)
$admin_name = "Lester Sam Duremdes";

// Step 1: RFID verification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['rfid_number']) && !isset($_POST['password'])) {
        $rfid_number = $_POST['rfid_number'];
        if ($rfid_number === $valid_rfid) {
            $_SESSION['pending_admin_rfid'] = $rfid_number; // Temporarily store RFID
            $show_password_form = true;
        } else {
            $error_message = "Invalid RFID number. Please try again.";
        }
    }

    // Step 2: Password verification
    elseif (isset($_POST['password']) && isset($_SESSION['pending_admin_rfid'])) {
        $password = $_POST['password'];
        if ($password === $valid_password) {
            $_SESSION['is_admin_authenticated'] = true;
            $_SESSION['admin_name'] = $admin_name; // Store admin name
            unset($_SESSION['pending_admin_rfid']); // Clear temporary RFID
            // Show loading animation before redirect
            $show_loading = true;
        } else {
            $show_password_form = true;
            $error_message = "Incorrect password. Please try again.";
        }
    }
    
    // Handle admin registration
    elseif (isset($_POST['register_admin'])) {
        // In a real application, you would save these to a database
        $success_message = "Admin registration request submitted. Awaiting approval.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Authentication</title>
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
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
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

        .auth-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: stretch;
            width: 90%;
            max-width: 1100px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 25px;
            gap: 25px;
            position: relative;
        }

        /* Login Box */
        .login-box {
            flex: 1;
            background: url('images/head.jpg') no-repeat center center;
            background-size: cover;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 25px;
            text-align: center;
            position: relative;
            border: 3px solid white;
            transition: transform 0.3s ease;
            animation: float 4s ease-in-out infinite;
        }

        /* Registration Box */
        .register-box {
            flex: 1;
            background: url('images/regi.jpg') no-repeat center center;
            background-size: cover;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 25px;
            text-align: center;
            position: relative;
            border: 3px solid white;
            transition: transform 0.3s ease;
            animation: float 4s ease-in-out infinite 0.5s;
        }

        /* Floating animation for boxes */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .box-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 10px;
        }

        .form-group {
            width: 100%;
            margin-bottom: 15px;
        }

        .form-group input {
            width: 80%;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-group button {
            width: 85%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .form-group button:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .error-message {
            color: #e74c3c;
            margin-top: 10px;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 8px;
            font-weight: bold;
        }

        .success-message {
            color: #27ae60;
            margin-top: 10px;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 8px;
            font-weight: bold;
        }

        .top-bar {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .top-bar img {
            width: 20px;
            height: 20px;
        }

        .home-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: #3498db;
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
        }

        .home-btn:hover {
            background: #ecf0f1;
        }

        h1 {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 10px;
        }

        /* RFID Popup Box */
        .rfid-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease-out;
        }

        /* Password Popup Box */
        .password-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease-out;
        }

        /* Loading Animation */
        .loading-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            backdrop-filter: blur(5px);
            color: white;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #3498db;
            animation: spin 1s ease-in-out infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .welcome-message {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .popup-content {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            animation: modalAppear 0.3s ease-out;
        }

        @keyframes modalAppear {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .popup-content h3 {
            margin-bottom: 20px;
            color: #333;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .popup-content input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 2px solid #ddd;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .popup-content input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }

        .popup-content button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: #fff;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            margin: 5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .popup-content button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        @media (max-width: 900px) {
            .auth-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <h1>Admin Authentication</h1>

    <!-- LOADING ANIMATION -->
    <?php if (isset($show_loading)) : ?>
    <div class="loading-animation">
        <div class="spinner"></div>
        <div class="welcome-message">Welcome, <?php echo $admin_name; ?>!</div>
        <div>Redirecting to admin dashboard...</div>
    </div>
    <script>
        // Redirect after 2 seconds
        setTimeout(function() {
            window.location.href = "admin.php";
        }, 2000);
    </script>
    <?php endif; ?>

    <div class="auth-container">
        <!-- LOGIN BOX -->
        <div class="login-box">
            <h2 class="box-title">Admin Login</h2>
            <form action="admin_auth.php" method="post">
                <!-- RFID input field -->
                <div class="form-group">
                    <input type="text" id="rfid_input" name="rfid_number" placeholder="Enter RFID Number..." required autocomplete="off"
                        value="" readonly onclick="openRfidPopup()">
                </div>
                <div class="form-group">
                    <button type="button" onclick="openRfidPopup()">Enter RFID</button>
                </div>
            </form>
        </div>

        <!-- REGISTRATION BOX -->
        <div class="register-box">
            <h2 class="box-title">Register as Admin</h2>
            <form action="admin_auth.php" method="post">
                <div class="form-group">
                    <input type="text" name="new_admin_name" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="new_admin_email" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="text" name="new_admin_rfid" placeholder="RFID Number" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="register_admin">Request Registration</button>
                </div>
            </form>

            <?php if (isset($success_message)) : ?>
                <p class="success-message"><?= $success_message ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- RFID POPUP BOX -->
    <div class="rfid-popup" id="rfidPopup" style="display: none;">
        <div class="popup-content">
            <h3>Enter RFID Number</h3>
            <form action="admin_auth.php" method="post">
                <input type="text" id="rfid_input_popup" name="rfid_number" placeholder="Enter RFID Number..." required autofocus autocomplete="off">
                <div>
                    <button type="submit">Verify RFID</button>
                    <button type="button" onclick="closeRfidPopup()">Cancel</button>
                </div>
            </form>
            <?php if (isset($error_message)) : ?>
                <p class="error-message"><?= $error_message ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- PASSWORD POPUP BOX -->
    <?php if (isset($show_password_form)) : ?>
    <div class="password-popup">
        <div class="popup-content">
            <h3>Enter Password</h3>
            <form action="admin_auth.php" method="post">
                <input type="password" name="password" placeholder="Enter Password..." required>
                <div>
                    <button type="submit">Login</button>
                    <button type="button" onclick="window.location='admin_auth.php'">Cancel</button>
                </div>
            </form>
            <?php if (isset($error_message)) : ?>
                <p class="error-message"><?= $error_message ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="top-bar">
        <a href="index.php" class="home-btn">
            <img src="images/return.png" alt="Home Icon">
            Home
        </a>
    </div>

    <script>
        // Open RFID popup
        function openRfidPopup() {
            document.getElementById('rfidPopup').style.display = 'flex';
            // Focus RFID input in popup
            setTimeout(function() {
                document.getElementById('rfid_input_popup').focus();
            }, 100);
        }

        // Close RFID popup
        function closeRfidPopup() {
            document.getElementById('rfidPopup').style.display = 'none';
            // Clear the main input field
            document.getElementById('rfid_input').value = '';
        }

        // Auto-submit if RFID is 10 digits
        document.addEventListener('DOMContentLoaded', function() {
            const rfidField = document.getElementById('rfid_input_popup');
            if (rfidField) {
                rfidField.addEventListener('input', function () {
                    if (this.value.length === 10) {
                        this.form.submit();
                    }
                });
            }
        });
    </script>
</body>
</html>