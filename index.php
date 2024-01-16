<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>歡迎來到海大智fun機平台</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        padding: 20px;
        background: linear-gradient(to right, #2196F3, #33ccff); /* 漸層藍色背景 */
    }

    h1, p {
        color: #ffffff; /* 白色 */
    }

    .container {
        text-align: center;
        color: #ffffff; /* 白色 */
    }

    .btn-container {
        margin-top: 20px;
    }

    a.btn-custom {
        margin-top: 10px;
        text-decoration: none;
    }

    .login-message {
        background-color: #f8d7da;
        color: #721c24;
        border: 5px solid #f5c6cb;
        padding: 10px;
        margin-bottom: 15px;
    }

    .text-white {
        color: #ffffff; /* 白色 */
    }

    a.btn-warning {
        background-color: #ffcc00; /* 黃色 */
        border-color: #ffcc00; /* 黃色 */
    }
</style>

</head>

<body>
    <div class="container">
        <h1 class="mt-5">歡迎來到海大智fun機平台</h1>
        <p>
"NTOU_VendHub" 是一個致力於提供便利、促進互動的平台，目的在於讓海洋大學的學生們輕鬆地瀏覽智fun機內的商品，便捷地解決購物中遇到的問題，並在留言板上分享願望和建議。我們期待著每位使用者的參與，共同形成一個互助社群，提供有價值的反饋，進一步改進和優化智fun機的使用體驗。讓我們一同創造更便利、更貼心的購物環境，成就更豐富的校園生活。</p>
        <?php
        session_start();
        // 檢查是否已經有設置 $_SESSION['username']
        if (isset($_SESSION['username'])) {
            // 如果已經登入，顯示連結到不同頁面的按鈕
            echo '<a href="product_page.php" class="btn btn-primary btn-custom">產品頁面</a>';
            echo '<a href="fix_page.php" class="btn btn-info btn-custom">修繕回報頁面</a>';
            echo '<a href="wish_page.php" class="btn btn-success btn-custom">留言板許願</a>';
        } else {
            // 如果未登入，將用戶導向登入頁面
            echo '<div class="login-message">請先登入!</div>';
        }
        ?>
        <div class="btn-container">
            <?php
            // session_start();
            if (isset($_SESSION['username'])) {
                // 使用者已登入，顯示使用者帳號
                echo '<span class="text-white">使用者：' . $_SESSION['username'] . '</span>';
                echo '<a href="logout.php" class="btn btn-warning btn-custom ml-2">登出</a>';
            } else {
                // 使用者未登入
                echo '<span class="text-white">尚未登入</span>';
                echo '<a href="login.php" class="btn btn-warning btn-custom ml-2">登入</a>';
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>