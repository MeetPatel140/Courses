<?php
// Start session for CSRF protection
// Example: $_SESSION['csrf_token'] = 'abc123...'
session_start();

// Generate CSRF token if not exists
// Example output: New token generated and stored in session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to sanitize input data
// Example input: sanitizeInput(' John Doe ')
// Example output: 'John Doe'
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate email format
// Example input: validateEmail('john@example.com')
// Example output: true
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Handle form submission via AJAX
// Example input: POST request with name, email, message
// Example output: JSON response with status and message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $response = ['status' => '', 'message' => ''];
    
    // Verify CSRF token
    // Example: Compare submitted token with session token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid request';
        echo json_encode($response);
        exit;
    }
    
    // Get and sanitize form data
    // Example input: name = ' John Doe '
    // Example output: name = 'John Doe'
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    
    // Validate form data
    // Example: Check if all fields are filled and email is valid
    if (empty($name) || empty($email) || empty($message)) {
        $response['status'] = 'error';
        $response['message'] = 'All fields are required';
    } elseif (!validateEmail($email)) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email format';
    } else {
        // Process email sending (example using PHP mail function)
        // In production, use a proper email library like PHPMailer
        $to = 'admin@example.com';
        $subject = 'New Contact Form Submission';
        $headers = 'From: ' . $email . "\r\n" .
                   'Reply-To: ' . $email . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        
        $emailBody = "Name: $name\n" .
                     "Email: $email\n\n" .
                     "Message:\n$message";
        
        // Attempt to send email
        // Example output: Email sent successfully or failed
        if (mail($to, $subject, $emailBody, $headers)) {
            $response['status'] = 'success';
            $response['message'] = 'Message sent successfully!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to send message';
        }
    }
    
    // Send JSON response
    // Example output: {"status":"success","message":"Message sent successfully!"}
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
        /* Basic styling for contact form */
        .contact-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            display: none;
        }
        .success {
            color: green;
            margin-bottom: 15px;
            display: none;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <h2>Contact Us</h2>
        
        <!-- Success/Error messages -->
        <div id="successMessage" class="success"></div>
        <div id="errorMessage" class="error"></div>
        
        <!-- Contact Form -->
        <form id="contactForm" onsubmit="return submitForm(event)">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            
            <button type="submit" class="btn">Send Message</button>
        </form>
    </div>

    <script>
        // Function to submit form via AJAX
        // Example: Form submission with validation and error handling
        async function submitForm(event) {
            event.preventDefault();
            
            // Reset message displays
            document.getElementById('successMessage').style.display = 'none';
            document.getElementById('errorMessage').style.display = 'none';
            
            // Get form data
            // Example: Create FormData object from form elements
            const form = document.getElementById('contactForm');
            const formData = new FormData(form);
            
            try {
                // Send AJAX request
                // Example: POST request to same page with form data
                const response = await fetch('contact_form.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                // Parse response
                // Example output: {status: 'success', message: 'Message sent successfully!'}
                const data = await response.json();
                
                // Handle response
                if (data.status === 'success') {
                    // Show success message and reset form
                    document.getElementById('successMessage').textContent = data.message;
                    document.getElementById('successMessage').style.display = 'block';
                    form.reset();
                } else {
                    // Show error message
                    document.getElementById('errorMessage').textContent = data.message;
                    document.getElementById('errorMessage').style.display = 'block';
                }
            } catch (error) {
                // Handle network or other errors
                document.getElementById('errorMessage').textContent = 'An error occurred. Please try again.';
                document.getElementById('errorMessage').style.display = 'block';
            }
            
            return false;
        }
    </script>
</body>
</html>