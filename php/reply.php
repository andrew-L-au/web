<?php
  // Retrieve the form data
  $artId = $_POST['art-id'];
  $replyToCommentId = $_POST['reply-to-comment-id'];
  $replyToUsername = $_POST['reply-to-username'];
  $userId = $_COOKIE['userId'];
  $username = $_COOKIE['username'];
  $reply = $_POST['reply'];

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

  // Prepare the SQL statement to insert the reply
  $stmt = $conn->prepare("INSERT INTO art_comment (art_id, reply_to_comment_id, user_id, username, comment_value, is_deleted) VALUES (?, ?, ?, ?, ?, 0)");
  $stmt->bind_param("iiiss", $artId, $replyToCommentId, $userId, $username, $reply);

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