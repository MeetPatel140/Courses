<?php
/* 
 * PHP Basic Syntax Examples
 * This file demonstrates fundamental PHP concepts with detailed explanations
 * Each section includes examples with expected outputs and behavior
 */

// Variable Declaration and Data Types
// PHP variables start with $ symbol and are case-sensitive
// Example: $name and $Name are different variables
$name = "John";     // String type: Stores text data in quotes
                    // Example output when echo: John
$age = 25;         // Integer type: Whole number without decimals
                    // Example output when echo: 25
$height = 1.75;    // Float type: Decimal number with precision
                    // Example output when echo: 1.75
$isStudent = true; // Boolean type: true or false value
                    // Example output when echo: 1 (true) or nothing (false)

// String Operations and Concatenation
// Demonstrates different ways to combine strings and variables
echo "Hello, " . $name . "!\n";    // String concatenation using dot operator
                                    // Output: Hello, John!
$greeting = "Welcome to PHP";        // Simple string assignment
$message = "$greeting, $name";      // String interpolation - variables expand inside double quotes
                                    // Output: Welcome to PHP, John
echo $message . "\n";              // Using \n for newline in strings

// Arithmetic Operations
// Basic mathematical operations with numeric values
$num1 = 10;                        // First operand
$num2 = 5;                         // Second operand
$sum = $num1 + $num2;              // Addition operator (+)
                                   // Result: 15
$diff = $num1 - $num2;             // Subtraction operator (-)
                                   // Result: 5
$product = $num1 * $num2;          // Multiplication operator (*)
                                   // Result: 50
$quotient = $num1 / $num2;         // Division operator (/)
                                   // Result: 2

// Array Types and Usage
// PHP supports both indexed and associative arrays
$colors = array("red", "green", "blue");  // Indexed array: Elements accessed by number
                                         // Example: $colors[0] returns "red"
$person = array(                         // Associative array: Key-value pairs
    "name" => $name,                    // Key "name" maps to value in $name
    "age" => $age,                      // Key "age" maps to value in $age
    "isStudent" => $isStudent           // Key "isStudent" maps to boolean value
);                                      // Example: $person["name"] returns "John"

// Control Structures - Conditional Statements
// if-else statement for decision making
if ($age >= 18) {                    // Condition: Check if age is 18 or more
    echo "You are an adult\n";       // Executes if condition is true
                                     // Output if age is 25: You are an adult
} else {                            // Alternative code block
    echo "You are a minor\n";        // Executes if condition is false
                                     // Output if age is 16: You are a minor
}

// Control Structures - Loops
// for loop example counting from 1 to 5
echo "Counting from 1 to 5:\n";      // Loop header message
for ($i = 1; $i <= 5; $i++) {        // Initialize $i=1, continue while $i<=5, increment by 1
    echo "Count: $i\n";              // Output current counter value
}                                    // Output: Count: 1 through Count: 5

// Functions - Definition and Usage
// Function to calculate rectangle area with parameters
function calculateArea($length, $width) {  // Function with two parameters
    return $length * $width;              // Returns the calculated area
}                                         // Example: calculateArea(10, 5) returns 50

// Function usage example
$length = 10;                             // First argument for function
$width = 5;                               // Second argument for function
$area = calculateArea($length, $width);   // Function call with arguments
echo "Rectangle area: $area\n";          // Output: Rectangle area: 50
?>