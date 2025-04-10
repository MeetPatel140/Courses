<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Syntax Basics - Hello World</title>
</head>
<body>
    <h1>PHP Syntax Basics</h1>

    <?php
    // This is a single-line comment
    
    /*
     * This is a multi-line comment
     * Used for longer explanations
     * or documentation blocks
     */

    /**
     * This is a DocBlock comment
     * Used for documenting functions, classes, and methods
     * @author Your Name
     * @version 1.0
     */

    # This is also a single-line comment (shell-style)

    // Basic output using echo
    echo "<h2>Hello, World!</h2>";

    // Using print (alternative output method)
    print "<p>This is a basic PHP script.</p>";

    // Variables and string concatenation
    $language = "PHP";
    $version = 7.4;
    echo "<p>We are using " . $language . " version " . $version . "</p>";

    // Using variables within strings (string interpolation)
    echo "<p>We are using $language version $version</p>";

    // HTML can be mixed with PHP
    ?>

    <h3>Different PHP Tags</h3>

    <?php echo "<p>Standard PHP tags</p>"; ?>

    <?= "<p>Short echo tag (shorthand for <?php echo)</p>" ?>

    <div class="examples">
        <?php
        // Demonstrating statement termination
        echo "<p>Statements must end with a semicolon";
        echo "<p>Missing semicolon will cause an error</p>";

        // Variable naming conventions
        $validName = "Valid variable name";
        $valid_name = "Also valid";
        $ValidName = "Valid but not conventional";
        // $123invalid = "Invalid - cannot start with number";
        // $invalid-name = "Invalid - cannot use hyphen";

        // Displaying the variables
        echo "<p>$validName</p>";
        echo "<p>$valid_name</p>";
        echo "<p>$ValidName</p>";
        ?>
    </div>

    <footer>
        <p>View the source code to see the PHP syntax examples.</p>
    </footer>
</body>
</html>