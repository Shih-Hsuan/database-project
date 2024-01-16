 <?php
// 引入資料庫連接檔案
include 'connMysql.php';

// 確認是否有提交表單
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 處理表單資料
    $wishboard_id = $_POST['edit_wishboard_id'];
    $username = $_POST['edit_username'];
    $wishboard_subject = $_POST['edit_wishboard_subject'];
    $wishboard_time = $_POST['edit_wishboard_time'];
    $wishboard_content = $_POST['edit_wishboard_content'];
    

    // 做資料庫更新
    $update_sql = "UPDATE wishboard SET 
					username = '$username', 
                    wishboard_subject = '$wishboard_subject', 
                    wishboard_time = '$wishboard_time', 
                    wishboard_content = '$wishboard_content'
                    WHERE wishboard_id = $wishboard_id";
    
       if ($db_link->query($update_sql) === TRUE) {
        // echo "成功修改許願表！";
        $message = "許願表修改成功！請稍後";
        // 顯示訊息
        echo "<p>{$message}</p>";
        // 倒數計時 2 秒後重新導向至 wish_page.php
        header("refresh:2;url=wish_page.php");
    } else {
        echo "許願表修改失敗：" . $db_link->error;
    }
}
?>