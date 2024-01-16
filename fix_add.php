<?php
// 引入資料庫連接檔案
include 'connMysql.php';

// 確認 POST 變數是否存在並不為空
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $place_id = $_POST['place_id'];
    $product_counter = $_POST['product_counter'];
    $username = $_POST['username'];
    $fixboard_subject = $_POST['fixboard_subject'];
    $fixboard_time = $_POST['fixboard_time'];
    $fixboard_content = $_POST['fixboard_content'];
	
    // 其他表單欄位根據需求添加

    $insert_sql = "INSERT INTO fixboard (fixboard_id,place_id,product_counter,username,fixboard_subject,fixboard_time,fixboard_content) VALUES ('','$place_id', '$product_counter', '$username', '$fixboard_subject', '$fixboard_time', '$fixboard_content')";
    
    if ($db_link->query($insert_sql) === TRUE) {
        // echo "成功新增商品！";
        $message = "修繕表新增成功！請稍後";
        // 顯示訊息
        echo "<p>{$message}</p>";
        // 倒數計時 2 秒後重新導向至 product_page.php
        header("refresh:2;url=fix_page.php");
    } else {
        echo "修繕表新增失敗：" . $db_link->error;
    }
}
?>
