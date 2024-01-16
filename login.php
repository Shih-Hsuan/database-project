<?php
// 包含連接資料庫的程式碼（connMysql.php）
include 'connMysql.php';
$registrationSuccess = false; // 初始註冊狀態
$errorMessage = ""; // 初始化錯誤訊息
if (isset($_GET['error']) && $_GET['error'] === '1') {
    $errorMessage = "密碼錯誤";
}
if (isset($_GET['error']) && $_GET['error'] === '2') {
    $errorMessage = "找不到使用者";
}
session_start(); // 開始 SESSION
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    try {
        // 預備查詢，檢查是否有相符的使用者名稱
        $stmt = $db_link->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // 登入成功，將使用者重新導向到歡迎頁面
                $registrationSuccess = true; // 修改為成功
                $_SESSION['username'] = $username;
                // exit();
            } else {
                // 密碼錯誤，重新導向回登入頁面或顯示錯誤訊息
                header("Location: login.php?error=1");
                exit();
            }
        } else {
            // 找不到使用者，重新導向回登入頁面或顯示錯誤訊息
            header("Location: login.php?error=2");
            exit();
        }
    } catch (Exception $e) {
        echo "SQL 錯誤：" . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>登入</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            margin-top: 100px;
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container mx-auto">
            <h2 class="text-center">登入</h2>
            <form id="loginForm" action="login.php" method="post">
                <div class="form-group">
                    <label for="username">使用者帳號:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">密碼:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="button" class="btn btn-primary btn-block" onclick="submitForm()">登入</button>
                <a href="sign_up.php" class="btn btn-secondary btn-block mt-2">尚未註冊? 請先註冊</a>
            </form>
        </div>
    </div>

    <!-- 登入成功modal -->
    <div class="modal fade" id="loginSuccessModal" tabindex="-1" role="dialog" aria-labelledby="loginSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginSuccessModalLabel">登入成功！</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    您已成功登入。點擊下面的按鈕前往首頁。
                </div>
                <div class="modal-footer">
                    <a href="index.php" class="btn btn-primary">前往首頁</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 登入失敗modal -->
    <div class="modal fade" id="loginFailModal" tabindex="-1" role="dialog" aria-labelledby="loginFailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginFailModalLabel">登入失敗！</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    登入失敗，請檢查您的帳號和密碼後再試一次。
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS 和其他必要的程式 -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            <?php if ($registrationSuccess && !isset($_GET['error'])) : ?>
                $('#loginSuccessModal').modal('show');
            <?php elseif (isset($_GET['error'])) : ?>
                $('#loginFailModal').find('.modal-body').text("<?php echo $errorMessage ?>");
                $('#loginFailModal').modal('show');
            <?php endif; ?>
        });
    </script>


    <script>
        function submitForm() {
            // 執行表單提交
            document.getElementById("loginForm").submit();
        }
    </script>
</body>

</html>