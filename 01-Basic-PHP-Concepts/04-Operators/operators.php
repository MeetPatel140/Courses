<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Operators</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>PHP Operators</h1>

    <?php
    // Arithmetic Operators
    echo "<div class='example'>
        <h2>Arithmetic Operators</h2>";
    $a = 10;
    $b = 3;
    echo "<div class='output'>
        Addition: $a + $b = " . ($a + $b) . "<br>
        Subtraction: $a - $b = " . ($a - $b) . "<br>
        Multiplication: $a * $b = " . ($a * $b) . "<br>
        Division: $a / $b = " . ($a / $b) . "<br>
        Modulus: $a % $b = " . ($a % $b) . "<br>
        Exponentiation: $a ** $b = " . ($a ** $b) . "<br>
        </div>";

    // Assignment Operators
    echo "<h2>Assignment Operators</h2>";
    $x = 5;
    echo "<div class='output'>
        Basic assignment: $x<br>";
    $x += 3;  // Addition
    echo "After += 3: $x<br>";
    $x -= 2;  // Subtraction
    echo "After -= 2: $x<br>";
    $x *= 4;  // Multiplication
    echo "After *= 4: $x<br>";
    $x /= 2;  // Division
    echo "After /= 2: $x<br>";
    $x %= 3;  // Modulus
    echo "After %= 3: $x</div>";

    // Comparison Operators
    echo "<h2>Comparison Operators</h2>";
    $a = 5;
    $b = "5";
    $c = 8;
    echo "<div class='output'>
        Equal (==): $a == $b = " . var_export($a == $b, true) . "<br>
        Identical (===): $a === $b = " . var_export($a === $b, true) . "<br>
        Not equal (!=): $a != $c = " . var_export($a != $c, true) . "<br>
        Greater than: $a > $c = " . var_export($a > $c, true) . "<br>
        Less than: $a < $c = " . var_export($a < $c, true) . "<br>
        Greater than or equal: $a >= $c = " . var_export($a >= $c, true) . "<br>
        Less than or equal: $a <= $c = " . var_export($a <= $c, true) . "</div>";

    // Logical Operators
    echo "<h2>Logical Operators</h2>";
    $p = true;
    $q = false;
    echo "<div class='output'>
        AND (&&): $p && $q = " . var_export($p && $q, true) . "<br>
        OR (||): $p || $q = " . var_export($p || $q, true) . "<br>
        NOT (!): !$p = " . var_export(!$p, true) . "<br>
        XOR: $p xor $q = " . var_export($p xor $q, true) . "</div>";

    // String Operators
    echo "<h2>String Operators</h2>";
    $str1 = "Hello";
    $str2 = "World";
    echo "<div class='output'>
        Concatenation (.): $str1 . ' ' . $str2 = " . ($str1 . ' ' . $str2) . "<br>
        Concatenation assignment (.=): ";
    $str1 .= " $str2";
    echo "$str1</div>";

    // Array Operators
    echo "<h2>Array Operators</h2>";
    $arr1 = [1, 2, 3];
    $arr2 = [4, 5, 6];
    echo "<div class='output'>
        Union (+): ";
    print_r($arr1 + $arr2);
    echo "<br>Equality (==): " . var_export($arr1 == $arr2, true) . "<br>
        Identity (===): " . var_export($arr1 === $arr2, true) . "</div>";

    // Increment/Decrement Operators
    echo "<h2>Increment/Decrement Operators</h2>";
    $i = 5;
    echo "<div class='output'>
        Initial value: $i<br>
        Pre-increment (++i): " . (++$i) . "<br>
        Pre-decrement (--i): " . (--$i) . "<br>
        Post-increment (i++): " . ($i++) . " (after: $i)<br>
        Post-decrement (i--): " . ($i--) . " (after: $i)</div>";

    // Ternary and Null Coalescing Operators
    echo "<h2>Ternary and Null Coalescing Operators</h2>";
    $age = 20;
    $name = null;
    $default = "Anonymous";
    echo "<div class='output'>
        Ternary: " . ($age >= 18 ? "Adult" : "Minor") . "<br>
        Null coalescing: " . ($name ?? $default) . "</div>";

    // Type Operators
    echo "<h2>Type Operators</h2>";
    class MyClass {}
    $obj = new MyClass();
    echo "<div class='output'>
        instanceof: " . var_export($obj instanceof MyClass, true) . "</div>";
    echo "</div>";
    ?>
</body>
</html>