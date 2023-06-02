<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Upload</title>
  <link rel="stylesheet" href="../css/nav.css">
  <link rel="stylesheet" href="../css/upload.css">
</head>
<body>
  <header>
    <nav>
      <div class="nav-container">
        <h1>网页标题</h1>
        <ul>
          <li><a href="home.php">首页</a></li>
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
  <main>
    <div class="product-image">
      <label for="upload-image">Upload Image:</label>
      <input type="file" id="upload-image" name="upload-image" accept="image/jpeg">
      <img id="image-preview" src="#" alt="Image Preview">
    </div>
    <div class="product-details">
      <form id="upload-form">
        <label for="product-name">Product Name:</label>
        <input type="text" id="product-name" name="product-name" placeholder="Enter product name" required>
        <label for="product-artist">Artist:</label>
        <input type="text" id="product-artist" name="product-artist" placeholder="Enter artist name" required>
        <label for="product-price">Price:</label>
        <input type="number" id="product-price" name="product-price" placeholder="Enter product price" min="0" required>
        <label for="product-create-year">Create Year:</label>
        <input type="number" id="product-create-year" name="product-create-year" placeholder="Enter create year" required>
        <label for="product-height">Height:</label>
        <input type="number" id="product-height" name="product-height" placeholder="Enter height" required>
        <label for="product-width">Width:</label>
        <input type="number" id="product-width" name="product-width" placeholder="Enter width" required>
        <label for="product-period">Period:</label>
        <input type="text" id="product-period" name="product-period" placeholder="Enter period" required>
        <label for="product-style">Style:</label>
        <input type="text" id="product-style" name="product-style" placeholder="Enter style" required>
        <label for="product-introduction">Introduction:</label>
        <textarea id="product-introduction" name="product-introduction" placeholder="Enter introduction" required></textarea>
        <button id="submit-button" type="submit">Upload Product</button>
      </form>
    </div>
  </main>
  <footer>
    <p>&copy; 2023 Product Upload</p>
  </footer>
  <script>
      const uploadImageInput = document.getElementById('upload-image');
      const imagePreview = document.getElementById('image-preview');

      uploadImageInput.addEventListener('change', function() {
          const file = this.files[0];
          const reader = new FileReader();

          reader.addEventListener('load', function() {
              imagePreview.src = reader.result;
          });

          if (file) {
              reader.readAsDataURL(file);
          }
      });

      document.getElementById("submit-button").addEventListener("click", function (event){
          event.preventDefault(); // Prevent form submission

          // Get the uploaded image file
          const uploadedImage = document.getElementById("upload-image").files[0];

          const imageName = `art_image.jpg`; // Adjust file extension if necessary

          // Create a FormData object to send the form data
          const formData = new FormData(document.getElementById("upload-form"));

          // Append the renamed image file to the FormData object
          formData.append("upload-image", uploadedImage, imageName);

          // Submit the form data asynchronously using AJAX
          const xhr = new XMLHttpRequest();
          xhr.open("POST", "../php/upload.php", true);
          xhr.onload = function() {
              // Handle the response from the PHP file if needed
              if (xhr.status === 200) {
                  // Successful upload
                  console.log(xhr.responseText);
              } else {
                  // Failed upload
                  console.error("Upload failed. Status:", xhr.status);
              }
          };
          xhr.send(formData);
      })

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