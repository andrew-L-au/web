<?php
// Retrieve the form data
$commentId = $_POST["comment-id"];

// Validate the form data if needed
// ...

// Connect to your MySQL database
$servername = "localhost";
$username = "root";
$password = "A//4321abcd";
$dbname = "art";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement to insert the reply
$stmt = $conn->prepare("UPDATE art_comment SET is_deleted = 1 WHERE art_comment_id = $commentId");

// Execute the prepared statement
if ($stmt->execute()) {
  // Reply inserted successfully
  echo "Reply submitted successfully!";
} else {
  // Failed to insert the reply
  echo "Error: " . $stmt->error;
}

// Close the database connection
$stmt->close();
$conn->close();