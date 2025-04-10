<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Arrays</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>PHP Arrays</h1>

    <?php
    // Array Creation
    echo "<div class='example'>
        <h2>Array Creation</h2>";

    // Array syntax
    $array1 = array(1, 2, 3, 4, 5);
    $array2 = [6, 7, 8, 9, 10];  // Short array syntax (PHP 5.4+)

    echo "<div class='output'>
        Array 1: " . implode(", ", $array1) . "<br>
        Array 2: " . implode(", ", $array2) . "
        </div>";

    // Associative Arrays
    echo "<h2>Associative Arrays</h2>";
    $person = [
        'name' => 'John Doe',
        'age' => 30,
        'city' => 'New York',
        'email' => 'john@example.com'
    ];

    echo "<div class='output'>";
    foreach ($person as $key => $value) {
        echo ucfirst($key) . ": $value<br>";
    }
    echo "</div>";

    // Multidimensional Arrays
    echo "<h2>Multidimensional Arrays</h2>";
    $students = [
        ['name' => 'Alice', 'grade' => 85],
        ['name' => 'Bob', 'grade' => 92],
        ['name' => 'Charlie', 'grade' => 78]
    ];

    echo "<div class='output'>
        <table border='1' cellpadding='5'>
            <tr><th>Name</th><th>Grade</th></tr>";
    foreach ($students as $student) {
        echo "<tr><td>{$student['name']}</td><td>{$student['grade']}</td></tr>";
    }
    echo "</table></div></div>";

    // Array Functions
    echo "<div class='example'>
        <h2>Array Functions</h2>";

    // Array Manipulation
    $fruits = ['apple', 'banana', 'orange'];
    echo "<h3>Array Manipulation</h3>
        <div class='output'>";
    array_push($fruits, 'grape');  // Add to end
    echo "After push: " . implode(", ", $fruits) . "<br>";
    array_unshift($fruits, 'mango');  // Add to beginning
    echo "After unshift: " . implode(", ", $fruits) . "<br>";
    array_pop($fruits);  // Remove from end
    echo "After pop: " . implode(", ", $fruits) . "<br>";
    array_shift($fruits);  // Remove from beginning
    echo "After shift: " . implode(", ", $fruits) . "</div>";

    // Array Search and Filter
    echo "<h3>Array Search and Filter</h3>";
    $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    $even = array_filter($numbers, fn($n) => $n % 2 == 0);
    $doubled = array_map(fn($n) => $n * 2, $numbers);

    echo "<div class='output'>
        Original: " . implode(", ", $numbers) . "<br>
        Even numbers: " . implode(", ", $even) . "<br>
        Doubled: " . implode(", ", $doubled) . "</div>";

    // Array Sorting
    echo "<h3>Array Sorting</h3>";
    $unsorted = [5, 2, 8, 1, 9];
    $assoc = ['banana' => 2, 'apple' => 1, 'orange' => 3];

    sort($unsorted);  // Sort indexed array
    asort($assoc);    // Sort associative array by values
    ksort($assoc);    // Sort associative array by keys

    echo "<div class='output'>
        Sorted array: " . implode(", ", $unsorted) . "<br>
        Sorted by value: ";
    print_r($assoc);
    echo "</div>";

    // Array Operations
    echo "<h3>Array Operations</h3>";
    $arr1 = [1, 2, 3];
    $arr2 = [4, 5, 6];

    echo "<div class='output'>
        Merge: " . implode(", ", array_merge($arr1, $arr2)) . "<br>
        Slice: " . implode(", ", array_slice($arr1, 1)) . "<br>
        Sum: " . array_sum($arr1) . "<br>
        Count: " . count($arr1) . "</div>";

    // Array Utilities
    echo "<h3>Array Utilities</h3>";
    $keys = array_keys($person);
    $values = array_values($person);
    $flipped = array_flip($keys);

    echo "<div class='output'>
        Keys: " . implode(", ", $keys) . "<br>
        Values: " . implode(", ", $values) . "<br>
        Exists 'name': " . (array_key_exists('name', $person) ? 'true' : 'false') . "<br>
        In array 'John': " . (in_array('John Doe', $values) ? 'true' : 'false') . "</div></div>";
    ?>
</body>
</html>