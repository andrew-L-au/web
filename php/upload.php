<?php
// Process the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the uploaded file details
  $file = $_FILES["upload-image"];
  $fileName = $file["name"];
  $fileTmp = $file["tmp_name"];

  $productName = $_POST["product-name"];
  $productArtist = $_POST["product-artist"];
  $productPrice = $_POST["product-price"];
  $productCreateYear = $_POST["product-create-year"];
  $productHeight = $_POST["product-height"];
  $productWidth = $_POST["product-width"];
  $productPeriod = $_POST["product-period"];
  $productStyle = $_POST["product-style"];
  $productIntroduction = $_POST["product-introduction"];
  $postUserId = $_COOKIE['userId'];
  $poster = $_COOKIE['username'];

  // Assuming you have established a database connection
  $servername = "localhost";
  $username = "root";
  $password = "A//4321abcd";
  $dbname = "art";

// Create a new MySQLi connection
  $conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

// Prepare the INSERT statement
  $current = date("Y-m-d");
  $stmt = $conn->prepare("INSERT INTO art (name, artist, price, visits, is_sold, post_date, post_user_id, poster,create_year, height, width, period, style, introduction) VALUES (?, ?, ?, 0, false, '$current', '$postUserId', '$poster', ?, ?, ?, ?, ?, ?)");

// Bind the parameters
  $stmt->bind_param("ssdsddsss", $productName, $productArtist, $productPrice, $productCreateYear, $productHeight, $productWidth, $productPeriod, $productStyle, $productIntroduction);

// Execute the statement
  $stmt->execute();

// Get the auto-generated art_id
  $artId = $stmt->insert_id;

// Close the statement
  $stmt->close();

// Close the connection
  $conn->close();

  // Set the new file name
  $newFileName = "art_image" . "_" . $artId . ".jpg";

  // Define the destination directory
  $destination = "../images/" . $newFileName;

  // Move the uploaded file to the destination directory
  if (move_uploaded_file($fileTmp, $destination)) {
    header("Location: success.php");
    exit();
  } else {
    // File upload failed
    echo "Error uploading file.";
  }
}
