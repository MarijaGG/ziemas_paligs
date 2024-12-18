<link rel="stylesheet" href="style2.css">

<?php

require "Database.php"; 
$config = require "config.php"; 

// Create a new Database object
$db = new Database($config["database"]);

// Fetch all gifts from the database
$gifts = $db->query("SELECT * FROM gifts")->fetchAll(PDO::FETCH_ASSOC);

// Create an array to hold the gift IDs and their request counts
$gift_requests = [];

// Fetch the number of children requesting each gift in one query
$query = "
    SELECT g.id AS gift_id, COUNT(l.id) AS request_count
    FROM gifts g
    LEFT JOIN letters l ON l.letter_text LIKE CONCAT('%', g.name, '%')
    GROUP BY g.id
";

$results = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Map the results to the $gift_requests array
foreach ($results as $row) {
    $gift_requests[$row['gift_id']] = $row['request_count'];
}

// Display the gift information in a grid layout
echo '<div class="gift-container">';
foreach ($gifts as $gift) {
    // Get the number of children requesting this gift
    $requests_count = $gift_requests[$gift['id']] ?? 0; // Default to 0 if not found

    // Determine if there are enough gifts in stock
    if ($gift['count_available'] < $requests_count) {
        $availability = "Not enough stock!";
        $status_class = "shortage";
    } else {
        $availability = "Stock is sufficient!";
        $status_class = "sufficient";
    }

    // Display the gift details
    echo '<div class="gift-box">';
    echo '<h3>' . htmlspecialchars($gift['name']) . '</h3>';
    echo '<p>' . htmlspecialchars($gift['count_available']) . ' available | ' . 
         $requests_count . ' children want this gift.</p>';
    echo '<p class="' . htmlspecialchars($status_class) . '">' . htmlspecialchars($availability) . '</p>';
    echo '</div>';
}
echo '</div>';

?>
