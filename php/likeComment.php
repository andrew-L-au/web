<?php
// Check if the purchase button is clicked
$conn = new mysqli("localhost", "root", "A//4321abcd", "art");
$artCommentId = $_POST['comment-id'];
$userId = $_POST['user-id'];
$artId = $_POST['art-id'];

// 检查连接是否成功
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['like'])) {
  // Check if the item is already sold
    // Item is available for purchase
    // Fetch the user's account balance from the user table
    $query = "INSERT INTO user_likes (user_id, art_comment_id) VALUES ($userId, $artCommentId)";
    $result = mysqli_query($conn, $query);
    $query = "UPDATE art_comment SET likes = likes + 1 WHERE art_comment_id = $artCommentId";
    $result = mysqli_query($conn, $query);
}else if (isset($_POST['unlike'])) {
  $query = "DELETE FROM user_likes WHERE user_id = $userId AND art_comment_id = $artCommentId)";
  $result = mysqli_query($conn, $query);
  $query = "UPDATE art_comment SET likes = likes - 1 WHERE art_comment_id = $artCommentId";
  $result = mysqli_query($conn, $query);
}
header("Location: ../html/detail.php?art_id=" . $artId);