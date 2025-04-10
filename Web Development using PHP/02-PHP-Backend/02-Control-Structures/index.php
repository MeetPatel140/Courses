<?php
/* 
 * PHP Control Structures Examples
 * This file demonstrates PHP control flow with detailed explanations
 * Each section includes examples with expected outputs and behavior
 */

// If-Else Statement Example
// Demonstrates nested conditional statements and multiple conditions
$temperature = 25;    // Current temperature in Celsius
                     // Example value: 25
$humidity = 70;      // Current humidity percentage
                     // Example value: 70

// Nested if-else with multiple conditions
// Shows how to handle multiple decision paths based on conditions
if ($temperature > 30) {           // Check if temperature exceeds 30°C
                                   // Example: if temperature is 35, this condition is true
    if ($humidity > 80) {          // Check humidity level when temperature is high
                                   // Example: if humidity is 85, outputs "Hot and humid day"
        echo "Hot and humid day\n";  // Output message for hot and humid conditions
    } else {                       // Alternative path when humidity is not high
        echo "Hot but dry day\n";    // Output message for hot but not humid conditions
                                   // Example: if humidity is 60, outputs "Hot but dry day"
    }
} else if ($temperature > 20) {   // Check if temperature is between 20-30°C
                                   // Example: with temperature 25, outputs "Pleasant weather"
    echo "Pleasant weather\n";      // Output message for moderate temperature
} else {                          // Temperature is 20°C or below
    echo "Cool weather\n";          // Output message for cool temperature
                                   // Example: if temperature is 15, outputs "Cool weather"
}

// Switch Statement Example
// Demonstrates using switch statement for multiple conditional paths
$dayOfWeek = date('l');   // Get current day name using PHP date function
                          // Example: Returns 'Monday' if it's Monday
switch ($dayOfWeek) {     // Start switch block with day of week variable
    case 'Monday':         // First case: Check if it's Monday
        echo "Start of work week\n";     // Output special Monday message
                                        // Example output: "Start of work week"
        break;             // Exit the switch block after executing code
    case 'Friday':         // Check if it's Friday
        echo "Weekend is coming!\n";     // Output Friday message
                                        // Example output: "Weekend is coming!"
        break;             // Exit switch block
    case 'Saturday':       // Check for weekend days
    case 'Sunday':         // Multiple cases can share same code block
        echo "It's weekend!\n";         // Output weekend message
                                        // Example output: "It's weekend!"
        break;             // Exit switch block
    default:               // Handles all other days (Tuesday, Wednesday, Thursday)
        echo "Regular working day\n";    // Output default message
                                        // Example output: "Regular working day"
}

// While Loop Example
// Demonstrates basic while loop with counter and accumulator pattern
$counter = 1;           // Initialize counter variable to start value
                        // Example: counter starts at 1
$sum = 0;              // Initialize sum accumulator to zero
                        // Example: sum starts at 0
while ($counter <= 5) { // Continue loop while counter is less than or equal to 5
                        // Example: loop runs for counter values 1,2,3,4,5
    $sum += $counter;   // Add current counter value to sum
                        // Example: when counter is 3, adds 3 to sum
    $counter++;        // Increment counter by 1 for next iteration
                        // Example: if counter was 3, becomes 4
}
echo "Sum of numbers 1 to 5: $sum\n"; // Display final sum value
                                       // Example output: "Sum of numbers 1 to 5: 15"

// Do-While Loop Example
// Demonstrates do-while loop with random number generation
$dice = 0;              // Initialize dice variable for storing roll result
                        // Example: starts at 0 before first roll
$attempts = 0;          // Initialize counter to track number of attempts
                        // Example: starts at 0 attempts
do {
    $dice = rand(1, 6); // Generate random number between 1 and 6
                         // Example: might generate 4 on first roll
    $attempts++;         // Increment attempt counter after each roll
                         // Example: first roll makes attempts = 1
    echo "Rolled: $dice\n"; // Display the result of current roll
                            // Example output: "Rolled: 4"
} while ($dice != 6);   // Continue loop until we roll a 6
                        // Example: if dice is 4, loop continues
echo "Took $attempts attempts to roll a 6\n"; // Display total number of attempts
                                               // Example output: "Took 3 attempts to roll a 6"

// Foreach Loop Example
// Demonstrates iterating over associative arrays with key-value pairs
$fruits = array(         // Array of fruits with their colors
    'apple' => 'red',    // Key 'apple' maps to value 'red'
    'banana' => 'yellow', // Key 'banana' maps to value 'yellow'
    'grape' => 'purple'  // Key 'grape' maps to value 'purple'
);

foreach ($fruits as $fruit => $color) {  // Iterate over fruits array
                                        // Example: first iteration $fruit='apple', $color='red'
    echo "$fruit is $color\n";          // Output each fruit and its color
                                        // Example output: "apple is red"
}

// Break and Continue Example
// Demonstrates loop control statements for skipping or ending iterations
for ($i = 1; $i <= 10; $i++) {        // Loop from 1 to 10
                                      // Example: i starts at 1 and increments each iteration
    if ($i == 5) continue;            // Skip iteration when i is 5
                                      // Example: when i=5, skips to next iteration
    if ($i == 8) break;               // Stop loop when i reaches 8
                                      // Example: when i=8, exits loop completely
    echo "Current number: $i\n";       // Output current number
                                      // Example output: "Current number: 1", "Current number: 2", etc.
}
?>