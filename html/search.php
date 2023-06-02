<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Shopping Website - Search Page</title>
    <link rel="stylesheet" href="../css/search.css">
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
      <h2>Search Results</h2>
      <div class="filter-sort">
        <div class="search-box">
          <input id="searchInput" type="text" placeholder="Search">
          <select id="searchCategory" name="category">
            <option value="author">By Author</option>
            <option value="art">By Art</option>
          </select>
          <button id="searchButton">Search</button>
        </div>
        <div class="sort-box">
          <label for="sort-by">Sort by:</label>
          <select name="sort-by" id="sortBy">
            <option value="price-low-to-high">Price: Low to High</option>
            <option value="price-high-to-low">Price: High to Low</option>
            <option value="date-newest-to-oldest">Date: Newest to Oldest</option>
            <option value="date-oldest-to-newest">Date: Oldest to Newest</option>
          </select>
        </div>
      </div>

      <?php
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : null;
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $sortOption = isset($_GET['sort-by']) ? $_GET['sort-by'] : null;

        // 连接数据库
        $conn = mysqli_connect("localhost", "root", "A//4321abcd", "art");
        // 检查连接是否成功
        if (!$conn) {
          die("连接失败: " . mysqli_connect_error());
        }

        // Build the SQL query based on the search query, category, and sort option
        $query = "SELECT * FROM art";

        if ($category == 'author') {
          $query .= " WHERE artist LIKE '%$searchQuery%'";
        } elseif ($category == 'art') {
          $query .= " WHERE name LIKE '%$searchQuery%'";
        }

        if ($sortOption == 'price-low-to-high') {
          $query .= " ORDER BY price ASC";
        } elseif ($sortOption == 'price-high-to-low') {
          $query .= " ORDER BY price DESC";
        } elseif ($sortOption == 'date-newest-to-oldest') {
          $query .= " ORDER BY post_date DESC";
        } elseif ($sortOption == 'date-oldest-to-newest') {
          $query .= " ORDER BY post_date ASC";
        }

        // Perform the database query
        $result = mysqli_query($conn, $query);
        $totalRecords = mysqli_num_rows($result);

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        $recordsPerPage = 5;
        $totalPages = ceil($totalRecords / $recordsPerPage);

        $offset = ($currentPage - 1) * $recordsPerPage;

        $query .= " LIMIT $offset, $recordsPerPage";

        $result = mysqli_query($conn, $query);
      ?>

      <h2>Search Results</h2>

      <!-- Add the search and sort form here -->

      <ul class="products">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <li>
            <a href="#">
              <img src="<?php echo "../images/art_image_" . $row['art_id'] . ".jpg"; ?>" alt="Product Image">
              <h3><?php echo $row['name']; ?></h3>
              <p>$<?php echo $row['price']; ?></p>
            </a>
          </li>
        <?php } ?>
      </ul>

      <!-- Add the pagination links here -->
      <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
          <a href="search.php?page=<?php echo $i; ?>&search=<?php echo $searchQuery; ?>&category=<?php echo $category; ?>&sort-by=<?php echo $sortOption; ?>">
            <?php echo $i; ?>
          </a>
        <?php } ?>
      </div>
    </main>
    <footer>
      <p>&copy; 2023 Shopping Website</p>
    </footer>
  </body>
  <script>
      // Get the elements
      const searchInput = document.getElementById('searchInput');
      const searchCategory = document.getElementById('searchCategory');
      const sortBy = document.getElementById('sortBy');
      const searchButton = document.getElementById('searchButton');

      // Handle search button click event
      searchButton.addEventListener('click', function() {
          // Get the selected values
          const searchValue = searchInput.value;
          const categoryValue = searchCategory.value;
          const sortByValue = sortBy.value;

          // Construct the URL
          const url = './search.php' + '?search=' + encodeURIComponent(searchValue) +
              '&category=' + encodeURIComponent(categoryValue) +
              '&sort-by=' + encodeURIComponent(sortByValue);

          // Redirect to the search page
          window.location.href = url;
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
          document.getElementById("logoutLink").style.display = "none";
      }
  </script>
</html>