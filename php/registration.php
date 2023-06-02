<?php
// Check if the request is a POST request
  if (!($_SERVER["REQUEST_METHOD"] === "POST")) {
    // Handle invalid request method
    $response = array("success" => false, "message" => "Invalid request method.");
    echo json_encode($response);
    exit;
  }
  // Get the form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];
  $phoneNumber = $_POST["phoneNumber"];
  $address = $_POST["address"];
  $gender = $_POST["gender"];
  $birthday = $_POST["birthday"];
  $nationality = $_POST["nationality"];

  // Validate the form data (perform additional validation as per your requirements)
  if (empty($username) || empty($password) || empty($email)) {
    $response = array("success" => false, "message" => "Please fill in all required fields.");
    echo json_encode($response);
    exit;
  }

  // Perform duplicate username verification
  // Connect to your MySQL database (update with your database credentials)
  $host = "localhost";
  $dbName = "art";
  $db_username = "root";
  $db_password = "A//4321abcd";

  $conn = new mysqli($host, $db_username, $db_password, $dbName);
  if ($conn->connect_error) {
    $response = array("success" => false, "message" => "Failed to connect to the database.");
    echo json_encode($response);
    exit;
  }

  // Prepare and execute the query to check for duplicate username
  $stmt = $conn->prepare("SELECT username FROM `user` WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  // Check if a row exists (duplicate username found)
  if ($stmt->num_rows > 0) {
    $response = array("success" => false, "message" => "Username already exists.");
    echo json_encode($response);
    exit;
  }

  // 生成随机盐值
  $salt = generateSalt();

  // 创建哈希密码
  $hashedPassword = hashPassword($password, $salt);

  // 将用户名、哈希密码和盐值插入到数据库表
  $query = "INSERT INTO `user` (username, hashed_password, salt) VALUES ('$username', '$hashedPassword', '$salt')";

  // Insert the user information into the database
  $stmt = $conn->prepare("INSERT INTO `user` (username, hashed_password, salt, email, phone_number, address, gender, birthday, nationality, account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
  $stmt->bind_param("sssssssss", $username, $hashedPassword, $salt, $email, $phoneNumber, $address, $gender, $birthday, $nationality);

  if ($stmt->execute()) {
    $response = array("success" => true, "message" => "Registration successful.");
    echo json_encode($response);
  } else {
    $response = array("success" => false, "message" => "Failed to register. Please try again later.");
    echo json_encode($response);
  }

  // Close the database connection
  $stmt->close();
  $conn->close();

  // 生成随机盐值
  function generateSalt() {
    $length = 16; // 盐值的长度
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $salt = '';
    for ($i = 0; $i < $length; $i++) {
      $salt .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $salt;
  }

  // 创建哈希密码
  function hashPassword($password, $salt) {
    return hash('sha256', $password . $salt);
  }
?>
