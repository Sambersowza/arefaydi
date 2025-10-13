<?php
// student_dashboard.php
include 'config.php'; // DB connection

// Capture filters
$year = isset($_GET['year']) ? $_GET['year'] : '';
$strand = isset($_GET['strand']) ? $_GET['strand'] : '';
$section = isset($_GET['section']) ? $_GET['section'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : ''; // New search input

// Base query
$query = "SELECT id, name, student_number, year_level, strand_course, section, image FROM students WHERE 1";

if ($year != '') {
    $query .= " AND year_level = '" . $conn->real_escape_string($year) . "'";
}
if ($strand != '') {
    $query .= " AND strand_course = '" . $conn->real_escape_string($strand) . "'";
}
if ($section != '') {
    $query .= " AND section = '" . $conn->real_escape_string($section) . "'";
}
if ($search != '') {
    $query .= " AND student_number LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
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

    .dashboard {
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        margin: 40px auto;
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

    /* Return button positioned on the left */
    .return-home-button {
        background: #e74c3c;
        color: #fff;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
        transition: background 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .return-home-button:hover {
        background: #c0392b;
    }

    h1 {
        text-align: center;
        margin: 0;
        color: #333;
        flex-grow: 1;
    }

    .filters {
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

    .filters select, .filters button, .filters input {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        cursor: pointer;
        font-size: 14px;
    }

    .filters input {
        width: 180px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        position: relative;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
        font-size: 14px;
    }

    thead {
        position: sticky;
        top: 120px; /* Adjust depending on header height */
        z-index: 80;
    }

    thead th {
        background: #3498db;
        color: white;
    }

    tr:nth-child(even) {
        background: #f9f9f9;
    }

    .eye-open {
        color: green;
        font-weight: bold;
    }
    .eye-closed {
        color: red;
        font-weight: bold;
    }
    .penalty {
        color: #e74c3c;
        font-weight: bold;
    }
</style>
</head>
<body>

<div class="dashboard">
    <!-- Header container with return button and title -->
    <div class="header-container">
        <a href="admin.php" class="return-home-button">
            <img src="images/return.png" alt="Return Icon" style="width: 20px; height: 20px;">
            Return
        </a>
        <h1>Student Violation</h1>
        <div style="width: 100px;"></div> <!-- Spacer to balance the layout -->
    </div>

    <!-- Filters & Search -->
    <form method="GET" class="filters">
        <select name="strand" id="strand" onchange="updateFilters()">
            <option value="" <?= $strand==''?'selected':'' ?>>Strand / Course</option>
            <option value="ICT" <?= $strand=="ICT"?'selected':'' ?>>ICT</option>
            <option value="BSCS" <?= $strand=="BSCS"?'selected':'' ?>>BSCS</option>
            <option value="BSEntrep" <?= $strand=="BSEntrep"?'selected':'' ?>>BSEntrep</option>
        </select>

        <select name="year" id="year" onchange="updateSections()">
            <option value="" <?= $year==''?'selected':'' ?>>Year Level</option>
        </select>

        <select name="section" id="section">
            <option value="" <?= $section==''?'selected':'' ?>>Section</option>
        </select>

        <input type="text" name="search" placeholder="Search Student Number" value="<?= htmlspecialchars($search) ?>">

        <button type="submit">Apply Filters</button>
        <button type="button" id="resetFilters" style="padding:8px 12px; border-radius:8px; border:1px solid #ccc; background:#f1f1f1; cursor:pointer;">Reset</button>
    </form>

    <!-- Student Table -->
    <table>
        <thead>
            <tr>
                <th rowspan="2">Student Name</th>
                <th rowspan="2">Student Number</th>
                <th rowspan="2">Year</th>
                <th rowspan="2">Strand / Course</th>
                <th rowspan="2">Section</th>
                <th colspan="2">Violations</th>
                <th rowspan="2">Offense Number</th>
                <th rowspan="2">Acknowledgement</th>
                <th rowspan="2">Penalty</th>
            </tr>
            <tr>
                <th>Conduct Violations</th>
                <th>Dress Code Violations</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['student_number']) ?></td>
                        <td><?= htmlspecialchars($row['year_level']) ?></td>
                        <td><?= htmlspecialchars($row['strand_course']) ?></td>
                        <td><?= htmlspecialchars($row['section']) ?></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td class="eye-closed">🙈 Not Seen</td>
                        <td class="penalty">None</td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No students found with the selected filters.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
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

document.addEventListener('DOMContentLoaded', function() {
    updateFilters();
    updateSections();
});

function updateFilters() {
    const strand = document.getElementById("strand").value;
    const yearSelect = document.getElementById("year");
    const sectionSelect = document.getElementById("section");
    
    yearSelect.innerHTML = '<option value="">Year Level</option>';
    sectionSelect.innerHTML = '<option value="">Section</option>';
    
    if (strand && yearLevels[strand]) {
        yearLevels[strand].forEach(function(year) {
            const opt = document.createElement("option");
            opt.value = year;
            opt.textContent = year;
            yearSelect.appendChild(opt);
        });
    }

    <?php if ($year): ?>
        const selectedYear = "<?php echo $year; ?>";
        for (let i = 0; i < yearSelect.options.length; i++) {
            if (yearSelect.options[i].value === selectedYear) {
                yearSelect.selectedIndex = i;
                break;
            }
        }
    <?php endif; ?>

    updateSections();
}

function updateSections() {
    const strand = document.getElementById("strand").value;
    const year = document.getElementById("year").value;
    const sectionSelect = document.getElementById("section");
    
    sectionSelect.innerHTML = '<option value="">Section</option>';
    
    if (strand && year && sections[strand] && sections[strand][year]) {
        sections[strand][year].forEach(function(sec) {
            const opt = document.createElement("option");
            opt.value = sec;
            opt.textContent = sec;
            sectionSelect.appendChild(opt);
        });
    }

    <?php if ($section): ?>
        const selectedSection = "<?php echo $section; ?>";
        for (let i = 0; i < sectionSelect.options.length; i++) {
            if (sectionSelect.options[i].value === selectedSection) {
                sectionSelect.selectedIndex = i;
                break;
            }
        }
    <?php endif; ?>
}

// Reset button
document.getElementById('resetFilters').addEventListener('click', function() {
    document.getElementById('strand').selectedIndex = 0;
    document.getElementById('year').selectedIndex = 0;
    document.getElementById('section').selectedIndex = 0;
    window.location.href = 'student_dashboard.php';
});
</script>

</body>
</html>