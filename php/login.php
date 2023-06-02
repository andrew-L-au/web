<?php
// Check if the form is submitted
function validatePassword($password, $hashedPassword, $salt) {
  $hashedInputPassword = hashPassword($password, $salt);
  return $hashedInputPassword === $hashedPassword;
}

function hashPassword($password, $salt) {
  return hash('sha256', $password . $salt);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $host = "localhost";
  $dbName = "art";
  $username = "root";
  $password = "A//4321abcd";

  $conn = new mysqli($host, $username, $password, $dbName);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  // Retrieve the submitted username and password
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Perform the login verification against your database (replace with your actual code)
  // Example: Query the database to check if the username and password match a user record
  $loginSuccess = false;
  $sql = "SELECT user_id, username, hashed_password, salt FROM `user` WHERE username = '$username'";
  $result = mysqli_query($conn, $sql);
  $row = $result->fetch_assoc();
  if ($row == null){
    echo "Invalid username Please try again.";
    exit();
  }
  $loginSuccess = validatePassword($password, $row["hashed_password"], $row["salt"]);
  if ($loginSuccess) {
    // Set the login cookie (example: set a cookie named "login" with a value "true" for 1 day)
    setcookie("userId", $row["user_id"], time() + (24 * 60 * 60), "/"); // Replace with your desired cookie settings
    setcookie("username", $username, time() + (24 * 60 * 60), "/");
    // Redirect to the home page
    header("Location: ../html/home.php");
    exit(); // Ensure the script stops executing after redirection
  } else {
    // Login failed, display an error message or perform appropriate actions
    echo "Invalid password. Please try again.";
  }
}
