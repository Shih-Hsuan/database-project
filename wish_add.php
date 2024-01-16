 <?php
// 引入資料庫連接檔案
include 'connMysql.php';

// 確認 POST 變數是否存在並不為空
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $wishboard_subject = $_POST['wishboard_subject'];
    $wishboard_time = $_POST['wishboard_time'];
    $wishboard_content = $_POST['wishboard_content'];
	
    // 其他表單欄位根據需求添加

    $insert_sql = "INSERT INTO wishboard (wishboard_id,username,wishboard_subject,wishboard_time,wishboard_content) VALUES ('', '$username', '$wishboard_subject', '$wishboard_time', '$wishboard_content')";
    
    if ($db_link->query($insert_sql) === TRUE) {
        // echo "成功新增許願表！";
        $message = "許願表新增成功！請稍後";
        // 顯示訊息
        echo "<p>{$message}</p>";
        // 倒數計時 2 秒後重新導向至 product_page.php
        header("refresh:2;url=wish_page.php");
    } else {
        echo "許願表新增失敗：" . $db_link->error;
    }
}
?>
