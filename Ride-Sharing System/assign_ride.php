<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RideSharingDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$rideQuery = "SELECT * FROM RideRequests WHERE status = 'pending' ORDER BY priority ASC LIMIT 1";
$rideResult = $conn->query($rideQuery);

if ($rideResult->num_rows == 0) {
    echo "No pending ride requests.";
    exit();
}

$ride = $rideResult->fetch_assoc();
$ride_id = $ride['id'];
$user_id = $ride['user_id'];

$driverQuery = "SELECT * FROM Drivers WHERE available = TRUE LIMIT 1";
$driverResult = $conn->query($driverQuery);

if ($driverResult->num_rows == 0) {
    echo "No available drivers. Transaction rollback.";
    exit();
}

$driver = $driverResult->fetch_assoc();
$driver_id = $driver['id'];
$driver_name = $driver['name'];

if (rand(1, 100) <= 20) {
    echo "Assignment failed for User $user_id. Rolling back...";
    exit();
}

$updateRide = "UPDATE RideRequests SET status = 'assigned' WHERE id = $ride_id";
$updateDriver = "UPDATE Drivers SET available = FALSE WHERE id = $driver_id";

if ($conn->query($updateRide) === TRUE && $conn->query($updateDriver) === TRUE) {
    echo "Assigned Driver $driver_name to User $user_id. Ride confirmed.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
