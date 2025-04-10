<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Sessions and Cookies</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
        .success { color: green; }
        form { margin: 15px 0; }
        label { display: block; margin: 10px 0 5px; }
        input { padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
    <?php
    // Start session
    session_start();

    // Session handling
    if (!isset($_SESSION['visits'])) {
        $_SESSION['visits'] = 1;
    } else {
        $_SESSION['visits']++;
    }

    // Cookie handling
    $cookieName = 'user_preference';
    $cookieValue = 'dark_theme';
    $cookieExpiry = time() + (86400 * 30); // 30 days

    if (isset($_POST['set_cookie'])) {
        setcookie($cookieName, $cookieValue, $cookieExpiry, '/');
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['delete_cookie'])) {
        setcookie($cookieName, '', time() - 3600, '/');
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['destroy_session'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    ?>

    <h1>PHP Sessions and Cookies</h1>

    <div class='example'>
        <h2>Session Example</h2>
        <div class='output'>
            <p>Number of visits in this session: <?php echo $_SESSION['visits']; ?></p>
            <p>Session ID: <?php echo session_id(); ?></p>
            
            <form method="POST">
                <button type="submit" name="destroy_session">Destroy Session</button>
            </form>
        </div>

        <h2>Cookie Example</h2>
        <div class='output'>
            <?php if(isset($_COOKIE[$cookieName])): ?>
                <p>Cookie '<?php echo $cookieName; ?>' is set!</p>
                <p>Value: <?php echo $_COOKIE[$cookieName]; ?></p>
            <?php else: ?>
                <p>Cookie '<?php echo $cookieName; ?>' is not set.</p>
            <?php endif; ?>

            <form method="POST">
                <button type="submit" name="set_cookie">Set Cookie</button>
                <button type="submit" name="delete_cookie">Delete Cookie</button>
            </form>
        </div>

        <h2>Session vs Cookies</h2>
        <div class='output'>
            <h3>Sessions:</h3>
            <ul>
                <li>Stored on server</li>
                <li>More secure for sensitive data</li>
                <li>Expires when browser closes</li>
                <li>Limited to one domain</li>
            </ul>

            <h3>Cookies:</h3>
            <ul>
                <li>Stored on client</li>
                <li>Good for preferences</li>
                <li>Can set expiration time</li>
                <li>Can be accessed by JavaScript</li>
            </ul>
        </div>

        <h2>Security Considerations</h2>
        <div class='output'>
            <ul>
                <li>Use HTTPS for secure transmission</li>
                <li>Set secure and httpOnly flags</li>
                <li>Validate session data</li>
                <li>Implement session timeout</li>
                <li>Prevent session fixation</li>
            </ul>
        </div>
    </div>
</body>
</html>