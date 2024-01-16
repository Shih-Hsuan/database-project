<?php
include 'connMysql.php';
$registrationSuccess = false; // 初始註冊狀態
$errorMessage = ""; // 初始化錯誤訊息
if (isset($_GET['error']) && $_GET['error'] === '1') {
    $errorMessage = "使用者帳號重複";
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // 獲取 POST 資訊
        $username = $_POST["username"];
        $password = $_POST["password"];

        // 使用密碼hash
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db_link->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            // 註冊成功
            $registrationSuccess = true; // 修改為成功
        }
    } catch (Exception $e) {
        header("Location: sign_up.php?error=1");
        $errorMessage = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>註冊使用者</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .register-container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 50px;
        }

        .register-container h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .form-group input[type="submit"] {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: none;
            background-color: #28a745;
            color: #fff;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>註冊</h2>
        <form action="sign_up.php" method="post">
            <div class="form-group">
                <label for="username">使用者帳號:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">使用者密碼:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="註冊">
            </div>
        </form>
    </div>

    <!-- 模態框 -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">註冊成功！</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    您已成功註冊。請點擊下面的按鈕以前往登入頁面。
                </div>
                <div class="modal-footer">
                    <a href="login.php" class="btn btn-primary">前往登入</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- 註冊失敗的 Modal -->
    <div class="modal fade" id="registerFailModal" tabindex="-1" aria-labelledby="registerFailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerFailModalLabel">註冊失敗</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    註冊失敗，請檢查您的資料並再試一次。
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            <?php if ($registrationSuccess && !isset($_GET['error'])) : ?>
                $('#myModal').modal('show');
            <?php elseif (isset($_GET['error'])) : ?>
                $('#registerFailModal').find('.modal-body').text("<?php echo $errorMessage ?>");
                $('#registerFailModal').modal('show');
            <?php endif; ?>
        });
    </script>

</body>

</html>