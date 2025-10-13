<?php
$attendance_records = [];
$absentees = [];
$error_message = '';
$success_message = '';
$selected_date = '';
$today = date('Y-m-d');
$formatted_date_header = '';

// Capture filters
$selected_strand = isset($_POST['strand_filter']) ? $_POST['strand_filter'] : '';
$selected_year = isset($_POST['year_filter']) ? $_POST['year_filter'] : '';
$selected_section = isset($_POST['section_filter']) ? $_POST['section_filter'] : '';

$conn = new mysqli("localhost", "root", "", "rfid_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available saved dates
$date_query = "SELECT DISTINCT saved_date FROM saved_attendance ORDER BY saved_date DESC";
$date_result = $conn->query($date_query);
$dates = [];
while ($row = $date_result->fetch_assoc()) {
    $dates[] = $row['saved_date'];
}

// Save today's attendance
if (isset($_POST['save_attendance'])) {
    // First, automatically timeout any students still checked in
    $timeout_query = "UPDATE attendance 
                      SET time_out = NOW(), auto_timeout = 1 
                      WHERE time_out IS NULL 
                      AND DATE(time_in) = '$today'";
    $conn->query($timeout_query);
    
    $fetch_query = "SELECT a.*, s.name, s.student_number, s.image
                    FROM attendance a
                    JOIN students s ON a.student_id = s.id
                    WHERE DATE(a.time_in) = '$today'";
    $fetch_result = $conn->query($fetch_query);
    
    while ($row = $fetch_result->fetch_assoc()) {
        $student_id = $row['student_id'];
        $name = $conn->real_escape_string($row['name']);
        $student_number = $conn->real_escape_string($row['student_number']);
        $image = $conn->real_escape_string($row['image']);
        $saved_time_in = $row['time_in'];
        $saved_time_out = $row['time_out'];
        // Get the auto_timeout flag to preserve it in saved_attendance
        $auto_timeout = isset($row['auto_timeout']) ? $row['auto_timeout'] : 0;
        
        $insert = "INSERT INTO saved_attendance (student_id, name, student_number, image, saved_time_in, saved_time_out, saved_date, auto_timeout)
                   VALUES ('$student_id', '$name', '$student_number', '$image', '$saved_time_in', '$saved_time_out', '$today', $auto_timeout)";
        $conn->query($insert);
    }

    $conn->query("DELETE FROM attendance WHERE DATE(time_in) = '$today'");
    $success_message = "Today's attendance saved and cleared successfully!";
}

// Get attendance records and absentees
if (isset($_POST['selected_date']) && $_POST['selected_date'] != '') {
    $selected_date = $_POST['selected_date'];
    $formatted_date_header = date('F d, Y', strtotime($selected_date)); // Format for the header

    // Fetch saved attendance with student details
    $sql = "SELECT sa.*, s.year_level, s.strand_course, s.section
            FROM saved_attendance sa
            JOIN students s ON sa.student_id = s.id
            WHERE sa.saved_date = '$selected_date'";
    
    // Apply filters
    if (!empty($selected_strand)) {
        $sql .= " AND s.strand_course = '$selected_strand'";
    }
    if (!empty($selected_year)) {
        $sql .= " AND s.year_level = '$selected_year'";
    }
    if (!empty($selected_section)) {
        $sql .= " AND s.section = '$selected_section'";
    }
    
    $sql .= " ORDER BY sa.saved_time_in DESC";
    
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $attendance_records[] = $row;
    }

    // Fetch absentees for saved date
    $absent_query = "
        SELECT * FROM students 
        WHERE id NOT IN (
            SELECT student_id FROM saved_attendance WHERE saved_date = '$selected_date'
        )";
        
    // Apply filters for absentees
    if (!empty($selected_strand)) {
        $absent_query .= " AND strand_course = '$selected_strand'";
    }
    if (!empty($selected_year)) {
        $absent_query .= " AND year_level = '$selected_year'";
    }
    if (!empty($selected_section)) {
        $absent_query .= " AND section = '$selected_section'";
    }
} else {
    // Fetch today's attendance
    $sql = "SELECT a.*, s.name, s.student_number, s.image, s.year_level, s.strand_course, s.section
            FROM attendance a
            JOIN students s ON a.student_id = s.id
            WHERE DATE(a.time_in) = '$today'";
            
    // Apply filters
    if (!empty($selected_strand)) {
        $sql .= " AND s.strand_course = '$selected_strand'";
    }
    if (!empty($selected_year)) {
        $sql .= " AND s.year_level = '$selected_year'";
    }
    if (!empty($selected_section)) {
        $sql .= " AND s.section = '$selected_section'";
    }
    
    $sql .= " ORDER BY a.time_in DESC";
    
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $attendance_records[] = $row;
    }

    // Fetch absentees for today
    $absent_query = "
        SELECT * FROM students 
        WHERE id NOT IN (
            SELECT student_id FROM attendance WHERE DATE(time_in) = '$today'
        )";
        
    // Apply filters for absentees
    if (!empty($selected_strand)) {
        $absent_query .= " AND strand_course = '$selected_strand'";
    }
    if (!empty($selected_year)) {
        $absent_query .= " AND year_level = '$selected_year'";
    }
    if (!empty($selected_section)) {
        $absent_query .= " AND section = '$selected_section'";
    }
    
    $formatted_date_header = date('F d, Y', strtotime($today)); // Default header text for today
}

$absent_result = $conn->query($absent_query);
while ($row = $absent_result->fetch_assoc()) {
    $absentees[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attendance Records</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('images/room.jpg') no-repeat center center fixed;
      background-size: cover;
      padding: 20px;
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
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .header h1 {
      font-size: 28px;
      margin: 0;
    }

    .return-btn {
      position: fixed;
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
      z-index: 101;
    }

    .return-btn img {
      width: 20px;
      height: 20px;
    }

    .return-btn:hover {
      background: #ecf0f1;
    }

    .attendance-container {
      background: rgba(255, 255, 255, 0.85);
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .select-date-form {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
      position: sticky;
      top: 80px;
      background: rgba(255, 255, 255, 0.85);
      padding: 15px 0;
      z-index: 90;
    }

    .select-date-form select {
      padding: 10px;
      font-size: 16px;
      border-radius: 5px;
      width: 180px;
    }

    /* Custom scrollbar for date dropdown */
    select[name="selected_date"] {
      height: auto;
      max-height: 200px;
      overflow-y: auto;
    }

    /* For Webkit browsers (Chrome, Safari) */
    select[name="selected_date"]::-webkit-scrollbar {
      width: 8px;
    }

    select[name="selected_date"]::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }

    select[name="selected_date"]::-webkit-scrollbar-thumb {
      background: #3498db;
      border-radius: 4px;
    }

    select[name="selected_date"]::-webkit-scrollbar-thumb:hover {
      background: #2980b9;
    }

    .filter-section {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    #attendance-header {
      position: sticky;
      top: 140px;
      background: rgba(255, 255, 255, 0.95);
      padding: 10px 0;
      z-index: 80;
      margin-top: 0;
      margin-bottom: 0;
      transition: opacity 0.3s ease;
    }
    
    .absentees-header {
      font-size: 20px;
      font-weight: bold;
      color: #e74c3c;
      margin-top: 20px;
      position: sticky;
      top: 140px;
      background: rgba(255, 255, 255, 0.95);
      padding: 10px 0;
      z-index: 70;
      margin-bottom: 0;
      transition: opacity 0.3s ease;
    }
    
    .attendance-table thead,
    .absentees-table thead {
      position: sticky;
      top: 190px; /* Adjusted to account for header height */
      background: #3498db;
      z-index: 60;
      transition: opacity 0.3s ease;
    }
    
    .attendance-table thead th,
    .absentees-table thead th {
      background-color: #3498db;
      color: white;
    }

    .attendance-table, .absentees-table {
      width: 100%;
      border-collapse: collapse;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #3498db;
      color: white;
    }

    .notification {
      margin-top: 15px;
      color: green;
      font-weight: bold;
    }

    .save-button-container {
      text-align: right;
      margin-top: 20px;
    }

    button {
      padding: 10px 15px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 5px;
      background-color: #3498db;
      color: white;
      border: none;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #2980b9;
    }

    .absentees-table td {
      color: #e74c3c;
    }

    .absentees-header {
      font-size: 20px;
      font-weight: bold;
      color: #e74c3c;
      margin-top: 20px;
    }

    /* Hide absent students table if no attendance records exist for selected date */
    .absentees-table-container {
      display: <?php echo empty($attendance_records) ? 'none' : 'block'; ?>;
    }
    
    .auto-timeout-note {
      color: #e74c3c;
      font-weight: bold;
      font-style: italic;
    }
    
    @media (max-width: 768px) {
      .select-date-form {
        flex-direction: column;
        align-items: stretch;
      }
      
      .filter-section {
        justify-content: center;
      }
    }

    .absentees-table td {
      color: #e74c3c;
    }

    .absentees-header {
      font-size: 20px;
      font-weight: bold;
      color: #e74c3c;
      margin-top: 20px;
    }

    /* Hide absent students table if no attendance records exist for selected date */
    .absentees-table-container {
      display: <?php echo empty($attendance_records) ? 'none' : 'block'; ?>;
    }
    
    .auto-timeout-note {
      color: #e74c3c;
      font-weight: bold;
      font-style: italic;
    }
    
    @media (max-width: 768px) {
      .select-date-form {
        flex-direction: column;
        align-items: stretch;
      }
      
      .filter-section {
        justify-content: center;
      }
    }
  </style>
</head>
<body>

<div class="header">
  <a href="admin.php" class="return-btn">
    <img src="images/return.png" alt="Return Icon">
    Return
  </a>
  <h1>Attendance</h1>
</div>

<div class="attendance-container">
  <div class="select-date-form">
    <div class="filter-section">
      <label for="attendance_date">Select Attendance Date:</label>
      <form method="POST" id="filterForm" style="display: flex; gap: 10px; flex-wrap: wrap;">
        <select name="selected_date" id="attendance_date" onchange="this.form.submit()">
          <option value="">-- Select a Date --</option>
          <?php foreach ($dates as $date): ?>
            <option value="<?php echo $date; ?>" <?php echo ($selected_date == $date) ? 'selected' : ''; ?>>
              <?php echo date('F d, Y', strtotime($date)); ?>
            </option>
          <?php endforeach; ?>
        </select>
        
        <select name="strand_filter" id="strand_filter" onchange="updateFilters()">
          <option value="">All Strands</option>
          <option value="ICT" <?php echo ($selected_strand == 'ICT') ? 'selected' : ''; ?>>ICT</option>
          <option value="BSCS" <?php echo ($selected_strand == 'BSCS') ? 'selected' : ''; ?>>BSCS</option>
          <option value="BSEntrep" <?php echo ($selected_strand == 'BSEntrep') ? 'selected' : ''; ?>>BSEntrep</option>
        </select>
        
        <select name="year_filter" id="year_filter" onchange="updateSections()">
          <option value="">All Year Levels</option>
          <!-- Options will be populated by JavaScript -->
        </select>
        
        <select name="section_filter" id="section_filter">
          <option value="">All Sections</option>
          <!-- Options will be populated by JavaScript -->
        </select>
        
        <button type="submit">Apply Filters</button>
      </form>
    </div>
    
    <?php if (empty($selected_date)): ?>
      <div class="save-button-container">
        <form method="POST" onsubmit="return confirm('Are you sure you want to save and clear today\'s attendance?');">
          <button type="submit" name="save_attendance">Save Attendance</button>
        </form>
      </div>
    <?php endif; ?>
  </div>

  <!-- Attendance Table -->
  <h2 id="attendance-header">Attendance for <?php echo $formatted_date_header; ?></h2>

  <?php if (!empty($attendance_records)): ?>
    <table class="attendance-table">
      <thead>
        <tr>
          <th>Picture</th>
          <th>Name</th>
          <th>Student Number</th>
          <th>Year Level</th>
          <th>Strand/Course</th>
          <th>Section</th>
          <th>Time In</th>
          <th>Time Out</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($attendance_records as $row): ?>
          <tr>
            <td><img src="<?php echo $row['image'] ?? 'assets/default-profile.png'; ?>" width="50" height="50" style="border-radius: 50%;"></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['student_number']; ?></td>
            <td><?php echo $row['year_level']; ?></td>
            <td><?php echo $row['strand_course']; ?></td>
            <td><?php echo $row['section']; ?></td>
            <td><?php echo date('H:i:s', strtotime($row['saved_time_in'] ?? $row['time_in'])); ?></td>
            <td>
              <?php 
              $timeOut = $row['saved_time_out'] ?? $row['time_out'];
              // Check if this record has an auto timeout flag
              $auto_timeout_flag = isset($row['auto_timeout']) ? $row['auto_timeout'] : 0;

              // If auto_timeout flag is set, show --- regardless of time value
              if ($auto_timeout_flag == 1) {
                echo '<span style="color: #e74c3c;">---</span>';
              } elseif ($timeOut) {
                echo date('H:i:s', strtotime($timeOut));
              } else {
                echo 'Still in';
              }
              ?>
            </td>
            <td>
              <?php 
              // Check if this record has an auto timeout flag
              $auto_timeout_flag = isset($row['auto_timeout']) ? $row['auto_timeout'] : 0;
              if ($auto_timeout_flag == 1) {
                echo '<span class="auto-timeout-note">Didn\'t time-out</span>';
              } elseif (!$timeOut) {
                echo 'In progress';
              } else {
                echo 'Completed';
              }
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No attendance records for the selected date and filters.</p>
  <?php endif; ?>

  <?php if ($success_message): ?>
    <div class="notification"><?php echo $success_message; ?></div>
  <?php elseif ($error_message): ?>
    <div class="notification"><?php echo $error_message; ?></div>
  <?php endif; ?>

  <!-- Absent Students -->
  <div class="absentees-table-container">
    <?php if (!empty($absentees)): ?>
      <div class="absentees-header">Absent Students for <?php echo $formatted_date_header; ?></div>
      <table class="absentees-table">
        <thead>
          <tr>
            <th>Picture</th>
            <th>Name</th>
            <th>Student Number</th>
            <th>Year Level</th>
            <th>Strand/Course</th>
            <th>Section</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($absentees as $row): ?>
            <tr>
              <td><img src="<?php echo $row['image'] ?? 'assets/default-profile.png'; ?>" width="50" height="50" style="border-radius: 50%;"></td>
              <td><?php echo $row['name']; ?></td>
              <td><?php echo $row['student_number']; ?></td>
              <td><?php echo $row['year_level']; ?></td>
              <td><?php echo $row['strand_course']; ?></td>
              <td><?php echo $row['section']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No absentee records for the selected date and filters.</p>
    <?php endif; ?>
  </div>
</div>

<script>
// Sections mapping
const sections = {
    "ICT": {
        "Grade 11": ["IC1MA"],
        "Grade 12": ["IC2MA"]
    },
    "BSCS": {
        "1st Year": ["BS1MA","BS2MA","BS1AA","BS2AA","BS1EA","BS2EA"],
        "2nd Year": ["BS3MA","BS4MA","BS3AA","BS4AA","BS3EA","BS4EA"],
        "3rd Year": ["BS5MA","BS6MA","BS5AA","BS6AA","BS5EA","BS6EA"],
        "4th Year": ["BS7MA","BS8MA","BS7AA","BS8AA","BS7EA","BS8EA"]
    },
    "BSEntrep": {
        "1st Year": ["BN1MA","BN2MA","BN1AA","BN2AA","BN1EA","BN2EA"],
        "2nd Year": ["BN3MA","BN4MA","BN3AA","BN4AA","BN3EA","BN4EA"],
        "3rd Year": ["BN5MA","BN6MA","BN5AA","BN6AA","BN5EA","BN6EA"],
        "4th Year": ["BN7MA","BN8MA","BN7AA","BN8AA","BN7EA","BN8EA"]
    }
};

// Year levels mapping
const yearLevels = {
    "ICT": ["Grade 11", "Grade 12"],
    "BSCS": ["1st Year", "2nd Year", "3rd Year", "4th Year"],
    "BSEntrep": ["1st Year", "2nd Year", "3rd Year", "4th Year"]
};

// Initialize filters on page load
document.addEventListener('DOMContentLoaded', function() {
    updateFilters();
    updateSections();
});

// Update year level options based on selected strand
function updateFilters() {
    const strand = document.getElementById("strand_filter").value;
    const yearSelect = document.getElementById("year_filter");
    const sectionSelect = document.getElementById("section_filter");
    
    // Clear existing options
    yearSelect.innerHTML = '<option value="">All Year Levels</option>';
    sectionSelect.innerHTML = '<option value="">All Sections</option>';
    
    // Populate year levels based on strand
    if (strand && yearLevels[strand]) {
        yearLevels[strand].forEach(function(year) {
            const opt = document.createElement("option");
            opt.value = year;
            opt.textContent = year;
            yearSelect.appendChild(opt);
        });
    }
    
    // If there was a previously selected year, try to keep it
    <?php if ($selected_year): ?>
        const selectedYear = "<?php echo $selected_year; ?>";
        for (let i = 0; i < yearSelect.options.length; i++) {
            if (yearSelect.options[i].value === selectedYear) {
                yearSelect.selectedIndex = i;
                break;
            }
        }
    <?php endif; ?>
    
    // Update sections based on current selections
    updateSections();
}

// Update section options based on selected strand and year level
function updateSections() {
    const strand = document.getElementById("strand_filter").value;
    const year = document.getElementById("year_filter").value;
    const sectionSelect = document.getElementById("section_filter");
    
    // Clear existing options
    sectionSelect.innerHTML = '<option value="">All Sections</option>';
    
    // Populate sections based on strand and year
    if (strand && year && sections[strand] && sections[strand][year]) {
        sections[strand][year].forEach(function(sec) {
            const opt = document.createElement("option");
            opt.value = sec;
            opt.textContent = sec;
            sectionSelect.appendChild(opt);
        });
    }
    
    // If there was a previously selected section, try to keep it
    <?php if ($selected_section): ?>
        const selectedSection = "<?php echo $selected_section; ?>";
        for (let i = 0; i < sectionSelect.options.length; i++) {
            if (sectionSelect.options[i].value === selectedSection) {
                sectionSelect.selectedIndex = i;
                break;
            }
        }
    <?php endif; ?>
}

document.getElementById('attendance_date').addEventListener('change', function() {
    var selectedDate = this.value;
    var formattedDate = new Date(selectedDate);
    var dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    var formattedDateString = formattedDate.toLocaleDateString(undefined, dateOptions);
    document.getElementById('attendance-header').innerHTML = 'Attendance for ' + formattedDateString;
});

// Function to handle header visibility based on scroll position
function handleHeaderVisibility() {
  const attendanceHeader = document.getElementById('attendance-header');
  const absenteesHeader = document.querySelector('.absentees-header');
  const attendanceTableHeader = document.querySelector('.attendance-table thead');
  const absenteesTableHeader = document.querySelector('.absentees-table thead');
  
  if (!attendanceHeader || !absenteesHeader) return;
  
  const absenteesRect = absenteesHeader.getBoundingClientRect();
  const stickyTop = 140; // Same as top value in CSS
  
  // When absentees header reaches the sticky position, fade out attendance elements
  if (absenteesRect.top <= stickyTop) {
    attendanceHeader.style.opacity = '0';
    if (attendanceTableHeader) {
      attendanceTableHeader.style.opacity = '0';
    }
  } else {
    attendanceHeader.style.opacity = '1';
    if (attendanceTableHeader) {
      attendanceTableHeader.style.opacity = '1';
    }
  }
  
  // Handle absentees table header visibility
  if (absenteesTableHeader) {
    if (absenteesRect.top <= stickyTop) {
      absenteesTableHeader.style.opacity = '1';
    } else {
      absenteesTableHeader.style.opacity = '0';
    }
  }
}

// Add scroll event listener
window.addEventListener('scroll', handleHeaderVisibility);

// Trigger on load to set initial state
document.addEventListener('DOMContentLoaded', function() {
  handleHeaderVisibility();
  // Also update on window resize to handle layout changes
  window.addEventListener('resize', handleHeaderVisibility);
});
</script>

</body>
</html>