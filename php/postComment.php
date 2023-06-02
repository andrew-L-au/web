<?php
// Retrieve the form data
$artId = $_POST['art-id'];
$userId = $_POST['user-id'];
$username = $_POST['username'];
$comment = $_POST['review'];

// Validate the form data if needed
// ...

// Connect to your MySQL database
$servername = "localhost";
$db_username = "root";
$password = "A//4321abcd";
$dbname = "art";

$conn = new mysqli($servername, $db_username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement to insert the comment
$stmt = $conn->prepare("INSERT INTO art_comment (art_id, user_id, username, comment_value, is_deleted) VALUES (?, ?, ?, ?, 0)");
$stmt->bind_param("iiss", $artId, $userId, $username, $comment);

// Execute the prepared statement
if ($stmt->execute()) {
  // Comment inserted successfully
  echo "Comment submitted successfully!";
} else {
  // Failed to insert the comment
  echo "Error: " . $stmt->error;
}

// Close the database connection
$stmt->close();
$conn->close();
?>







