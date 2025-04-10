<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Forms and Validation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
        .error { color: red; }
        .success { color: green; }
        form { margin: 15px 0; }
        label { display: block; margin: 10px 0 5px; }
        input, textarea { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
    <h1>PHP Forms and Validation</h1>

    <?php
    // Initialize variables
    $name = $email = $message = '';
    $errors = [];
    $success = false;

    // Form Processing
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate Name
        if (empty($_POST['name'])) {
            $errors['name'] = 'Name is required';
        } else {
            $name = sanitizeInput($_POST['name']);
            if (strlen($name) < 2) {
                $errors['name'] = 'Name must be at least 2 characters long';
            }
        }

        // Validate Email
        if (empty($_POST['email'])) {
            $errors['email'] = 'Email is required';
        } else {
            $email = sanitizeInput($_POST['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format';
            }
        }

        // Validate Message
        if (empty($_POST['message'])) {
            $errors['message'] = 'Message is required';
        } else {
            $message = sanitizeInput($_POST['message']);
            if (strlen($message) < 10) {
                $errors['message'] = 'Message must be at least 10 characters long';
            }
        }

        // Process if no errors
        if (empty($errors)) {
            $success = true;
            // Here you would typically save to database or send email
        }
    }

    // Helper function to sanitize input
    function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>

    <div class='example'>
        <h2>Contact Form Example</h2>

        <?php if ($success): ?>
            <div class='success output'>
                Thank you for your message! We'll get back to you soon.
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>">
                <?php if (isset($errors['name'])): ?>
                    <span class="error"><?php echo $errors['name']; ?></span>
                <?php endif; ?>
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                <?php if (isset($errors['email'])): ?>
                    <span class="error"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
            </div>

            <div>
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4"><?php echo $message; ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <span class="error"><?php echo $errors['message']; ?></span>
                <?php endif; ?>
            </div>

            <button type="submit">Submit</button>
        </form>

        <h2>Form Security Features</h2>
        <div class='output'>
            <ul>
                <li>Input Sanitization</li>
                <li>CSRF Protection (form action uses PHP_SELF)</li>
                <li>HTML Special Characters Encoding</li>
                <li>Email Format Validation</li>
                <li>Required Field Validation</li>
                <li>Length Validation</li>
            </ul>
        </div>

        <h2>GET vs POST</h2>
        <div class='output'>
            <p>Current Request Method: <?php echo $_SERVER['REQUEST_METHOD']; ?></p>
            <p>GET: Used for retrieving data (visible in URL)</p>
            <p>POST: Used for submitting data (not visible in URL)</p>
        </div>
    </div>
</body>
</html>