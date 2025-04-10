<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Inheritance</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>PHP Inheritance</h1>

    <?php
    // Base Class
    class Vehicle {
        protected $brand;
        protected $model;
        protected $year;

        public function __construct($brand, $model, $year) {
            $this->brand = $brand;
            $this->model = $model;
            $this->year = $year;
        }

        public function getInfo() {
            return "Brand: {$this->brand}, Model: {$this->model}, Year: {$this->year}";
        }

        public function start() {
            return "The vehicle is starting...";
        }
    }

    // Derived Class - Car
    class Car extends Vehicle {
        private $numDoors;
        private $fuelType;

        public function __construct($brand, $model, $year, $numDoors, $fuelType) {
            parent::__construct($brand, $model, $year);
            $this->numDoors = $numDoors;
            $this->fuelType = $fuelType;
        }

        public function getInfo() {
            return parent::getInfo() . ", Doors: {$this->numDoors}, Fuel: {$this->fuelType}";
        }

        public function honk() {
            return "Beep! Beep!";
        }
    }

    // Derived Class - Motorcycle
    class Motorcycle extends Vehicle {
        private $engineSize;
        private $hasABS;

        public function __construct($brand, $model, $year, $engineSize, $hasABS) {
            parent::__construct($brand, $model, $year);
            $this->engineSize = $engineSize;
            $this->hasABS = $hasABS;
        }

        public function getInfo() {
            return parent::getInfo() . ", Engine: {$this->engineSize}cc, ABS: " . ($this->hasABS ? 'Yes' : 'No');
        }

        public function wheelie() {
            return "Performing a wheelie!";
        }
    }

    // Creating and Using Objects
    echo "<div class='example'>
        <h2>Basic Inheritance</h2>";
    
    $car = new Car("Toyota", "Camry", 2022, 4, "Gasoline");
    $motorcycle = new Motorcycle("Honda", "CBR", 2023, 1000, true);

    echo "<div class='output'>
        Car Info: " . $car->getInfo() . "<br>
        Car Action: " . $car->start() . " " . $car->honk() . "<br><br>
        Motorcycle Info: " . $motorcycle->getInfo() . "<br>
        Motorcycle Action: " . $motorcycle->start() . " " . $motorcycle->wheelie() . "</div>";

    // Method Override Example
    echo "<h2>Method Override</h2>";
    $vehicles = [$car, $motorcycle];

    echo "<div class='output'>";
    foreach ($vehicles as $vehicle) {
        echo "Vehicle Type: " . get_class($vehicle) . "<br>";
        echo "Info: " . $vehicle->getInfo() . "<br><br>";
    }
    echo "</div>";

    // Type Hinting Example
    echo "<h2>Type Hinting</h2>";
    function startVehicle(Vehicle $vehicle) {
        return get_class($vehicle) . ": " . $vehicle->start();
    }

    echo "<div class='output'>
        " . startVehicle($car) . "<br>
        " . startVehicle($motorcycle) . "</div></div>";
    ?>
</body>
</html>