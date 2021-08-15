<?php
$servername = "dockerhost";
$username = "wp_backstage";
$password = 'CEWdFD27fz#7EnQk5';

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully<br>";

if($conn->select_db('wp_backstage')){
    echo 'Connected DB';
}

?>
