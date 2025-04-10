<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Functions</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>PHP Functions</h1>

    <?php
    // Basic Function Declaration
    echo "<div class='example'>
        <h2>Basic Functions</h2>";

    function greet($name) {
        return "Hello, $name!";
    }

    echo "<div class='output'>
        Basic function call: " . greet("John") . "
        </div>";

    // Function with Default Parameters
    function calculateTotal($price, $taxRate = 0.1) {
        return $price * (1 + $taxRate);
    }

    echo "<div class='output'>
        With default parameter: $" . calculateTotal(100) . "<br>
        With custom parameter: $" . calculateTotal(100, 0.2) . "
        </div>";

    // Type Declarations
    function add(int $a, int $b): int {
        return $a + $b;
    }

    echo "<div class='output'>
        Type declared function: " . add(5, 3) . "
        </div></div>";

    // Variable Scope
    echo "<div class='example'>
        <h2>Variable Scope</h2>";

    $globalVar = "I'm global";

    function testScope() {
        global $globalVar;
        $localVar = "I'm local";
        echo "Inside function:<br>
            Global: $globalVar<br>
            Local: $localVar<br>";
    }

    echo "<div class='output'>";
    testScope();
    echo "Outside function:<br>
        Global: $globalVar</div></div>";

    // Passing Arguments by Reference
    echo "<div class='example'>
        <h2>Pass by Reference</h2>";

    function modifyArray(&$arr) {
        $arr[] = 4;
    }

    $numbers = [1, 2, 3];
    echo "<div class='output'>
        Before: " . implode(", ", $numbers) . "<br>";
    modifyArray($numbers);
    echo "After: " . implode(", ", $numbers) . "
        </div></div>";

    // Variable Functions
    echo "<div class='example'>
        <h2>Variable Functions</h2>";

    function sayHello() {
        return "Hello!";
    }

    $func = "sayHello";
    echo "<div class='output'>
        Variable function: " . $func() . "
        </div></div>";

    // Anonymous Functions
    echo "<div class='example'>
        <h2>Anonymous Functions</h2>";

    $multiply = function($a, $b) {
        return $a * $b;
    };

    echo "<div class='output'>
        Anonymous function: " . $multiply(4, 5) . "</div>";

    // Closure Example
    $message = "Hello";
    $greet = function($name) use ($message) {
        return "$message, $name!";
    };

    echo "<div class='output'>
        Closure with 'use': " . $greet("John") . "</div></div>";

    // Arrow Functions (PHP 7.4+)
    echo "<div class='example'>
        <h2>Arrow Functions</h2>";

    $numbers = [1, 2, 3, 4, 5];
    $doubled = array_map(fn($n) => $n * 2, $numbers);

    echo "<div class='output'>
        Original: " . implode(", ", $numbers) . "<br>
        Doubled: " . implode(", ", $doubled) . "</div></div>";

    // Variadic Functions
    echo "<div class='example'>
        <h2>Variadic Functions</h2>";

    function sum(...$numbers) {
        return array_sum($numbers);
    }

    echo "<div class='output'>
        Sum of 1, 2, 3, 4: " . sum(1, 2, 3, 4) . "<br>
        Sum of 10, 20: " . sum(10, 20) . "</div></div>";

    // Return Type Declarations
    echo "<div class='example'>
        <h2>Return Type Declarations</h2>";

    function divide(float $a, float $b): ?float {
        if ($b == 0) {
            return null;
        }
        return $a / $b;
    }

    echo "<div class='output'>
        10 / 2 = " . divide(10, 2) . "<br>
        10 / 0 = " . var_export(divide(10, 0), true) . "</div></div>";
    ?>
</body>
</html>