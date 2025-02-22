<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RideSharingDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_POST['user_id'];
$pickup = $_POST['pickup'];
$destination = $_POST['destination'];
$priority = $_POST['priority'];

$sql = "INSERT INTO RideRequests (user_id, pickup, destination, priority) 
        VALUES ('$user_id', '$pickup', '$destination', '$priority')";

if ($conn->query($sql) === TRUE) {
    echo "Ride request submitted successfully.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
