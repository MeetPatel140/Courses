<?php
require_once '../01-User-Authentication/auth.php';

// Initialize Authentication class
// Example output: New Authentication instance created
$auth = new Authentication();

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    header('Location: ../01-User-Authentication/login.php');
    exit;
}

// Generate CSRF token if not exists
// Example: $_SESSION['csrf_token'] = 'abc123...'
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Content Loading</title>
    <style>
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }
        .data-container {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .button {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        .button:hover {
            background: #45a049;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dynamic Content Loading Example</h2>
        
        <!-- Data display container -->
        <div id="dataContainer" class="data-container">
            <p>Click the button to load data...</p>
        </div>
        
        <!-- Control buttons -->
        <button onclick="loadData()" class="button">Load Data</button>
        <button onclick="saveData()" class="button">Save Data</button>
        
        <!-- Error display -->
        <div id="errorContainer" class="error"></div>
    </div>

    <script>
        // Load data using AJAX
        // Example: Fetches data from server and updates display
        function loadData() {
            const formData = new FormData();
            formData.append('action', 'get_data');
            formData.append('csrf_token', '<?php echo $_SESSION["csrf_token"]; ?>');
            
            fetch('ajax_handler.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update display with received data
                    const container = document.getElementById('dataContainer');
                    container.innerHTML = `
                        <h3>Received Data:</h3>
                        <p>ID: ${data.data.id}</p>
                        <p>Name: ${data.data.name}</p>
                        <p>Timestamp: ${data.data.timestamp}</p>
                    `;
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                document.getElementById('errorContainer').textContent = 
                    'Error: ' + error.message;
            });
        }
        
        // Save data using AJAX
        // Example: Sends data to server and handles response
        function saveData() {
            const formData = new FormData();
            formData.append('action', 'save_data');
            formData.append('csrf_token', '<?php echo $_SESSION["csrf_token"]; ?>');
            formData.append('name', 'Test Data');
            formData.append('value', 'Sample Value');
            
            fetch('ajax_handler.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('dataContainer').innerHTML = 
                        '<p>Data saved successfully!</p>';
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                document.getElementById('errorContainer').textContent = 
                    'Error: ' + error.message;
            });
        }
    </script>
</body>
</html>