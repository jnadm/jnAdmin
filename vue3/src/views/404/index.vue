 <script setup lang='ts'>
 
 </script>
 
 <template>
     <div class="container">
        <img class="robot" src="../../../src/assets/images/404.png" alt="404 - Page Not Found">
         <p>页面被外星人劫持了！</p>
        <p>我们的程序员正在努力解救页面，请稍候再试。</p>
        <a href="/">返回首页</a>
    </div>
 </template>
 
 <style lang='scss' scoped>
    body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
        }
        h1 {
            font-size: 6em;
            color: #333;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
            color: #666;
            margin-bottom: 30px;
        }
        .robot {
            margin-top: 50px;
            width: 200px;
            margin-bottom: 30px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        } 
 </style>