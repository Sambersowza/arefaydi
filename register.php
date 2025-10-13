<?php
// Simplified Student Registration - Only RFID required
include 'config.php';

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = sanitizeInput($_POST['rfid'] ?? '');

    // Validate RFID (exactly 10 digits)
    if (!validateRFID($rfid)) {
        $response['message'] = "RFID must be exactly 10 digits.";
    } else {
        // Check if RFID already exists in rfid_scans
        $existing_scan = fetchSingleResult($conn, "SELECT id FROM rfid_scans WHERE rfid_number = ?", [$rfid], "s");
        
        if ($existing_scan) {
            $response['message'] = "RFID already registered.";
        } else {
            // Insert into rfid_scans table
            $stmt = executeQuery($conn, "INSERT INTO rfid_scans (rfid_number) VALUES (?)", [$rfid], "s");
            
            if ($stmt) {
                $response['status'] = 'success';
                $response['message'] = 'RFID scan recorded. Please check your app for registration.';
                logActivity("RFID scanned for registration: $rfid");
            } else {
                $response['message'] = "Registration failed. Please try again.";
            }
        }
    }
    
    // Return JSON response for AJAX requests
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RFID Student Registration</title>
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
            padding: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        .top-bar {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
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
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .home-btn:hover {
            background: #ecf0f1;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .container:hover::before {
            left: 100%;
        }

        h2 {
            margin-bottom: 30px;
            color: #2c3e50;
            font-size: 28px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            position: relative;
            display: inline-block;
        }

        h2::after {
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

        .instruction {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            padding: 15px;
            font-size: 24px;
            text-align: center;
            border: 3px solid #3498db;
            border-radius: 10px;
            letter-spacing: 2px;
            font-family: monospace;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .input-field:focus {
            outline: none;
            border-color: #2980b9;
            box-shadow: 0 0 15px rgba(52, 152, 219, 0.5);
            transform: scale(1.02);
        }

        .status-message {
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-weight: bold;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .error {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
        }

        .loading {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }

        .loading-animation {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .fade-out {
            opacity: 0;
            transition: opacity 1s ease-out;
        }

        .hidden {
            display: none;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .container {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="admin.php" class="home-btn">
            <img src="images/return.png" alt="Return" style="width: 20px; height: 20px;">
            Return to Admin
        </a>
    </div>

    <div class="container">
        <h2>📱 RFID Student Registration</h2>
        <p class="instruction">Scan or enter the 10-digit RFID number</p>
        
        <form id="rfidForm">
            <input type="text" 
                   id="rfidInput" 
                   name="rfid" 
                   class="input-field" 
                   placeholder="RFID Number"
                   maxlength="10" 
                   pattern="[0-9]{10}"
                   autocomplete="off"
                   autofocus>
        </form>

        <div id="statusMessage" class="status-message hidden"></div>
    </div>

    <script>
        class RFIDRegistration {
            constructor() {
                this.rfidInput = document.getElementById('rfidInput');
                this.statusMessage = document.getElementById('statusMessage');
                this.currentRFID = null;
                this.checkInterval = null;
                
                this.init();
            }

            init() {
                // Auto-focus input on page load and keep it focused
                this.rfidInput.focus();
                
                // Re-focus if user clicks elsewhere
                document.addEventListener('click', () => {
                    setTimeout(() => this.rfidInput.focus(), 100);
                });
                
                // Handle input changes
                this.rfidInput.addEventListener('input', (e) => {
                    this.handleInput(e.target.value);
                });
                
                // Prevent form submission
                document.getElementById('rfidForm').addEventListener('submit', (e) => {
                    e.preventDefault();
                });
            }

            handleInput(value) {
                // Only allow digits
                value = value.replace(/[^0-9]/g, '');
                this.rfidInput.value = value;
                
                // Auto-submit when 10 digits are entered
                if (value.length === 10) {
                    this.submitRFID(value);
                } else {
                    this.hideStatus();
                }
            }

            async submitRFID(rfid) {
                this.currentRFID = rfid;
                this.showLoading();
                
                try {
                    const formData = new FormData();
                    formData.append('rfid', rfid);
                    formData.append('ajax', '1');
                    
                    const response = await fetch('register.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.status === 'success') {
                        this.startRegistrationCheck();
                    } else {
                        this.showError(result.message);
                        this.resetForm();
                    }
                } catch (error) {
                    this.showError('Network error. Please try again.');
                    this.resetForm();
                }
            }

            startRegistrationCheck() {
                let attempts = 0;
                const maxAttempts = 60; // 2 minutes max
                
                this.checkInterval = setInterval(async () => {
                    attempts++;
                    
                    try {
                        const response = await fetch(`check_student.php?rfid=${this.currentRFID}`);
                        const result = await response.json();
                        
                        if (result.status === 'found') {
                            this.showSuccess();
                            this.stopRegistrationCheck();
                            this.resetFormDelayed();
                        } else if (attempts >= maxAttempts) {
                            this.showError('Registration timeout. Please try again or check your app.');
                            this.stopRegistrationCheck();
                            this.resetForm();
                        }
                    } catch (error) {
                        console.error('Check error:', error);
                    }
                }, 2000); // Check every 2 seconds
            }

            stopRegistrationCheck() {
                if (this.checkInterval) {
                    clearInterval(this.checkInterval);
                    this.checkInterval = null;
                }
            }

            showLoading() {
                this.statusMessage.className = 'status-message loading';
                this.statusMessage.innerHTML = `
                    <div class="loading-animation"></div>
                    Please check your registration on the app for RFID: ${this.currentRFID}
                `;
            }

            showSuccess() {
                this.statusMessage.className = 'status-message success';
                this.statusMessage.innerHTML = '✅ Registration successful! Ready for next scan.';
            }

            showError(message) {
                this.statusMessage.className = 'status-message error';
                this.statusMessage.innerHTML = `❌ ${message}`;
            }

            hideStatus() {
                this.statusMessage.className = 'status-message hidden';
            }

            resetForm() {
                this.rfidInput.value = '';
                this.rfidInput.focus();
                this.currentRFID = null;
                
                setTimeout(() => {
                    this.hideStatus();
                }, 3000);
            }

            resetFormDelayed() {
                setTimeout(() => {
                    this.resetForm();
                }, 3000);
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new RFIDRegistration();
        });
    </script>
</body>
</html>