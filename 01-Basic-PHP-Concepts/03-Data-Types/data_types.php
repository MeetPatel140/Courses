<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Data Types</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>PHP Data Types</h1>

    <?php
    // Scalar Types
    echo "<div class='example'>
        <h2>Scalar Types</h2>";

    // Integer
    $int = 42;
    $negative = -17;
    $octal = 0755;  // Octal number (starts with 0)
    $hex = 0xFF;    // Hexadecimal number (starts with 0x)
    echo "<h3>Integer</h3>
        <div class='output'>
        Regular integer: $int<br>
        Negative integer: $negative<br>
        Octal number: $octal<br>
        Hexadecimal number: $hex<br>
        Type: " . gettype($int) . "
        </div>";

    // Float/Double
    $float = 3.14;
    $scientific = 2.5e3;  // 2.5 * 10^3
    echo "<h3>Float</h3>
        <div class='output'>
        Regular float: $float<br>
        Scientific notation: $scientific<br>
        Type: " . gettype($float) . "
        </div>";

    // String
    $single = 'Single quoted';
    $double = "Double quoted with variable: $int";
    $heredoc = <<<EOT
This is a heredoc string.
It can span multiple lines
and interpolate variables: $int
EOT;
    echo "<h3>String</h3>
        <div class='output'>
        Single quoted: $single<br>
        Double quoted: $double<br>
        Heredoc: <pre>$heredoc</pre>
        Type: " . gettype($single) . "
        </div>";

    // Boolean
    $true = true;
    $false = false;
    echo "<h3>Boolean</h3>
        <div class='output'>
        True value: " . ($true ? 'true' : 'false') . "<br>
        False value: " . ($false ? 'true' : 'false') . "<br>
        Type: " . gettype($true) . "
        </div>
    </div>";

    // Compound Types
    echo "<div class='example'>
        <h2>Compound Types</h2>";

    // Array
    $indexed = ['apple', 'banana', 'orange'];
    $associative = [
        'name' => 'John',
        'age' => 25,
        'city' => 'New York'
    ];
    $multidimensional = [
        'fruits' => ['apple', 'banana'],
        'numbers' => [1, 2, 3]
    ];

    echo "<h3>Array</h3>
        <div class='output'>
        Indexed array: " . implode(', ', $indexed) . "<br>
        Associative array: <pre>" . print_r($associative, true) . "</pre>
        Multidimensional array: <pre>" . print_r($multidimensional, true) . "</pre>
        Type: " . gettype($indexed) . "
        </div>";

    // Object
    class Person {
        public $name;
        public $age;

        public function __construct($name, $age) {
            $this->name = $name;
            $this->age = $age;
        }
    }

    $person = new Person('John', 25);
    echo "<h3>Object</h3>
        <div class='output'>
        Person object: <pre>" . print_r($person, true) . "</pre>
        Type: " . gettype($person) . "
        </div>
    </div>";

    // Special Types
    echo "<div class='example'>
        <h2>Special Types</h2>";

    // NULL
    $null = null;
    echo "<h3>NULL</h3>
        <div class='output'>
        Null value: " . var_export($null, true) . "<br>
        Type: " . gettype($null) . "
        </div>";

    // Resource
    $file = fopen(__FILE__, 'r');
    echo "<h3>Resource</h3>
        <div class='output'>
        File handle resource: " . $file . "<br>
        Type: " . gettype($file) . "
        </div>";
    fclose($file);

    // Type Casting
    echo "<h3>Type Casting</h3>
        <div class='output'>";
    $number = '42';
    echo "Original: $number (" . gettype($number) . ")<br>";
    echo "To integer: " . (int)$number . " (" . gettype((int)$number) . ")<br>";
    echo "To float: " . (float)$number . " (" . gettype((float)$number) . ")<br>";
    echo "To boolean: " . var_export((bool)$number, true) . " (" . gettype((bool)$number) . ")<br>";
    echo "</div>";

    // Type Checking
    echo "<h3>Type Checking Functions</h3>
        <div class='output'>";
    $testVar = 42;
    echo "Variable: $testVar<br>";
    echo "is_int(): " . var_export(is_int($testVar), true) . "<br>";
    echo "is_string(): " . var_export(is_string($testVar), true) . "<br>";
    echo "is_numeric(): " . var_export(is_numeric($testVar), true) . "<br>";
    echo "isset(): " . var_export(isset($testVar), true) . "<br>";
    echo "empty(): " . var_export(empty($testVar), true) . "<br>";
    echo "</div>";
    echo "</div>";
    ?>
</body>
</html>