<?php
// Retrieve the recharge amount and user ID from the AJAX request
$amount = $_POST['amount'];
$userId = $_POST['userId'];

// Connect to your database (replace the placeholders with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "A//4321abcd";
$dbname = "art";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Perform the recharge logic: update the user's account balance
// Prepare and execute the update query
$stmt = $conn->prepare("UPDATE user SET account = account + ? WHERE user_id = ?");
$stmt->bind_param("di", $amount, $userId);
$stmt->execute();

// Check if the update was successful
if ($stmt->affected_rows > 0) {
  // Recharge successful
  $response = "success";
} else {
  // Recharge failed
  $response = "failed";
}

$stmt->close();
$conn->close();

// Return the response to the JavaScript code
echo $response;