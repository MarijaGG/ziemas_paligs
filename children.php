<link rel="stylesheet" href="style.css">

<?php 
require "Database.php";
$config = require "config.php";

$db = new Database($config["database"]);

// Fetch gift names from the "gifts" table
$gifts = $db->query("SELECT name FROM gifts")->fetchAll(PDO::FETCH_ASSOC);
$gift_names = array_column($gifts, 'name');

// Fetch children info and letter texts
$children = $db->query(
    "SELECT children.firstname, children.middlename, children.surname, children.age, letters.letter_text 
    FROM children
    LEFT JOIN letters ON children.id = letters.sender_id"
)->fetchAll(PDO::FETCH_ASSOC);

echo "<div class='christmas-container'>";
foreach ($children as $x) {
    $letter_text = $x['letter_text'];
    $wishes = [];

    // Search for gift names in the letter text
    foreach ($gift_names as $gift) {
        if (stripos($letter_text, $gift) !== false) {
            // Add found gift names to the wishes list
            $wishes[] = $gift;
            // Highlight the gift name in the letter text
            $letter_text = preg_replace("/\b" . preg_quote($gift, '/') . "\b/i", "<span class='highlight'>$0</span>", $letter_text);
        }
    }

    // Display the child's letter with their info
    echo "
    <div class='christmas-box'>
        <h3><span>Name:</span> " . htmlspecialchars($x['firstname']) . " " . htmlspecialchars($x['middlename']) . " " . htmlspecialchars($x['surname']) . "</h3>
        <p><span>Age:</span> " . htmlspecialchars($x['age']) . "</p>
        <p><span>Letter:</span> " . nl2br($letter_text) . "</p> 
    </div>";
}
echo "</div>";
?>

<style>
/* Overall page styling */
body {
    background-color: #f4f4f4;
    margin: 0;
    font-family: 'Georgia', serif;
}

/* Grid container for Christmas boxes */
.christmas-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 equal columns */
    gap: 20px;
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Individual Christmas box styling */
.christmas-box {
    border: 3px solid #b22222; /* Festive red border */
    background: linear-gradient(to bottom, #fffaf0, #ffefd5); /* Gradient background */
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    text-align: center; /* Centers the text horizontally */
    display: flex;
    flex-direction: column; /* Ensures text is stacked vertically */
    justify-content: center; /* Centers content vertically */
    align-items: center; /* Centers content horizontally */
    min-height: 200px; /* Ensures the box has enough height for centering */
}

/* Hover effect */
.christmas-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 18px rgba(0, 0, 0, 0.4);
    border-color: #006400; /* Green border on hover */
}

/* Heading - Name styling */
.christmas-box h3 {
    color: #b22222; /* Deep red */
    font-size: 20px;
    margin-bottom: 10px;
    font-weight: bold;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

/* Labels for Name, Age, Letter */
.christmas-box p {
    margin: 8px 0;
    font-size: 16px;
    color: #333;
    line-height: 1.5;
}

.christmas-box p span {
    font-weight: bold;
    color: #006400; /* Festive green */
    font-family: 'Arial', sans-serif;
}

/* Responsive adjustments */
@media (max-width: 900px) {
    .christmas-container {
        grid-template-columns: repeat(2, 1fr); /* 2 columns for medium screens */
    }
}

@media (max-width: 600px) {
    .christmas-container {
        grid-template-columns: 1fr; /* 1 column for small screens */
    }
    .christmas-box {
        padding: 15px;
    }
}

/* Highlighted gift text with Christmasy vibes */
.highlight {
    color: #32cd32; /* Bright green text to give a festive, Christmas vibe */
    font-weight: bold; /* Bold text to make it stand out */
    border-radius: 5px; /* Rounded corners for a softer look */
    text-shadow: 0 0 10px rgba(50, 205, 50, 0.8), 0 0 20px rgba(50, 205, 50, 0.6), 0 0 30px rgba(50, 205, 50, 0.4); /* Glowing effect on the text */
}
</style>
