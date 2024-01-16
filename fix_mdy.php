<?php
// 引入資料庫連接檔案
include 'connMysql.php';

// 確認是否有提交表單
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 處理表單資料
    $fixboard_id = $_POST['edit_fixboard_id'];
    $place_id = $_POST['edit_place_id'];
    $product_counter = $_POST['edit_product_counter'];
    $username = $_POST['edit_username'];
    $fixboard_subject = $_POST['edit_fixboard_subject'];
    $fixboard_time = $_POST['edit_fixboard_time'];
    $fixboard_content = $_POST['edit_fixboard_content'];
    

    // 做資料庫更新
    $update_sql = "UPDATE fixboard SET 
                    place_id = '$place_id', 
                    product_counter = '$product_counter',
					username = '$username', 
                    fixboard_subject = '$fixboard_subject', 
                    fixboard_time = '$fixboard_time', 
                    fixboard_content = '$fixboard_content'
                    WHERE fixboard_id = $fixboard_id";
    
       if ($db_link->query($update_sql) === TRUE) {
        // echo "成功新增商品！";
        $message = "修繕表修改成功！請稍後";
        // 顯示訊息
        echo "<p>{$message}</p>";
        // 倒數計時 2 秒後重新導向至 product_page.php
        header("refresh:2;url=fix_page.php");
    } else {
        echo "修繕表修改失敗：" . $db_link->error;
    }
}
?>