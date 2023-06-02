<?php
// 获取POST请求中的数据
$userId = $_POST['userId'];
$artId = $_POST['artId'];

// 执行将数据插入到表中的操作

// 示例：插入到shopping_cart表中
$conn = new mysqli("localhost", "root", "A//4321abcd", "art");

// 检查连接是否成功
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 创建插入SQL语句
$sql = "INSERT INTO shopping_cart (user_id, art_id) VALUES ('$userId', '$artId')";

// 执行插入操作
if ($conn->query($sql) === TRUE) {
  // 插入成功
  header("Location: ../html/shoppingCart.php");
  exit();
} else {
  // 插入失败
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// 关闭数据库连接
$conn->close();
