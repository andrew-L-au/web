<?php
// 连接数据库
$servername = 'localhost';
$username = 'root';
$password = 'A//4321abcd';
$dbname = 'mydb';
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}

// 从 POST 请求中获取用户名
$username = $_POST['username'];

// 查询数据库中是否存在该用户名
$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $sql);

// 根据查询结果返回 JSON 数据
if (mysqli_num_rows($result) > 0) {
  echo json_encode(array('exists' => true));
} else {
  echo json_encode(array('exists' => false));
}

mysqli_close($conn);
?>