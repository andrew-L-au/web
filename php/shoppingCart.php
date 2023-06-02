<?php
// Check if the purchase button is clicked
$conn = new mysqli("localhost", "root", "A//4321abcd", "art");

// 检查连接是否成功
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['purchase'])) {
  $artId = $_POST['artId'];
  $price = $_POST['price'];
  $userId = $_POST['userId'];
  // Check if the item is already sold
  $isSold = $_POST['isSold'];
  if ($isSold) {
    // Item is sold, show error message
    echo "This item is already sold.";
  } else {
    // Item is available for purchase
    // Fetch the user's account balance from the user table
    $query = "SELECT account FROM `user` WHERE user_id = $userId";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $account = $row['account'];

    // Check if the user has sufficient funds to make the purchase
    if ($account >= $price) {
      // Deduct the price from the user's account balance
      $newAccountBalance = $account - $price;
      $updateQuery = "UPDATE user SET account = $newAccountBalance WHERE user_id = $userId";
      mysqli_query($conn, $updateQuery);

      // Insert the purchase record into the user_purchase table
      $insertQuery = "INSERT INTO user_purchase (user_id, art_id) VALUES ($userId, $artId)";
      mysqli_query($conn, $insertQuery);

      $updateArtQuery = "UPDATE art SET is_sold = 1 WHERE art_id = $artId";
      mysqli_query($conn, $insertQuery);
      header("Location: ../html/home.php");
      // Redirect the user to the homepage
    } else {
      // Insufficient funds, show error message
      echo "Insufficient funds. Purchase failed.";
    }
    exit();
  }
}
?>

<?php
// Check if the remove button is clicked
if (isset($_POST['remove'])) {
  $artId = $_POST['artId'];
  $userId = $_POST['userId'];

  // Delete the item from the shopping_cart table
  $deleteQuery = "DELETE FROM shopping_cart WHERE user_id = $userId AND art_id = $artId";
  mysqli_query($conn, $deleteQuery);
  header("Location: ../html/home.php");
}
?>
