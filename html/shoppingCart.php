<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/shoppingCart.css">
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
    <main>
      <?php
      // Retrieve the userId from the GET request
      $userId = $_COOKIE['userId'];

      // Query the database to fetch the items in the shopping cart for the user
      // Replace database_connection with your actual database connection code
      $cartItems = []; // Placeholder array for cart items

      $conn = new mysqli("localhost", "root", "A//4321abcd", "art");

      // 检查连接是否成功
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Execute the query to fetch cart items based on the userId
      // Assuming you have a database connection already established
      $query = "SELECT art.art_id, `name`, artist, price, is_sold FROM shopping_cart INNER JOIN art ON shopping_cart.art_id = art.art_id WHERE shopping_cart.user_id = $userId";
      $result = mysqli_query($conn, $query);

      // Loop through the query results and store the cart items in the $cartItems array
      while ($row = mysqli_fetch_assoc($result)) {
        $cartItems[] = $row;
      }

      // Close the database connection
      mysqli_close($conn);
      ?>

      <!-- Render the cart items in the HTML using PHP -->
      <div class="cart-items">
        <?php foreach ($cartItems as $item): ?>
          <div class="cart-item-image">
            <img src="../images/art_image_<?php echo $item['art_id']; ?>.jpg" alt="Item Image">
          </div>
          <div class="cart-item-details">
            <h2><?php echo $item['name']; ?></h2>
            <p>Price: $<?php echo $item['price']; ?></p>
            <p>IsSold: <?php echo $item['is_sold']; ?></p>
            <form method="post" action="../php/shoppingCart.php">
              <input type="hidden" name="userId" value="<?php echo $userId; ?>">
              <input type="hidden" name="artId" value="<?php echo $item['art_id']; ?>">
              <input type="hidden" name="price" value="<?php echo $item['price']; ?>">
              <input type="hidden" name="isSold" value="<?php echo $item['is_sold']; ?>">
              <button type="submit" name="purchase" class="purchase-button" <?php if ($item['is_sold']) echo "disabled"; ?>>Purchase</button>
              <button type="submit" name="remove" class="remove-button">Remove</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    </main>
    <footer>
      <p>&copy; 2023 Shopping Cart</p>
    </footer>
    <script>
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