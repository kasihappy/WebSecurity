<html>
  <head>
    <meta charset="utf-8">
    <title>账户登录</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
      }
      .card-container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        width: 300px;
      }
      .input-group {
        margin-bottom: 15px;
      }
      input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
      }
      button {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }
      button:hover {
        background-color: #45a049;
      }
      #captcha-info {
        font-size: 12px;
        color: #666;
        margin-bottom: 15px;
      }
      #login-title {
        text-align: center;
        margin-bottom: 20px;
      }
      .error-message {
        color: red;
        font-size: 12px;
        margin-top: 5px;
      }
      /* 新增样式：验证码容器 */
      .captcha-container {
        display: flex;
        gap: 10px;
        align-items: center;
      }
      #captcha-input {
        flex: 1;
      }
      #captcha-img {
        width: 100px;
        height: 40px;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        cursor: pointer;
        user-select: none;
        font-weight: bold;
        letter-spacing: 2px;
      }
    </style>
  </head>

  <body>
    <div class="card-container">
      <form id="loginForm">
        <div id="login-title"><h3>身份认证</h3> </div>
        <div class="input-group">
          <input type="text" name="name" id="name" placeholder="学工号/手机号/邮箱" required>
        </div>
        <div class="input-group">
          <input type="password" name="passwd" id="passwd" placeholder="密码" required>
        </div>
        <div class="input-group captcha-container">
          <div id="captcha-input">
            <input type="text" name="code" id="code" placeholder="请输入验证码" required>
            <div id="code-error" class="error-message"></div>
          </div>
          <div id="captcha-img" title="点击刷新验证码">ABCD</div>
        </div>
        <input type="hidden" name="id" id="captcha-id" value="123">
        
        <p id="captcha-info">验证码只包含字母,不区分大小写</p>
        <div><button type="button" class="button" id="login-submit"><span>登录</span></button></div>
      </form>
    </div>

    <script>
      // 生成随机验证码
      function generateCaptcha() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        let captcha = '';
        for (let i = 0; i < 4; i++) {
          captcha += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return captcha;
      }

      // 更新验证码显示
      function updateCaptcha() {
        const captcha = generateCaptcha();
        document.getElementById('captcha-img').textContent = captcha;
        // 在实际应用中，这里应该调用后端获取验证码图片
        // 并设置captcha-id的值
      }

      // 初始化验证码
      updateCaptcha();

      // 点击验证码图片刷新验证码
      document.getElementById('captcha-img').addEventListener('click', updateCaptcha);

      document.getElementById('login-submit').addEventListener('click', function(e) {
        e.preventDefault();
        
        // 获取表单数据
        const code = document.getElementById('code').value;
        const captchaId = document.getElementById('captcha-id').value;
        const errorElement = document.getElementById('code-error');
        
        // 先验证验证码
        verifyCaptcha(code, captchaId)
          .then(result => {
            if (result === 1) {
              // 验证码正确，继续验证用户名密码
              const name = document.getElementById('name').value;
              const passwd = document.getElementById('passwd').value;
              
              return verifyCredentials(name, passwd);
            } else {
              // 验证码错误
              errorElement.textContent = '验证码错误，请重新输入';
              updateCaptcha(); // 刷新验证码
              document.getElementById('code').value = ''; // 清空输入框
              return Promise.reject('验证码错误');
            }
          })
          .then(loginResult => {
            // 处理登录结果
            if (loginResult.success) {
              alert('登录成功');
              // 这里可以跳转到其他页面
            } else {
              alert('登录失败: ' + loginResult.message);
            }
          })
          .catch(error => {
            console.error('登录过程出错:', error);
          });
      });

      // 验证验证码的函数
      function verifyCaptcha(code, captchaId) {
        // 这里应该是发送到后端验证验证码的API
        // 返回一个Promise，模拟异步请求
        return new Promise((resolve) => {
          // 模拟API请求
          setTimeout(() => {
            // 这里模拟验证逻辑：验证码不区分大小写
            const displayedCaptcha = document.getElementById('captcha-img').textContent;
            const isCorrect = code.toUpperCase() === displayedCaptcha.toUpperCase();
            resolve(isCorrect ? 1 : 0);
          }, 500);
        });
      }

      // 验证用户名密码的函数
      function verifyCredentials(name, passwd) {
        // 这里应该是发送到后端验证用户名密码的API
        return new Promise((resolve) => {
          // 模拟API请求
          setTimeout(() => {
            // 模拟返回结果
            resolve({ success: true, message: '登录成功' });
          }, 500);
        });
      }
    </script>
  </body>
</html>