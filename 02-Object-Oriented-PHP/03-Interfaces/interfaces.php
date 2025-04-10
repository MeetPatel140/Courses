<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Interfaces</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>PHP Interfaces</h1>

    <?php
    // Basic Interface Definition
    interface Drawable {
        public function draw();
        public function getArea();
    }

    interface Resizable {
        public function resize($scale);
    }

    // Implementing Multiple Interfaces
    class Rectangle implements Drawable, Resizable {
        private $width;
        private $height;

        public function __construct($width, $height) {
            $this->width = $width;
            $this->height = $height;
        }

        public function draw() {
            return "Drawing a rectangle with width {$this->width} and height {$this->height}";
        }

        public function getArea() {
            return $this->width * $this->height;
        }

        public function resize($scale) {
            $this->width *= $scale;
            $this->height *= $scale;
        }
    }

    class Circle implements Drawable, Resizable {
        private $radius;

        public function __construct($radius) {
            $this->radius = $radius;
        }

        public function draw() {
            return "Drawing a circle with radius {$this->radius}";
        }

        public function getArea() {
            return pi() * $this->radius * $this->radius;
        }

        public function resize($scale) {
            $this->radius *= $scale;
        }
    }

    // Using Interfaces
    echo "<div class='example'>
        <h2>Basic Interface Usage</h2>";
    
    $rectangle = new Rectangle(5, 3);
    $circle = new Circle(4);

    echo "<div class='output'>
        Rectangle: " . $rectangle->draw() . "<br>
        Rectangle Area: " . $rectangle->getArea() . "<br><br>
        Circle: " . $circle->draw() . "<br>
        Circle Area: " . number_format($circle->getArea(), 2) . "</div>";

    // Type Hinting with Interfaces
    echo "<h2>Type Hinting with Interfaces</h2>";
    function drawShape(Drawable $shape) {
        return $shape->draw();
    }

    function resizeAndDraw(Drawable $shape, $scale) {
        if ($shape instanceof Resizable) {
            $shape->resize($scale);
            return $shape->draw();
        }
        return "Shape cannot be resized";
    }

    echo "<div class='output'>
        Drawing shapes:<br>
        " . drawShape($rectangle) . "<br>
        " . drawShape($circle) . "<br><br>
        After resizing (scale: 2):<br>
        " . resizeAndDraw($rectangle, 2) . "<br>
        " . resizeAndDraw($circle, 2) . "</div>";

    // Interface Segregation Example
    echo "<h2>Interface Segregation</h2>";
    interface Colorable {
        public function setColor($color);
        public function getColor();
    }

    class ColoredRectangle extends Rectangle implements Colorable {
        private $color;

        public function setColor($color) {
            $this->color = $color;
        }

        public function getColor() {
            return $this->color;
        }

        public function draw() {
            return parent::draw() . " in {$this->color} color";
        }
    }

    $coloredRect = new ColoredRectangle(4, 3);
    $coloredRect->setColor("blue");

    echo "<div class='output'>
        " . $coloredRect->draw() . "<br>
        Color: " . $coloredRect->getColor() . "</div></div>";
    ?>
</body>
</html>