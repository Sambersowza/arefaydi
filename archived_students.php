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

// Fetch archived students from the database
$sql = "SELECT * FROM students WHERE archived=1";
$result = $conn->query($sql);

// Fetch attendance and violation records for a specific student if requested
$selected_student = null;
$attendance_records = [];
$violation_records = [];

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    
    // Fetch student details
    $student_sql = "SELECT * FROM students WHERE id='$student_id' AND archived=1";
    $student_result = $conn->query($student_sql);
    if ($student_result->num_rows > 0) {
        $selected_student = $student_result->fetch_assoc();
        
        // Fetch attendance records
        $attendance_sql = "SELECT * FROM saved_attendance WHERE student_id='$student_id' ORDER BY saved_date DESC";
        $attendance_result = $conn->query($attendance_sql);
        while ($row = $attendance_result->fetch_assoc()) {
            $attendance_records[] = $row;
        }
        
        // For violation records, we'll create a placeholder since there's no violations table yet
        // In a real implementation, you would query an actual violations table
        $violation_records = [
            // Example violation data structure
            // array('date' => '2023-05-15', 'type' => 'Late Arrival', 'description' => 'Arrived 30 minutes late'),
            // array('date' => '2023-05-20', 'type' => 'Uniform Violation', 'description' => 'Not wearing proper uniform')
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archived Students</title>
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
            padding: 20px;
            position: relative;
        }

        .header {
            background: rgba(52, 152, 219, 0.95);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            margin-bottom: 30px;
            position: relative;
        }

        .header h1 {
            font-size: 28px;
            margin: 0;
        }

        .return-btn {
            position: absolute;
            left: 20px;
            top: 20px;
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

        .return-btn img {
            width: 20px;
            height: 20px;
        }

        .return-btn:hover {
            background: #ecf0f1;
        }

        .container {
            background: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .students-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .student-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .student-card:hover {
            transform: scale(1.05);
        }

        .student-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3498db;
            margin-bottom: 10px;
        }

        .student-card .name {
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }

        /* Student Details Modal */
        .student-details-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .details-container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            height: 80%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .attendance-section, .violation-section {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .attendance-section {
            border-right: 1px solid #ddd;
        }

        .section-header {
            text-align: center;
            margin-bottom: 20px;
            color: #3498db;
            font-size: 24px;
        }

        .records-table {
            width: 100%;
            border-collapse: collapse;
        }

        .records-table th, .records-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .records-table th {
            background-color: #3498db;
            color: white;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 30px;
            color: white;
            cursor: pointer;
            z-index: 1001;
        }

        .no-records {
            text-align: center;
            color: #777;
            font-style: italic;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .details-container {
                flex-direction: column;
                height: 90%;
            }
            
            .attendance-section {
                border-right: none;
                border-bottom: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <a href="registered_students.php" class="return-btn">
        <img src="images/return.png" alt="Return Icon">
        Return
    </a>
    <h1>Archived Students</h1>
</div>

<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <div class="students-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="student-card" onclick="showStudentDetails(<?php echo $row['id']; ?>)">
                    <img src="<?php echo (!empty($row['image']) && file_exists($row['image'])) ? $row['image'] : 'images/pfp.jpg'; ?>" alt="Profile Picture">
                    <div class="name"><?php echo $row['name']; ?></div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No archived students found.</p>
    <?php endif; ?>
</div>

<!-- Student Details Modal -->
<?php if ($selected_student): ?>
<div class="student-details-modal">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <div class="details-container">
        <!-- Attendance Section -->
        <div class="attendance-section">
            <h2 class="section-header">Attendance Records</h2>
            <?php if (!empty($attendance_records)): ?>
                <table class="records-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendance_records as $record): ?>
                            <tr>
                                <td><?php echo date('F d, Y', strtotime($record['saved_date'])); ?></td>
                                <td><?php echo date('H:i:s', strtotime($record['saved_time_in'])); ?></td>
                                <td><?php echo $record['saved_time_out'] ? date('H:i:s', strtotime($record['saved_time_out'])) : 'N/A'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-records">No attendance records found for this student.</div>
            <?php endif; ?>
        </div>

        <!-- Violation Section -->
        <div class="violation-section">
            <h2 class="section-header">Violation Records</h2>
            <?php if (!empty($violation_records)): ?>
                <table class="records-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($violation_records as $violation): ?>
                            <tr>
                                <td><?php echo $violation['date']; ?></td>
                                <td><?php echo $violation['type']; ?></td>
                                <td><?php echo $violation['description']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-records">No violation records found for this student.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    function showStudentDetails(studentId) {
        window.location.href = 'archived_students.php?student_id=' + studentId;
    }

    function closeModal() {
        window.location.href = 'archived_students.php';
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.querySelector('.student-details-modal');
        if (modal && event.target === modal) {
            closeModal();
        }
    });
</script>

</body>
</html>