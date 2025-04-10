<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Control Structures</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>PHP Control Structures</h1>

    <?php
    // If, Else, Elseif Statements
    echo "<div class='example'>
        <h2>Conditional Statements</h2>";
    
    $score = 85;
    echo "<h3>If, Else, Elseif</h3>
        <div class='output'>";
    echo "Score: $score<br>";
    if ($score >= 90) {
        echo "Grade: A";
    } elseif ($score >= 80) {
        echo "Grade: B";
    } elseif ($score >= 70) {
        echo "Grade: C";
    } else {
        echo "Grade: F";
    }
    echo "</div>";

    // Switch Statement
    echo "<h3>Switch Statement</h3>";
    $day = date('l');
    echo "<div class='output'>
        Today is: $day<br>";
    switch ($day) {
        case 'Monday':
            echo "Start of work week";
            break;
        case 'Friday':
            echo "End of work week";
            break;
        case 'Saturday':
        case 'Sunday':
            echo "Weekend!";
            break;
        default:
            echo "Mid-week";
    }
    echo "</div></div>";

    // Loops
    echo "<div class='example'>
        <h2>Loops</h2>";

    // For Loop
    echo "<h3>For Loop</h3>
        <div class='output'>";
    for ($i = 1; $i <= 5; $i++) {
        echo "Iteration $i<br>";
    }
    echo "</div>";

    // While Loop
    echo "<h3>While Loop</h3>
        <div class='output'>";
    $count = 1;
    while ($count <= 5) {
        echo "Count: $count<br>";
        $count++;
    }
    echo "</div>";

    // Do-While Loop
    echo "<h3>Do-While Loop</h3>
        <div class='output'>";
    $num = 1;
    do {
        echo "Number: $num<br>";
        $num++;
    } while ($num <= 5);
    echo "</div>";

    // Foreach Loop
    echo "<h3>Foreach Loop</h3>
        <div class='output'>";
    $colors = ['red', 'green', 'blue', 'yellow'];
    foreach ($colors as $index => $color) {
        echo "Color $index: $color<br>";
    }
    echo "</div></div>";

    // Break and Continue
    echo "<div class='example'>
        <h2>Break and Continue</h2>";

    // Break Example
    echo "<h3>Break Statement</h3>
        <div class='output'>";
    for ($i = 1; $i <= 10; $i++) {
        if ($i == 6) {
            break;
        }
        echo "Number: $i<br>";
    }
    echo "</div>";

    // Continue Example
    echo "<h3>Continue Statement</h3>
        <div class='output'>";
    for ($i = 1; $i <= 5; $i++) {
        if ($i == 3) {
            continue;
        }
        echo "Number: $i<br>";
    }
    echo "</div></div>";

    // Alternative Syntax
    echo "<div class='example'>
        <h2>Alternative Syntax</h2>";

    $isLoggedIn = true;
    echo "<h3>If Statement (Alternative)</h3>
        <div class='output'>";
    if ($isLoggedIn): ?>
        <p>Welcome, User!</p>
    <?php else: ?>
        <p>Please log in.</p>
    <?php endif;
    echo "</div>";

    echo "<h3>Foreach Loop (Alternative)</h3>
        <div class='output'>";
    $fruits = ['apple', 'banana', 'orange'];
    ?>
    <ul>
    <?php foreach ($fruits as $fruit): ?>
        <li><?php echo $fruit; ?></li>
    <?php endforeach; ?>
    </ul>
    <?php
    echo "</div></div>";
    ?>
</body>
</html>