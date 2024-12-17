<?php 


require "Database.php";
$config = require "config.php";


$db = new Database($config["database"]);

$children = $db->query("SELECT * FROM children")->fetchAll(PDO::FETCH_ASSOC);;
// $comments = $db->query("SELECT * FROM comments")
// $users = $db->query("SELECT * FROM users WHERE userid = $id")



echo "<ul>";
  foreach($children as $x) {
    echo "<li>" . 
        $x['firstname'] . " " . 
        $x['middlename'] . " " . 
        $x['surname'] . " " .
        $x['age'] . 
        "</li>"; }
echo "</ul>";




