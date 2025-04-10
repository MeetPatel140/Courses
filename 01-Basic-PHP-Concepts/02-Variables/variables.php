<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Variables</title>
</head>
<body>
    <h1>PHP Variables</h1>

    <?php
    // Variable Declaration and Initialization
    $name = "John";             // String
    $age = 25;                 // Integer
    $height = 1.75;            // Float
    $isStudent = true;         // Boolean
    $hobbies = null;           // Null

    // Displaying Variables
    echo "<h2>Basic Variable Usage</h2>";
    echo "<p>Name: $name</p>";
    echo "<p>Age: $age</p>";
    echo "<p>Height: $height meters</p>";
    echo "<p>Is Student? " . ($isStudent ? 'Yes' : 'No') . "</p>";
    echo "<p>Hobbies: " . ($hobbies === null ? 'Not set' : $hobbies) . "</p>";

    // Variable Scope
    echo "<h2>Variable Scope</h2>";

    // Global Scope
    $globalVar = "I'm a global variable";

    function testScope() {
        global $globalVar;  // Accessing global variable
        $localVar = "I'm a local variable";
        echo "<p>Inside function: $globalVar</p>";
        echo "<p>Local variable: $localVar</p>";
    }

    testScope();
    echo "<p>Outside function: $globalVar</p>";
    // echo $localVar; // This would cause an error - local variable not accessible

    // Variable References
    echo "<h2>Variable References</h2>";
    $original = "Original Value";
    $reference = &$original;  // Creating a reference
    $reference = "New Value";
    echo "<p>Original: $original</p>";  // Will show "New Value"
    echo "<p>Reference: $reference</p>";

    // Type Juggling
    echo "<h2>Type Juggling</h2>";
    $number = 42;        // Integer
    $string = "10";     // String
    $result = $number + $string;  // PHP converts string to number
    echo "<p>$number + $string = $result</p>";

    // Variable Variables
    echo "<h2>Variable Variables</h2>";
    $varName = "color";
    $$varName = "blue";  // Creates $color = "blue"
    echo "<p>The value of $$varName is: $color</p>";

    // Constants
    echo "<h2>Constants</h2>";
    define('MAX_VALUE', 100);
    const MIN_VALUE = 0;
    echo "<p>Maximum value: " . MAX_VALUE . "</p>";
    echo "<p>Minimum value: " . MIN_VALUE . "</p>";

    // Predefined Variables
    echo "<h2>Predefined Variables</h2>";
    echo "<p>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
    echo "<p>PHP Version: " . PHP_VERSION . "</p>";
    ?>

    <footer>
        <p>View the source code to understand PHP variable concepts.</p>
    </footer>
</body>
</html>