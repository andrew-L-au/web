<!DOCTYPE html>
<html>
  <head>
    <title>Login Page</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../css/login.css">
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
              </ul>
          </div>
          <div class="nav-container">
            <div class="searchBox">
              <button class="searchBtn"><a id="searchLink" href="search.php">搜索</a></button>
            </div>
          </div>
      </nav>
    </header>

    <form action="../php/login.php" method="POST">
        <div class="container signin">
          <h1>Login</h1>
          <p>Please fill in this form to log in to your account.</p>
          <hr/>
  
          <label for="username"><b>Username</b></label>
          <input type="text" placeholder="Enter Username" name="username" required />
  
          <label for="password"><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="password" required />

          <label for="captcha"><b>Verification Code</b></label>
          <input type="text" placeholder="Enter Verification Code" name="captcha" required />
          <div id="captchaMessage" style="display: none; color: red;">Incorrect verification code. Please try again.</div>
          <canvas id="captchaCanvas" style="border: 1px solid #ccc;"></canvas>
          <button id="refreshButton" class="registerbtn">Refresh</button>

          <hr/>

          <button type="submit" class="signinbtn">Log In</button>
        </div>
    </form>
    <footer>
      <p>版权所有 © 2023 art-web</p>
    </footer>
    <script>
        const canvas = document.getElementById('captchaCanvas');
        const refreshButton = document.getElementById('refreshButton');
        const context = canvas.getContext('2d');
        let var_captcha;

        refreshButton.addEventListener('click', function() {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let captcha = '';
            const  length = 4
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                captcha += characters.charAt(randomIndex);
            }
            drawCaptcha(captcha);
            var_captcha = captcha
        });
        generateCaptcha(4);

        function generateCaptcha(length) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let captcha = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                captcha += characters.charAt(randomIndex);
            }
            drawCaptcha(captcha);
            var_captcha = captcha
        }

        function drawCaptcha(captcha) {
            context.clearRect(0, 0, canvas.width, canvas.height);

            context.font = '48px Arial';
            context.fillStyle = '#000';
            context.fillText(captcha, 20, 50);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            distortCaptcha(imageData);

            context.putImageData(imageData, 0, 0);
        }

        function distortCaptcha(imageData) {
            const data = imageData.data;
            const width = imageData.width;
            const height = imageData.height;

            for (let y = 0; y < height; y++) {
                for (let x = 0; x < width; x++) {
                    const offset = (y * width + x) * 4;
                    const r = data[offset];
                    const g = data[offset + 1];
                    const b = data[offset + 2];

                    // 通过一些算法对像素进行扭曲处理
                    // 可以使用像素位移、颜色变化、噪点等操作

                    data[offset] = r;
                    data[offset + 1] = g;
                    data[offset + 2] = b;
                }
            }
        }

        const captchaMessage = document.getElementById("captchaMessage");
        const captchaInput = document.querySelector('input[name="captcha"]');

        captchaInput.addEventListener("input", function () {
            if(var_captcha === captchaInput.value){
                captchaMessage.style.display = 'none'
            }else {
                captchaMessage.style.display = 'block'
            }
        })
    </script>
  </body>
</html>