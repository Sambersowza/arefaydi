<?php
// Start the session
session_start();

// Check if admin is authenticated
if (!isset($_SESSION['is_admin_authenticated']) || $_SESSION['is_admin_authenticated'] !== true) {
    header("Location: admin_auth.php"); // Redirect to authentication page if not authenticated
    exit();
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture search term
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// Handle update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $student_number = $_POST['student_number'];
    $rfid = $_POST['rfid'];
    $email = $_POST['email']; // NEW FIELD

    // NEW FIELDS
    $year_level = $_POST['year_level'];
    $strand_course = $_POST['strand_course'];
    $section = $_POST['section'];

    $image_path = $_POST['current_image']; // default to existing image

    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create directory if it doesn't exist
        }
        $target_file = $target_dir . basename($_FILES["new_image"]["name"]);
        if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $update_sql = "UPDATE students 
                   SET name='$name', student_number='$student_number', rfid='$rfid', email='$email',
                       year_level='$year_level', strand_course='$strand_course', section='$section',
                       image='$image_path' 
                   WHERE id='$student_id'";

    if ($conn->query($update_sql) === TRUE) {
        $message = "Student updated successfully!";
    } else {
        $message = "Error updating record: " . $conn->error;
    }
}

// Handle archive authentication
$show_archive_auth = false;
$student_to_archive = null;

if (isset($_GET['archive_id'])) {
    $student_to_archive = $_GET['archive_id'];
    $show_archive_auth = true;
}

// Handle archive authentication submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rfid_number']) && isset($_POST['archive_student_id'])) {
    // Admin credentials (for demo; replace with DB in production)
    $valid_rfid = "3870770196";
    
    $rfid_number = $_POST['rfid_number'];
    $student_id = $_POST['archive_student_id'];
    
    if ($rfid_number === $valid_rfid) {
        // No password required - directly archive the student
        // Check if archived column exists
        $check_column = "SHOW COLUMNS FROM students LIKE 'archived'";
        $column_result = $conn->query($check_column);
        
        if ($column_result->num_rows > 0) {
            // Column exists, proceed with archiving
            $archive_sql = "UPDATE students SET archived=1 WHERE id='$student_id'";
        } else {
            // Column doesn't exist, show error message
            $message = "Archiving is not available. Please update the database first.";
        }

        if (isset($archive_sql) && $conn->query($archive_sql) === TRUE) {
            $message = "Student archived successfully!";
        } elseif (isset($archive_sql)) {
            $message = "Error archiving record: " . $conn->error;
        }
        
        // Redirect to avoid resubmission
        header("Location: registered_students.php?message=" . urlencode($message));
        exit();
    } else {
        $error_message = "Invalid RFID number. Please try again.";
        $show_archive_auth = true;
        $student_to_archive = $student_id;
    }
}

// Get message from redirect if exists
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

// Fetch students from the database with search
$sql = "SELECT * FROM students WHERE (archived IS NULL OR archived=0)";
if (!empty($search_term)) {
    $sql .= " AND student_number LIKE '%$search_term%'";
}
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Students</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: url('images/room.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            width: 95%;
            max-width: 1200px;
            overflow: visible;
            position: relative;
        }

        /* Header container to align title and return button */
        .header-container {
            position: sticky;
            top: 0;
            background: rgba(255, 255, 255, 0.95);
            z-index: 100;
            padding: 10px 0;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .container h1 {
            text-align: center;
            margin: 0;
            color: #333;
            flex-grow: 1;
        }

        .top-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 100;
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

        .archive-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f39c12;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .archive-link:hover {
            background-color: #d35400;
        }

        .search-section {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            position: sticky;
            top: 60px;
            background: rgba(255, 255, 255, 0.95);
            z-index: 90;
            padding: 10px 0;
        }

        .search-section input {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            width: 300px;
            border: 1px solid #ddd;
        }

        .search-section button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-section button:hover {
            background-color: #2980b9;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            position: relative;
        }

        .table th, .table td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        thead {
            position: sticky;
            top: 120px;
            z-index: 80;
        }

        .table th {
            background-color: #3498db;
            color: white;
        }

        .table td img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
        }

        .edit-btn, .archive-btn {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 5px;
            font-size: 16px;
            display: inline-block;
            text-align: center;
            width: 120px;
            box-sizing: border-box;
        }

        .edit-btn {
            background-color: #3498db;
        }

        .edit-btn:hover {
            background-color: #2980b9;
        }

        .archive-btn {
            background-color: #f39c12;
            text-decoration: none;
        }

        .archive-btn:hover {
            background-color: #d35400;
        }

        .success-message {
            margin-bottom: 20px;
            color: green;
        }

        .error-message {
            margin-bottom: 20px;
            color: red;
        }

        .edit-form {
            display: none;
            margin-top: 10px;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 10px;
            width: 100%; 
            max-width: 350px;
            margin-left: auto;
            margin-right: auto;
            box-sizing: border-box;
        }

        .edit-form input[type="text"],
        .edit-form input[type="file"] {
            margin-top: 10px;
            margin-bottom: 15px;
            width: 100%; 
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        .edit-form button[type="submit"] {
            margin-top: 10px;
        }

        /* Zoom modal styling */
        .zoom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .zoom-modal img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
        }

        .zoom-modal .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }

        /* Archive Authentication Popup */
        .archive-auth-popup {
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

        .archive-auth-content {
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
            margin: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes modalAppear {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .archive-auth-content h3 {
            margin-bottom: 20px;
            color: #333;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .archive-auth-content input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 2px solid #ddd;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            box-sizing: border-box;
        }

        .archive-auth-content input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }

        .archive-auth-content button {
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

        .archive-auth-content button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .archive-auth-content .cancel-btn {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .archive-auth-content .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .archive-auth-content .button-container button {
            flex: 1;
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .search-section {
                flex-direction: column;
                align-items: center;
            }
            
            .search-section input {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>

<!-- Top Bar with Home Button -->
<div class="top-bar">
    <a href="admin.php" class="home-btn" id="returnBtn">
        <img src="images/return.png" alt="Home Icon">
        Return
    </a>
</div>

<div class="container">
    <div class="header-container">
        <div style="width: 100px;"></div> <!-- Spacer to balance the layout -->
        <h1>Registered Students</h1>
        <!-- Display error message for missing column -->
        <?php 
        // Check if archived column exists
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "rfid_system";
        $conn = new mysqli($servername, $username, $password, $dbname);
        $check_column = "SHOW COLUMNS FROM students LIKE 'archived'";
        $column_result = $conn->query($check_column);
        $conn->close();
        
        if ($column_result->num_rows > 0): ?>
            <a href="archived_students.php" class="archive-link">See Archived Students</a>
        <?php else: ?>
            <div style="width: 100px;"></div> <!-- Spacer to balance the layout -->
        <?php endif; ?>
    </div>

    <!-- Display success message -->
    <?php if (isset($message)): ?>
        <div class="success-message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($column_result->num_rows == 0): ?>
        <div class="error-message">
            Archiving feature is not available. Please run the following SQL command to enable it:
            <pre>ALTER TABLE students ADD COLUMN archived TINYINT(1) DEFAULT 0;</pre>
        </div>
    <?php endif; ?>

    <!-- Search Section -->
    <div class="search-section">
        <form method="POST" style="display: flex; gap: 10px;">
            <input type="text" name="search_term" placeholder="Search by Student Number..." value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Student Number</th>
            <th>RFID</th>
            <th>Email</th>
            <th>Year Level</th>
            <th>Strand/Course</th>
            <th>Section</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img class="profile-img" src="<?php echo (!empty($row['image']) && file_exists($row['image'])) ? $row['image'] : 'images/pfp.jpg'; ?>" alt="Profile Picture" onclick="zoomImage(this)"></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['student_number']; ?></td>
                    <td><?php echo $row['rfid']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['year_level']; ?></td>
                    <td><?php echo $row['strand_course']; ?></td>
                    <td><?php echo $row['section']; ?></td>
                    <td>
                        <button class="edit-btn" id="edit-btn-<?php echo $row['id']; ?>" onclick="toggleEditForm(<?php echo $row['id']; ?>)">Edit</button>
                        <?php if ($column_result->num_rows > 0): ?>
                            <a href="registered_students.php?archive_id=<?php echo $row['id']; ?>" class="archive-btn">Archive</a>
                        <?php endif; ?>

                        <div id="edit-form-<?php echo $row['id']; ?>" class="edit-form">
                            <form action="registered_students.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="current_image" value="<?php echo $row['image']; ?>">
                                <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
                                <input type="text" name="student_number" value="<?php echo $row['student_number']; ?>" required>
                                <input type="text" name="rfid" value="<?php echo $row['rfid']; ?>" required>
                                <input type="text" name="email" value="<?php echo $row['email']; ?>" placeholder="Email" required>

                                <!-- NEW EDITABLE FIELDS -->
                                <input type="text" name="year_level" value="<?php echo $row['year_level']; ?>" placeholder="Year Level" required>
                                <input type="text" name="strand_course" value="<?php echo $row['strand_course']; ?>" placeholder="Strand/Course" required>
                                <input type="text" name="section" value="<?php echo $row['section']; ?>" placeholder="Section" required>

                                <label>Change Picture:</label>
                                <input type="file" name="new_image" accept="image/*">
                                <button type="submit" name="update" class="edit-btn">Update</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">No students found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Archive Authentication Popup -->
<?php if ($show_archive_auth): ?>
<div class="archive-auth-popup">
    <div class="archive-auth-content">
        <h3>Admin Authentication Required</h3>
        <p>Please scan your RFID to archive this student</p>
        <form action="registered_students.php" method="post">
            <input type="hidden" name="archive_student_id" value="<?php echo $student_to_archive; ?>">
            <input type="text" id="rfid_input" name="rfid_number" placeholder="Enter RFID Number..." required autofocus autocomplete="off">
            <div class="button-container">
                <button type="submit">Verify RFID</button>
                <button type="button" class="cancel-btn" onclick="window.location='registered_students.php'">Cancel</button>
            </div>
        </form>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Zoom Modal -->
<div id="zoom-modal" class="zoom-modal">
    <span class="close-btn" onclick="closeZoom()">X</span>
    <img id="zoom-img" src="" alt="Zoomed Image">
</div>

<script>
function toggleEditForm(studentId) {
    var form = document.getElementById('edit-form-' + studentId);
    var button = document.getElementById('edit-btn-' + studentId);

    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        button.textContent = 'Cancel';
    } else {
        form.style.display = 'none';
        button.textContent = 'Edit';
    }
}

function zoomImage(img) {
    var zoomModal = document.getElementById('zoom-modal');
    var zoomImg = document.getElementById('zoom-img');
    zoomImg.src = img.src;
    zoomModal.style.display = "flex";
}

function closeZoom() {
    var zoomModal = document.getElementById('zoom-modal');
    zoomModal.style.display = "none";
}

// Focus RFID input if present
document.addEventListener('DOMContentLoaded', function() {
    const rfidField = document.getElementById('rfid_input');
    if (rfidField) {
        rfidField.focus();

        // Optionally auto-submit if RFID is 10 digits
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