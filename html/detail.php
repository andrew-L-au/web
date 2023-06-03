<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Artwork Details</title>
  <link rel="stylesheet" href="../css/detail.css">
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

    if (empty($_GET['art_id'])) {
      // Exit PHP execution
      exit;
    }

    $artId = $_GET['art_id'];

    $host = "localhost";
    $dbName = "art";
    $username = "root";
    $password = "A//4321abcd";

    $conn = new mysqli($host, $username, $password, $dbName);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_COOKIE["userId"])){
      $userId = $_COOKIE["userId"];
      $sql = "SELECT * FROM user_visits WHERE art_id = $artId AND user_id = $userId";
      $result = mysqli_query($conn, $sql);
      if ($result->num_rows == 0){
        $sql = "INSERT INTO user_visits (user_id, art_id, visits) VALUES ($userId, $artId, 1)";
      }else{
        $sql = "UPDATE user_visits SET visits = visits + 1 WHERE art_id = $artId AND user_id = $userId";
      }
      mysqli_query($conn, $sql);
    }

    $sql = "UPDATE art SET visits = visits + 1 WHERE art_id = '$artId'";
    $result = mysqli_query($conn, $sql);

    $sql = "SELECT * FROM art WHERE art_id = '$artId'";
    $result = mysqli_query($conn, $sql);
    $row = $result->fetch_assoc();

    $artworkName = $row["name"];
    $artistName = $row["artist"];
    $price = $row["price"];
    $visits = $row["visits"];
    $isSold = $row["is_sold"];
    $datePosted = $row["post_date"];
    $postedBy = $row["poster"];
    $year = $row["create_year"];
    $height = $row["height"];
    $width = $row["height"];
    $period = $row["period"];
    $style = $row["style"];

    $imagePath = "../images/art_image_" . $artId . ".jpg";
    $artInCart = 0;
    // Get the user ID from the cookie (assuming it's stored in a variable named $userId)
    if (isset($_COOKIE['userId'])){
      $userId = $_COOKIE['userId'];

      // Prepare the SQL query to select records from the shopping_cart table
      $sql = "SELECT * FROM shopping_cart WHERE user_id = '$userId'";

      // Execute the query
      $result = $conn->query($sql);

      // Create an array to store the art IDs in the user's shopping cart
      $artIdsInCart = [];

      // Check if the query returned any records
      if ($result->num_rows > 0) {
        // Fetch each row from the result and store the art ID in the array
        while ($row = $result->fetch_assoc()) {
          $artIdsInCart[] = $row['art_id'];
        }
      }

      // Close the database connection
      $conn->close();
      // Now you have the art IDs in the user's shopping cart stored in the $artIdsInCart array
      // You can use this array to check if a specific art is in the user's shopping cart
      if (in_array($artId, $artIdsInCart)) {
        // Art is in the user's shopping cart, disable the button
        $artInCart = 1;
      }else {
        $artInCart = 0;
      }
    }
  ?>
  <main>
    <div class="artwork-details">
      <div class="image-container">
        <img id="zoom-image" class="photo" src="<?php echo $imagePath; ?>" alt="Artwork Image">
      </div>
      <div class="details">
        <h2><?php echo $artworkName; ?></h2>
        <p>By <?php echo $artistName; ?></p>
        <p>Price: <?php echo $price; ?></p>
        <p>IsSold: <?php echo $isSold; ?></p>
        <p>Date Posted: <?php echo $datePosted; ?></p>
        <p>Posted By: <?php echo $postedBy; ?></p>
        <p>Visits: <?php echo $visits; ?></p>
        <h3>Details</h3>
        <ul>
          <li>Year: <?php echo $year; ?></li>
          <li>Height: <?php echo $height; ?></li>
          <li>Width: <?php echo $width; ?></li>
          <li>Period: <?php echo $period; ?></li>
          <li>Style: <?php echo $style; ?></li>
        </ul>
        <button id="addToCartButton">Add to Cart</button>
        <p id="isSoldErrorMessage" style="display: none;">This artwork is already sold.</p>
        <p id="inCartErrorMessage" style="display: none;">This artwork is already in cart.</p>
        <p id="notLoginErrorMessage" style="display: none;">You have not logged in.</p>
      </div>
    </div>

    <h2>Customer Reviews</h2>
    <div class="write-review">
      <form id="comment-form">
        <input id="art-id" type="hidden" name="art-id" value="<?php echo $artId?>">
        <label for="review">Review:</label>
        <textarea id="review" name="review" placeholder="Enter your review" required></textarea>
        <button type="submit">Submit Review</button>
      </form>
    </div>
    <div class="reviews">
      <h3>Customer Reviews</h3>
        <ul>
        <?php
          // Connect to your MySQL database
          $servername = "localhost";
          $username = "root";
          $password = "A//4321abcd";
          $dbname = "art";

          $conn = new mysqli($servername, $username, $password, $dbname);
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          // Retrieve all comments from the art_comment table
          $sql = "SELECT * FROM art_comment WHERE art_id = $artId AND is_deleted != 1";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            // Output each comment
            while ($row = $result->fetch_assoc()) {
              if ($row["is_deleted"] == 0) {
        ?>
        <li>
          <div id="<?php echo $row["art_comment_id"]?>" class="review">
            <?php
              if (!($row['reply_to_comment_id'] === null)) {
                $reply_to_comment_id = $row['reply_to_comment_id'];
                $replySql = "SELECT * FROM art_comment WHERE art_comment_id = 1";
                $replyResult = $conn->query($replySql);
                $replyRow = $replyResult->fetch_assoc();
            ?>
            <div class="reply-to-review-content">
              <div class="review-header">
                <h4>Reply To : </h4>
                <p><?php echo $replyRow["username"]?></p>
              </div>
              <p><?php if ($replyRow["is_deleted"] == 0){ echo $replyRow["comment_value"];}else{echo "Deleted";}?></p>
            </div>
            <?php
              }
            ?>
            <div class="review-content">
              <div class="review-header">
                <h4><?php echo $row["username"]?></h4>
              </div>
              <p><?php echo $row["comment_value"]?></p>
              <p>Likes :<?php echo $row["likes"]?></p>
            </div>
            <?php
              if (isset($_COOKIE["userId"])){
            ?>
            <form id="reply-form" action="../php/reply.php" method="POST">
              <input id="art-id" type="hidden" name="art-id" value="<?php echo $artId?>">
              <input type="hidden" name="reply-to-comment-id" value="<?php echo $row["art_comment_id"]?>">
              <input type="hidden" name="reply-to-username" value="<?php echo $row["username"]?>">
              <label for="reply">Reply:</label>
              <textarea id="reply" name="reply" placeholder="Enter your reply" required></textarea>
              <button type="submit">Submit Reply</button>
            </form>
            <div class="review-actions">
              <?php
              $userId = $_COOKIE["userId"];
              $artCommentId = $row["art_comment_id"];
              $sql = "SELECT * FROM user_likes WHERE user_id = $userId AND art_comment_id = $artCommentId";
              $result = $conn->query($sql);
              ?>
              <form  id="like-comment-form" action="../php/likeComment.php" method="POST">
                <input id="like-comment-art-id" type="hidden" name="art-id" value="<?php echo $artId?>">
                <input id="like-comment-comment-id" type="hidden" name="comment-id" value="<?php echo $row["art_comment_id"]?>">
                <input id="like-comment-user-id" type="hidden" name="user-id" value="<?php echo $_COOKIE["userId"]?>">
                <input id="like-comment-status" type="hidden" name="<?php if ($result->num_rows == 0){echo "like";}else{echo "unlike";}?>" value="">
                <button type="submit" class="like" <?php if ($row["user_id"] == $_COOKIE["userId"]){echo "style=\"display: none\"";}?>>Like</button>
              </form>
              <form  id="delete-comment-form" action="../php/deleteComment.php" method="POST">
                <input id="delete-comment-comment-id" type="hidden" name="comment-id" value="<?php echo $row["art_comment_id"]?>">
                <button type="submit" class="delete" <?php if (!($row["user_id"] == $_COOKIE["userId"])){echo "style=\"display: none\"";}?>>Delete</button>
              </form>
            </div>
          </div>
        </li>
      <?php
              }
            }
          }
        }
      ?>
      </ul>
    </div>
  </main>
  <footer>
    <p>&copy; 2023 Shopping Website</p>
  </footer>
  <script>
      const isLoggedIn = document.cookie.includes("userId=") && document.cookie.includes("username=");

      const isSold = <?php echo $isSold;?>;
      const addToCartButton = document.getElementById('addToCartButton');
      const isSoldErrorMessage = document.getElementById('isSoldErrorMessage');
      const inCartErrorMessage = document.getElementById('inCartErrorMessage');
      const notLoginErrorMessage = document.getElementById('notLoginErrorMessage');
      if (isSold) {
          addToCartButton.disabled = true;
          isSoldErrorMessage.style.display = 'block';
      }
      if (isLoggedIn){
          const inCart = <?php echo $artInCart; ?>;
          if (inCart) {
              addToCartButton.disabled = true;
              inCartErrorMessage.style.display = 'block';
          }
      }else {
          addToCartButton.disabled = true;
          notLoginErrorMessage.style.display = 'block';
      }

      const artId = <?php echo $artId; ?>;
      // 添加点击事件处理程序
      addToCartButton.addEventListener("click", function() {
          // 获取当前用户的ID和艺术品的ID
          const userId = getCookieValue("userId");

          // 创建一个FormData对象，用于将数据发送到PHP页面
          const formData = new FormData();
          formData.append("userId", userId);
          formData.append("artId", artId);

          // 创建XMLHttpRequest对象
          const xhr = new XMLHttpRequest();

          // 设置POST请求的URL和参数
          xhr.open("POST", "../php/addToCart.php", true);

          // 定义回调函数，处理服务器响应
          xhr.onreadystatechange = function() {
              if (xhr.readyState === 4 && xhr.status === 200) {
                  // 处理服务器的响应
                  window.location.href = "shoppingCart.php";
              }
          };

          // 发送请求
          xhr.send(formData);
      });

      const commentForm = document.getElementById("comment-form");
      const artIdInput = document.getElementById("art-id");
      const userId = getCookieValue("userId");

      // Get the artId from your source

      commentForm.addEventListener("submit", function(event) {
          event.preventDefault(); // Prevent form submission

          // Get the userId and username from the cookie or any other source
           // Set the userId value here
          const username = getCookieValue("username"); // Set the username value here

          // Create a FormData object to send the form data
          const formData = new FormData(commentForm);
          formData.append("user-id", userId);
          formData.append("username", username);

          // Submit the form data asynchronously using AJAX
          const xhr = new XMLHttpRequest();
          xhr.open("POST", "../php/postComment.php", true);
          xhr.onload = function() {
              // Handle the response from the PHP file if needed
              if (xhr.status === 200) {
                  // Successful comment submission
                  console.log(xhr.responseText);
                  // Clear the textarea after successful submission
                  document.getElementById("review").value = "";
              } else {
                  // Failed comment submission
                  console.error("Comment submission failed. Status:", xhr.status);
              }
          };
          xhr.send(formData);
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


      if (isLoggedIn) {
          document.getElementById("loginLink").style.display = "none";
          document.getElementById("registerLink").style.display = "none";
      } else {
          document.getElementById("shoppingCartLink").style.display = "none";
          document.getElementById("personalCenterLink").style.display = "none";
          document.getElementById("logoutLink").style.display = "none";
      }

      function getCookieValue(name) {
          const cookies = document.cookie.split("; ");
          for (let i = 0; i < cookies.length; i++) {
              const cookie = cookies[i].split("=");
              if (cookie[0] === name) {
                  return cookie[1];
              }
          }
          return "";
      }

      const image = document.getElementById('zoom-image');

      image.addEventListener('mousemove', function(event) {
          const { left, top, width, height } = image.getBoundingClientRect();
          const x = (event.clientX - left) / width;
          const y = (event.clientY - top) / height;

          image.style.transformOrigin = `${x * 100}% ${y * 100}%`;
          image.classList.add('zoomed');
      });

      image.addEventListener('mouseleave', function() {
          image.classList.remove('zoomed');
      });
  </script>
</body>
</html>