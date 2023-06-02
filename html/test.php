<!DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <header class="header">
      <nav>
        <div class="nav-container">
          <h1>网页标题</h1>
          <ul>
            <li><a href="home.php">首页</a></li>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
            <li><a href="#">购物车</a></li>
            <li><a href="#">个人中心</a></li>
            <li><a href="#">登出</a></li>
          </ul>
        </div>
      </nav>
    </header>
  </body>
  <script>
    document.cookie = "userInfo=John Doe; expires=" + new Date(new Date().getTime() + (365 * 24 * 60 * 60 * 1000)).toUTCString() + "; path=/";
    console.log(document.cookie);
    document.cookie = "userInf=John Do; expires=" + new Date(new Date().getTime() + (365 * 24 * 60 * 60 * 1000)).toUTCString() + "; path=/";
    console.log(document.cookie);
  </script>
</html>