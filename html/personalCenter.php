<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile</title>
  <link rel="stylesheet" href="../css/personalcenter.css">
  <link rel="stylesheet" href="../css/nav.css">
  <link rel="stylesheet" href="../css/searchBox.css">
</head>
<body>
  <header class="header">
    <nav>
      <div class="nav-container">
        <h1>网页标题</h1>
        <ul>
          <li><a href="home.php">首页</a></li>
          <li><a id="shoppingCartLink" href="shoppingCart.php">购物车</a></li>
          <li><a id="personalCenterLink" href="personalCenter.php">个人中心</a></li>
          <li><a id="logoutLink" href="home.php">登出</a></li>
        </ul>
      </div>
      <div class="nav-container">
        <div class="searchBox">
          <button class="searchBtn"><a id="searchLink" href="search.php">搜索</a></button>
        </div>
      </div>
    </nav>
  </header>

  <?php
  // Get the user ID from the cookie
  $userId = $_COOKIE['userId'];

  // 连接数据库
  $conn = mysqli_connect("localhost", "root", "A//4321abcd", "art");
  // 检查连接是否成功
  if (!$conn) {
    die("连接失败: " . mysqli_connect_error());
  }

  // Fetch user's personal information
  $query = "SELECT * FROM user WHERE user_id = $userId";
  // Execute the query and fetch the result
  $result = $conn->query($query);
  $user = $result->fetch_assoc();

  // Fetch art uploaded by the user
  $query = "SELECT * FROM art WHERE post_user_id = $userId";
  // Execute the query and fetch the result
  $result = $conn->query($query);
  $uploadedArts = [];
  while ($row = $result->fetch_assoc()){
    $uploadedArts[] = $row;
  }


  // Fetch art purchased by the user
  $query = "SELECT * FROM art a INNER JOIN user_purchase up ON a.art_id = up.art_id WHERE up.user_id = $userId";
  // Execute the query and fetch the result
  $result = $conn->query($query);
  $purchasedArts = [];
  while ($row = $result->fetch_assoc()){
    $purchasedArts[] = $row;
  }

  ?>

  <main>
    <div class="user-info">
      <h2>Basic Information</h2>
      <ul>
        <li><strong>Name:</strong> <?php echo $user['username']; ?></li>
        <li><strong>Email:</strong> <?php echo $user['email']; ?></li>
        <li><strong>Phone Number:</strong> <?php echo $user['phone_number']; ?></li>
        <li><strong>Address:</strong> <?php echo $user['address']; ?></li>
        <li><strong>Gender:</strong> <?php echo $user['gender']; ?></li>
        <li><strong>Birthday:</strong> <?php echo $user['birthday']; ?></li>
        <li><strong>Nationality:</strong> <?php echo $user['nationality']; ?></li>
        <li><strong>Account:</strong> <?php echo $user['account']; ?>
          <input type="number" id="rechargeAmount" placeholder="Enter amount">
          <button id="rechargeButton">Recharge</button>
        </li>
      </ul>
    </div>

    <div class="user-products">
      <h2>My Products</h2>
      <ul>
        <?php foreach ($uploadedArts as $art): ?>
          <li>
            <a href="#">
              <img src="<?php echo "../images/art_image_" . $art['name'] . ".jpg"; ?>" alt="Product 1">
              <h3>Name:<?php echo $art['name']; ?></h3>
              <p>Artist:<?php echo $art['artist']; ?></p>
              <p>Price:<?php echo $art['price']; ?></p>
              <p>Is Sold:<?php echo $art['is_sold']; ?></p>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="user-orders">
      <h2>My Orders</h2>
      <ul>
        <?php foreach ($purchasedArts as $art): ?>
          <li>
            <a href="#">
              <h3>Order #<?php echo $art['user_purchase_id']; ?></h3>
              <p>Name:<?php echo $art['name']; ?></p>
              <p>Artist:<?php echo $art['artist']; ?></p>
              <p>Price:$<?php echo $art['price']; ?></p>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </main>
  <footer>
    <p>&copy; 2023 User Profile</p>
  </footer>
  <script>
      document.getElementById("rechargeButton").addEventListener("click", function() {
          const amount = document.getElementById("rechargeAmount").value;
          const userId = <?php echo $userId?>;

          // Make an AJAX request to the server
          const xhr = new XMLHttpRequest();
          xhr.open("POST", "../php/recharge.php", true);
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function() {
              if (xhr.readyState === 4 && xhr.status === 200) {
                  // Handle the response from the server
                  const response = xhr.responseText;
                  if (response === "success") {
                      // Recharge successful
                      alert("Recharge successful!");
                      // Optionally, update the account balance on the page
                  } else {
                      // Recharge failed
                      console.log(response)
                      alert("Recharge failed. Please try again.");
                  }
              }
          };
          xhr.send("amount=" + amount + "&userId=" + userId);
      });

      document.getElementById("logoutLink").addEventListener("click", function(event) {
          event.preventDefault();
          // Prevent the default link behavior
          // Clear the cookie by setting an expiration date in the past
          document.cookie = "userInfo=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
          document.cookie = "userId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
          document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
          // Redirect to the home page
          window.location.href = "home.php";
      });
  </script>
</body>
</html>