<!DOCTYPE html>
<html>
<head>
  <title>Modify Page</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../css/register.css">
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
  ?>

<form id="registerForm" method="POST">
  <div class="container">
    <h1>Register</h1>
    <p>Please fill in this form to create an account.</p>
    <hr />

    <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" value="<?php echo $user["username"]?>" required />
    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required />
    <!-- 显示密码强度的元素 -->
    <div id="passwordStrength" style="display: none;"></div>
    <div id="passwordRules" style="display: none;">
      Password must contain at least 8 characters, including at least one letter, one number, and one special character.
    </div>
    <label for="confirmPassword"><b>Confirm Password</b></label>
    <input type="password" placeholder="Confirm Password" name="confirmPassword" required />
    <div id="passwordMatchError" style="display: none;">Passwords do not match.</div>
    <label for="email"><b>Email</b></label>
    <div id="error-message" style="display: none;">Please enter a valid email address.</div>
    <input type="text" id="email" placeholder="Enter Email" value="<?php echo $user["email"]?>" name="email"/>

    <div id="phoneError" style="display: none;">Please enter a valid phone number.</div>
    <label for="phoneNumber"><b>Phone Number</b></label>
    <input id="phoneNumberInput" type="text" placeholder="Enter Phone Number" value="<?php echo $user["phone_number"]?>" name="phoneNumber"/>

    <label for="address"><b>Address</b></label>
    <input type="text" placeholder="Enter Address" value="<?php echo $user["address"]?>" name="address"/>

    <label for="gender"><b>Gender</b></label>
    <select name="gender">
      <option value="male">male</option>
      <option value="female">female</option>
      <option value="other">other</option>
    </select>

    <label for="birthday"><b>Birthday</b></label>
    <input type="date" name="birthday">

    <label for="nationality"><b>Nationality</b></label>
    <input type="text" placeholder="Enter Nationality" value="<?php echo $user["nationality"]?>" name="nationality"/>

    <label for="captcha"><b>Verification Code</b></label>
    <input type="text" placeholder="Enter Verification Code" name="captcha" required />
    <div id="captchaMessage" style="display: none; color: red;">Incorrect verification code. Please try again.</div>
    <canvas id="captchaCanvas" style="border: 1px solid #ccc;"></canvas>
    <button id="refreshButton" class="registerbtn">Refresh</button>

    <hr />

    <button type="submit" class="registerbtn">Modify</button>
  </div>
</form>
<script>
    // 获取密码输入框和密码规则显示元素
    const passwordInput = document.querySelector('input[name="password"]');
    const passwordRules = document.getElementById('passwordRules');
    const passwordStrength = document.getElementById('passwordStrength');

    // 监听密码输入事件
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        // 校验密码强度并显示密码规则
        const strengthText = checkPasswordStrength(password);
        if (strengthText === 'weak') {
            passwordRules.style.display = 'block';
        } else {
            passwordRules.style.display = 'none';
        }

        passwordStrength.textContent = 'Password Strength: ' + strengthText;
        passwordStrength.style.display = 'block';
    });

    // 密码强度校验函数
    function checkPasswordStrength(password) {
        const hasLetter = /[a-zA-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecialChar = /[!@#$%^&*]/.test(password);
        if (password.length < 8) {
            return 'weak';
        } else if ((hasLetter && hasNumber) || (hasLetter && hasSpecialChar) || (hasNumber && hasSpecialChar)) {
            return 'strong';
        } else {
            return 'medium';
        }
    }

    const confirmPasswordInput = document.querySelector('input[name="confirmPassword"]');

    // 获取密码匹配错误消息的引用
    const passwordMatchError = document.getElementById('passwordMatchError');

    // 监听确认密码输入框的输入事件
    confirmPasswordInput.addEventListener('input', function() {
        // 检查两次输入的密码是否相同
        if (passwordInput.value !== confirmPasswordInput.value) {
            // 显示密码匹配错误消息
            passwordMatchError.style.display = 'block';
        } else {
            // 隐藏密码匹配错误消息
            passwordMatchError.style.display = 'none';
        }
    });

    // 获取电子邮件输入框和错误消息的引用
    const emailInput = document.getElementById('email');
    const errorMessage = document.getElementById('error-message');

    // 监听电子邮件输入框的输入事件
    emailInput.addEventListener('input', function() {
        // 获取输入的电子邮件值
        const email = emailInput.value;

        // 正则表达式用于验证电子邮件格式
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // 检查输入的电子邮件值是否符合格式
        if (emailPattern.test(email)) {
            // 隐藏错误消息
            errorMessage.style.display = 'none';
        } else {
            // 显示错误消息
            errorMessage.style.display = 'block';
        }
    });

    const phoneNumberInput = document.getElementById('phoneNumberInput');
    const phoneError = document.getElementById('phoneError');

    phoneNumberInput.addEventListener('input', function() {
        const phoneNumber = this.value;

        // 使用正则表达式验证手机号码格式
        const phoneRegex = /^[0-9]{11}$/;

        if (!phoneRegex.test(phoneNumber)) {
            phoneError.style.display = 'block';
        } else {
            phoneError.style.display = 'none';
        }
    });

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

    // Get the form element
    const form = document.getElementById("registerForm");

    // Add event listener to the form's submit event
    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent form submission

        if (passwordRules.style.display === 'block' || passwordMatchError.style.display === 'block' || errorMessage.style.display === 'block' || phoneError.style.display === 'block' || captchaMessage.style.display === 'block'){
            return
        }

        // Get the form data
        const formData = new FormData(form);
        // Send an AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../php/modify.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Registration success
                    console.log(xhr.responseText)
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert("Registration successful!");
                        document.cookie = "userInfo=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        document.cookie = "userId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        window.location.href = "login.php";
                        // Redirect to a success page or perform any other actions
                    } else {
                        alert(response.message);
                    }
                } else {
                    // Error occurred
                    alert("An error occurred during registration. Please try again later.");
                }
            }
        };
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.send(formData);
    });
</script>
</body>
</html>
