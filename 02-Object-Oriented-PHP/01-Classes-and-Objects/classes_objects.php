<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Classes and Objects</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>PHP Classes and Objects</h1>

    <?php
    // Basic Class Definition
    class Person {
        // Properties
        private $name;
        private $age;
        private $email;

        // Constructor
        public function __construct($name, $age, $email) {
            $this->name = $name;
            $this->age = $age;
            $this->email = $email;
        }

        // Getter methods
        public function getName() {
            return $this->name;
        }

        public function getAge() {
            return $this->age;
        }

        public function getEmail() {
            return $this->email;
        }

        // Setter methods
        public function setName($name) {
            $this->name = $name;
        }

        public function setAge($age) {
            if ($age > 0) {
                $this->age = $age;
            }
        }

        public function setEmail($email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->email = $email;
            }
        }

        // Method to display person info
        public function getInfo() {
            return "Name: {$this->name}, Age: {$this->age}, Email: {$this->email}";
        }
    }

    // Creating Objects
    echo "<div class='example'>
        <h2>Creating and Using Objects</h2>";
    
    $person1 = new Person("John Doe", 30, "john@example.com");
    $person2 = new Person("Jane Smith", 25, "jane@example.com");

    echo "<div class='output'>
        Person 1: " . $person1->getInfo() . "<br>
        Person 2: " . $person2->getInfo() . "</div>";

    // Modifying Object Properties
    echo "<h2>Modifying Object Properties</h2>";
    $person1->setAge(31);
    $person1->setEmail("john.doe@example.com");

    echo "<div class='output'>
        Updated Person 1: " . $person1->getInfo() . "</div>";

    // Class with Static Members
    class Counter {
        private static $count = 0;

        public static function increment() {
            self::$count++;
        }

        public static function getCount() {
            return self::$count;
        }
    }

    echo "<h2>Static Members</h2>";
    Counter::increment();
    Counter::increment();
    Counter::increment();

    echo "<div class='output'>
        Counter value: " . Counter::getCount() . "</div>";

    // Class Constants
    class MathConstants {
        const PI = 3.14159;
        const E = 2.71828;
    }

    echo "<h2>Class Constants</h2>";
    echo "<div class='output'>
        PI: " . MathConstants::PI . "<br>
        E: " . MathConstants::E . "</div>";

    // Object Comparison
    echo "<h2>Object Comparison</h2>";
    $person3 = new Person("John Doe", 30, "john@example.com");
    $person4 = $person3;
    $person5 = new Person("John Doe", 30, "john@example.com");

    echo "<div class='output'>
        $person3 === $person4: " . (($person3 === $person4) ? 'true' : 'false') . "<br>
        $person3 === $person5: " . (($person3 === $person5) ? 'true' : 'false') . "</div></div>";
    ?>
</body>
</html>