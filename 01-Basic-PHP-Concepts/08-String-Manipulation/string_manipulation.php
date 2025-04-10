 Initi<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP String Manipulation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .example { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .output { background: #f5f5f5; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>PHP String Manipulation</h1>

    <?php
    // String Creation and Concatenation
    echo "<div class='example'>
        <h2>String Creation and Concatenation</h2>";
    
    $str1 = 'Hello';
    $str2 = "World";
    $concat1 = $str1 . ' ' . $str2;  // Using dot operator
    $concat2 = "$str1 $str2";       // Using double quotes

    echo "<div class='output'>
        Using dot operator: $concat1<br>
        Using double quotes: $concat2</div>";

    // String Functions
    echo "<h2>String Functions</h2>";
    $text = "  Hello World! This is a sample text.  ";
    
    echo "<div class='output'>
        Original text: '$text'<br>
        Length: " . strlen($text) . "<br>
        Trimmed: '" . trim($text) . "'<br>
        Uppercase: " . strtoupper($text) . "<br>
        Lowercase: " . strtolower($text) . "<br>
        First char uppercase: " . ucfirst(trim($text)) . "<br>
        Words uppercase: " . ucwords(trim($text)) . "</div>";

    // String Search and Replace
    echo "<h2>String Search and Replace</h2>";
    $sentence = "The quick brown fox jumps over the lazy dog.";
    $search = "fox";
    $replace = "cat";

    echo "<div class='output'>
        Original: $sentence<br>
        Position of 'fox': " . strpos($sentence, $search) . "<br>
        Replace 'fox' with 'cat': " . str_replace($search, $replace, $sentence) . "<br>
        Substring (words 2-3): " . substr($sentence, 4, 11) . "</div>";

    // String Splitting and Joining
    echo "<h2>String Splitting and Joining</h2>";
    $csv = "apple,banana,orange,grape";
    $array = explode(",", $csv);
    $joined = implode(" - ", $array);

    echo "<div class='output'>
        CSV string: $csv<br>
        Array: " . print_r($array, true) . "<br>
        Joined with dashes: $joined</div>";

    // String Formatting
    echo "<h2>String Formatting</h2>";
    $name = "John";
    $age = 30;
    $height = 1.85;
    $date = date('Y-m-d');

    echo "<div class='output'>
        sprintf: " . sprintf("Name: %s, Age: %d, Height: %.2f", $name, $age, $height) . "<br>
        number_format: " . number_format($height, 2) . "<br>
        date format: $date</div>";

    // Special Characters and Escaping
    echo "<h2>Special Characters and Escaping</h2>";
    $special = "This string contains \"quotes\" and a \t tab";
    $html = "<b>Bold text</b> and <i>italic text</i>";

    echo "<div class='output'>
        Escaped characters: $special<br>
        HTML escaped: " . htmlspecialchars($html) . "<br>
        HTML entities: " . htmlentities($html) . "</div>";

    // Regular Expressions
    echo "<h2>Regular Expressions</h2>";
    $pattern = "/\b\w{4}\b/";  // Match 4-letter words
    $text = "The quick brown fox jumps over the lazy dog";
    preg_match_all($pattern, $text, $matches);

    echo "<div class='output'>
        Text: $text<br>
        Four letter words: " . implode(", ", $matches[0]) . "<br>
        Email validation: " . (preg_match("/^[\w.-]+@[\w.-]+\.\w+$/", "test@example.com") ? "Valid" : "Invalid") . "</div></div>";
    ?>
</body>
</html>