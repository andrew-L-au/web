<!DOCTYPE html>
<html>
<head>
  <title>网页标题</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/home.css">
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
          <li><a id="loginLink" href="login.php">登录</a></li>
          <li><a id="registerLink" href="register.php">注册</a></li>
          <li><a id="shoppingCartLink" href="shoppingCart.php">购物车</a></li>
          <li><a id="personalCenterLink" href="personalCenter.php">个人中心</a></li>
          <li><a id="uploadLink" href="upload.php">上传作品</a></li>
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

  <section class="hero">
    <h2>Welcome to online art website</h2>
  </section>
  <div id = "LatestArt">
    <h3>Latest Art</h3>
    <section class="merchandises">
    <?php
      // 连接数据库
      $conn = mysqli_connect("localhost", "root", "A//4321abcd", "art");
      // 检查连接是否成功
      if (!$conn) {
        die("连接失败: " . mysqli_connect_error());
      }
      // 查询数据库
      $sql = "SELECT art_id, name, artist, price FROM art ORDER BY art_id DESC LIMIT 5;";
      $result = mysqli_query($conn, $sql);
      // 显示结果
      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
    ?>
          <div id="<?php echo $row["art_id"]?>"class="merchandise">
          <img src="<?php echo "../images/art_image_" . $row["art_id"] . ".jpg"?>"  alt="Feature 1">
          <h3><?php echo $row["name"]?></h3>
          <p>Artist : <?php echo $row["artist"]?></p>
          <p><?php echo $row["price"]?></p>
          </div>
    <?php
        }
      } else {
        echo "no art";
      }
      // 关闭连接
      mysqli_close($conn);
    ?>
    </section>
  </div>

  <div id = "RecommendForYou">
    <h3>Recommend For You</h3>
    <section class="merchandises">
      <?php
      // 连接数据库
      $conn = mysqli_connect("localhost", "root", "A//4321abcd", "art");
      // 检查连接是否成功
      if (!$conn) {
        die("连接失败: " . mysqli_connect_error());
      }
      // 查询数据库
      $sql = "SELECT * FROM user_visits ORDER BY visits DESC LIMIT 5;";
      $result = mysqli_query($conn, $sql);
      // 显示结果
      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          $recommendArtId = $row["art_id"];
          $sql = "SELECT art_id, name, artist, price FROM art WHERE art_id = $recommendArtId;";
          $result = mysqli_query($conn, $sql);
          $row = $result->fetch_assoc();
          ?>
          <div id="<?php echo $row["art_id"]?>"class="merchandise">
            <img src="<?php echo "../images/art_image_" . $row["art_id"] . ".jpg"?>"  alt="Feature 1">
            <h3><?php echo $row["name"]?></h3>
            <p>Artist : <?php echo $row["artist"]?></p>
            <p><?php echo $row["price"]?></p>
          </div>
          <?php
        }
      } else {
        echo "no art";
      }
      // 关闭连接
      mysqli_close($conn);
      ?>
    </section>
  </div>
    


  <footer>
    <p>版权所有 © 2023 art-web</p>
  </footer>
  <script>
  // 获取所有商品元素
    const merchandiseElements = document.querySelectorAll('.merchandise');

    // 遍历每个商品元素，为其添加点击事件
    merchandiseElements.forEach((element) => {
      element.addEventListener('click', () => {
        // 获取商品参数
        const artId = element.getAttribute('id');
        // 构造跳转URL并跳转
        const url = `./detail.php?art_id=` + artId;
        window.location.href = url;
      });
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

  const isLoggedIn = document.cookie.includes("userId=") && document.cookie.includes("username=");
  if (isLoggedIn) {
      document.getElementById("loginLink").style.display = "none";
      document.getElementById("registerLink").style.display = "none";
  } else {
      document.getElementById("shoppingCartLink").style.display = "none";
      document.getElementById("personalCenterLink").style.display = "none";
      document.getElementById("uploadLink").style.display = "none";
      document.getElementById("logoutLink").style.display = "none";
  }
  </script>
</body>
</html>
    