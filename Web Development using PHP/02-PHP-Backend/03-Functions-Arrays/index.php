<?php
/* 
 * PHP Functions and Arrays Examples
 * This file demonstrates PHP functions and array operations with detailed explanations
 * Each section includes examples with expected outputs and behavior
 */

// Function with Default Parameters
// Demonstrates function definition with optional parameter values
function greet($name = "Guest", $greeting = "Hello") {  // Function with two optional parameters
                                                       // Example: $name defaults to "Guest" if not provided
                                                       // Example: $greeting defaults to "Hello" if not provided
    return "$greeting, $name!";                        // Returns formatted greeting string
                                                       // Example: "Hello, John!" when name is "John"
}

echo greet("John");             // Call function with only name parameter
                                // Example output: "Hello, John!"
echo greet("Jane", "Hi");       // Call function with both parameters
                                // Example output: "Hi, Jane!"
echo greet();                   // Call function with no parameters (using defaults)
                                // Example output: "Hello, Guest!"

// Function with Return Type Declaration
// Demonstrates type hinting for parameters and return value
function add(float $a, float $b): float {  // Function that accepts and returns float values
                                          // Example: add(3.5, 2.7) expects and returns float
    return $a + $b;                       // Returns sum of two numbers
                                          // Example: 3.5 + 2.7 = 6.2
}

$result = add(3.5, 2.7);    // Call function with float parameters
                            // Example: $result will be 6.2
echo "Sum: $result\n";      // Output the result
                            // Example output: "Sum: 6.2"

// Variable Scope Example
// Demonstrates variable accessibility in different scopes
$globalVar = "I'm global";    // Global variable declaration
                             // Example: Can be accessed from any scope using 'global' keyword

function scopeTest() {
    global $globalVar;        // Access global variable inside function scope
                             // Example: Without 'global', $globalVar would be undefined here
    $localVar = "I'm local"; // Local variable declaration - only exists in function scope
                             // Example: Cannot be accessed outside this function
    
    echo $globalVar . "\n";  // Output global variable value
                             // Example output: "I'm global"
    echo $localVar . "\n";   // Output local variable value
                             // Example output: "I'm local"
}

scopeTest();                 // Call function to demonstrate variable scope behavior
                             // Example: Outputs both global and local variable values

// Array Operations
// Demonstrates creating and manipulating indexed arrays
$fruits = [                 // Define indexed array using short syntax
    "apple",               // First element at index 0
    "banana",              // Second element at index 1
    "orange"               // Third element at index 2
];

// Array push and pop operations
// Shows how to add and remove elements from array end
array_push($fruits, "grape");     // Add element to end of array
                                 // Example: fruits now contains ["apple", "banana", "orange", "grape"]
echo "Last fruit: " . array_pop($fruits) . "\n";  // Remove and return last element
                                                   // Example output: "Last fruit: grape"

// Associative Array Operations
// Demonstrates key-value pair array creation and access
$person = [                     // Define associative array with key-value pairs
    "name" => "John Doe",      // String value with string key
    "age" => 30,              // Integer value with string key
    "city" => "New York"       // String value with string key
];

// Array iteration with key and value
// Shows how to loop through associative array elements
foreach ($person as $key => $value) {  // Iterate over associative array
                                      // Example: first iteration $key="name", $value="John Doe"
    echo "$key: $value\n";            // Output each key-value pair
                                      // Example output: "name: John Doe"
}

// Array Functions
// Demonstrates common array manipulation functions
$numbers = [5, 2, 8, 1, 9];     // Define numeric array
                                // Example: Initial array [5, 2, 8, 1, 9]

sort($numbers);                  // Sort array in ascending order
                                // Example: Array becomes [1, 2, 5, 8, 9]
echo "Sorted numbers: " . implode(", ", $numbers) . "\n";  // Join array elements with comma
                                                          // Example output: "1, 2, 5, 8, 9"

$sum = array_sum($numbers);      // Calculate sum of array elements
                                // Example: 1 + 2 + 5 + 8 + 9 = 25
echo "Sum of numbers: $sum\n";   // Output total sum
                                // Example output: "Sum of numbers: 25"

$filtered = array_filter($numbers, function($n) {  // Filter array using callback function
    return $n > 5;                                // Keep only numbers greater than 5
                                                 // Example: Returns [8, 9]
});

echo "Numbers > 5: " . implode(", ", $filtered) . "\n";  // Output filtered numbers
                                                        // Example output: "8, 9"

// Array mapping
// Demonstrates transforming array elements using callback function
$doubled = array_map(function($n) {    // Transform array using callback
    return $n * 2;                     // Double each number
                                      // Example: [1, 2, 5, 8, 9] becomes [2, 4, 10, 16, 18]
}, $numbers);

echo "Doubled numbers: " . implode(", ", $doubled) . "\n";  // Output transformed numbers
                                                           // Example output: "2, 4, 10, 16, 18"
?>