<link rel="stylesheet" href="style.css">

<?php 

require "Database.php";
$config = require "config.php";

$db = new Database($config["database"]);

// Iegūstam dāvanu nosaukumus no "gifts" tabulas
$gifts = $db->query("SELECT name FROM gifts")->fetchAll(PDO::FETCH_ASSOC);
$gift_names = array_column($gifts, 'name');

// Iegūstam bērnu informāciju un vēstules tekstus
$children = $db->query(
    "SELECT children.firstname, children.middlename, children.surname, children.age, letters.letter_text 
    FROM children
    LEFT JOIN letters ON children.id = letters.sender_id"
)->fetchAll(PDO::FETCH_ASSOC);

echo "<div class='christmas-container'>";
foreach ($children as $x) {
    $letter_text = $x['letter_text'];
    $wishes = [];

    // Pārmeklējam vēstules tekstu un meklējam dāvanu nosaukumus
    foreach ($gift_names as $gift) {
        if (stripos($letter_text, $gift) !== false) {
            // Ja dāvanu nosaukums ir atrasts, pievienojam to vēlmju sarakstam
            $wishes[] = $gift;
            // Highlighting the gift in the letter text
            $letter_text = preg_replace("/\b" . preg_quote($gift, '/') . "\b/i", "<span class='highlight'>$0</span>", $letter_text);
        }
    }

    // Izveidojam vēstules parādīšanu
    echo "
    <div class='christmas-box'>
        <h3><span>Name:</span> " . htmlspecialchars($x['firstname']) . " " . htmlspecialchars($x['middlename']) . " " . htmlspecialchars($x['surname']) . "</h3>
        <p><span>Age:</span> " . htmlspecialchars($x['age']) . "</p>
        <p><span>Letter:</span> " . nl2br($letter_text) . "</p>"; // Do not apply htmlspecialchars here

    // Remove the full wish list section
    echo "</div>";
}
echo "</div>";

?> 
