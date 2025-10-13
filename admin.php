<?php
session_start();

// Authentication check
if (!isset($_SESSION['is_admin_authenticated']) || $_SESSION['is_admin_authenticated'] !== true) {
    header("Location: admin_auth.php");
    exit();
}

// Check if user came from any non-admin page (using a referrer check)
$admin_pages = ['admin.php', 'admin_auth.php', 'register.php', 'attendance.php', 'registered_students.php', 'archived_students.php', 'student_dashboard.php'];
$needs_reauth = true;

if (isset($_SERVER['HTTP_REFERER'])) {
    $referrer = $_SERVER['HTTP_REFERER'];
    // Parse the referrer URL to get just the filename
    $referrer_path = parse_url($referrer, PHP_URL_PATH);
    $referrer_filename = basename($referrer_path);
    
    // If the referrer is an admin-related page, no re-authentication needed
    if (in_array($referrer_filename, $admin_pages)) {
        $needs_reauth = false;
    }
}

// If coming from a non-admin page, force re-authentication
if ($needs_reauth && isset($_SERVER['HTTP_REFERER'])) {
    // Clear the authentication session to force re-login
    unset($_SESSION['is_admin_authenticated']);
    header("Location: admin_auth.php");
    exit();
}

// Admin data (you can replace these with session or DB values)
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : "Lester Sam Duremdes";
$admin_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : "ADMIN";
$admin_rfid = "3870770196";
$admin_image = isset($_SESSION['admin_image']) ? $_SESSION['admin_image'] : "images/sam (2).jpg";

// Handle form submission for editing profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $_SESSION['admin_name'] = $_POST['admin_name'];
    $_SESSION['admin_role'] = $_POST['admin_role'];

    // Handle image upload
    if (!empty($_FILES['admin_image']['name'])) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["admin_image"]["name"]);
        if (move_uploaded_file($_FILES["admin_image"]["tmp_name"], $target_file)) {
            $_SESSION['admin_image'] = $target_file;
        }
    }

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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

        .dashboard-container {
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

        /* LEFT SIDE - PROFILE */
        .admin-profile {
            flex: 1;
            background: url('images/rum.jpg') no-repeat center center;
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
        }

        /* Floating animation for profile */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .admin-profile {
            animation: float 4s ease-in-out infinite;
        }

        .profile-img-container {
            position: relative;
            width: 140px;
            height: 140px;
            margin-bottom: 15px;
        }

        .profile-img-container img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3498db;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .profile-img-container img:hover {
            transform: scale(1.05);
        }

        .edit-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: #fff;
            padding: 6px 10px;
            font-size: 12px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            z-index: 2;
        }

        .edit-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .admin-info {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 0 2px white;
            width: 90%;
            margin: 10px auto;
        }

        .admin-info h2 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
            text-shadow: 
                -1px -1px 0 #fff,  
                1px -1px 0 #fff,
                -1px 1px 0 #fff,
                1px 1px 0 #fff;
            background: transparent;
            padding: 8px 12px;
            border-radius: 5px;
            display: block;
            text-align: center;
            z-index: 2;
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
        }

        .admin-info p {
            font-size: 16px;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            text-shadow: 
                -1px -1px 0 #fff,  
                1px -1px 0 #fff,
                -1px 1px 0 #fff,
                1px 1px 0 #fff;
            background: transparent;
            padding: 5px 10px;
            border-radius: 5px;
            display: block;
            text-align: center;
            z-index: 2;
            text-transform: uppercase;
        }

        .admin-info .rfid {
            font-size: 15px;
            font-weight: bold;
            color: #777;
            background: transparent;
            padding: 5px 10px;
            border-radius: 10px;
            display: block;
            text-align: center;
            z-index: 2;
            text-shadow: 
                -1px -1px 0 #fff,  
                1px -1px 0 #fff,
                -1px 1px 0 #fff,
                1px 1px 0 #fff;
        }

        /* RIGHT SIDE - BUTTON GRID */
        .action-section {
            flex: 2;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 25px;
            width: 100%;
        }

        /* Individual box animations */
        .action-btn:nth-child(1) {
            animation: float 3s ease-in-out infinite 0.2s;
        }
        
        .action-btn:nth-child(2) {
            animation: float 3s ease-in-out infinite 0.4s;
        }
        
        .action-btn:nth-child(3) {
            animation: float 3s ease-in-out infinite 0.6s;
        }
        
        .action-btn:nth-child(4) {
            animation: float 3s ease-in-out infinite 0.8s;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border-radius: 15px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            height: 150px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .action-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .action-btn span {
            font-size: 35px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        /* RETURN BUTTON */
        .home-btn {
            display: inline-block;
            text-align: center;
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: #fff;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            margin-top: 25px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: float 3s ease-in-out infinite 1s;
        }

        .home-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .home-btn:hover::before {
            left: 100%;
        }

        .home-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        /* MODAL */
        .modal {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
            z-index: 1000;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            position: relative;
            animation: modalAppear 0.3s ease-out;
        }

        @keyframes modalAppear {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-content h3 {
            margin-bottom: 20px;
            color: #333;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .modal-content input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 2px solid #ddd;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .modal-content input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }

        .modal-content button {
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

        .modal-content button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        /* Floating animation keyframes */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        @media (max-width: 900px) {
            .dashboard-container {
                flex-direction: column;
                align-items: center;
            }

            .action-grid {
                grid-template-columns: 1fr;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-container">

    <!-- LEFT SIDE: PROFILE -->
    <div class="admin-profile">
        <div class="profile-img-container">
            <img src="<?php echo $admin_image; ?>" alt="Admin Image" id="profileImage">
            <button class="edit-btn" onclick="openModal()">✎</button>
        </div>
        <div class="admin-info">
            <h2 id="adminName"><?php echo $admin_name; ?></h2>
            <p id="adminRole"><?php echo $admin_role; ?></p>
            <p class="rfid">RFID: <?php echo $admin_rfid; ?></p>
        </div>
    </div>

    <!-- RIGHT SIDE: ACTION BUTTONS -->
    <div class="action-section">
        <div class="action-grid">
            <a href="register.php" class="action-btn">
                <span>📋</span>
                Register a Student
            </a>
            <a href="attendance.php" class="action-btn">
                <span>📊</span>
                View Attendance
            </a>
            <a href="registered_students.php" class="action-btn">
                <span>👥</span>
                View Registered Students
            </a>
            <a href="student_dashboard.php" class="action-btn">
                <span>🎓</span>
                Student Violation
            </a>
        </div>
        <a href="index.php" class="home-btn">🏠 Return Home</a>
    </div>

</div>

<!-- Edit Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <h3>Edit Profile</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="admin_name" value="<?php echo $admin_name; ?>" placeholder="Admin Name" required>
            <input type="text" name="admin_role" value="<?php echo $admin_role; ?>" placeholder="Admin Role" required>
            <input type="file" name="admin_image" accept="image/*">
            <div>
                <button type="submit" name="update_profile">Save Changes</button>
                <button type="button" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('editModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

</body>
</html>