<?php
// 引入資料庫連接檔案
include 'connMysql.php';

// 獲取要刪除的商品ID
if (isset($_POST['deleteFixboardId'])) {
    $deleteFixboardId = $_POST['deleteFixboardId'];
    // 在這裡執行從資料庫中刪除商品的操作
    $deleteQuery = "DELETE FROM fixboard WHERE fixboard_id = $deleteFixboardId";
    if ($db_link->query($deleteQuery) === TRUE) {
        // 刪除成功，顯示訊息
        echo "修繕表已成功刪除！";
        // 執行倒數計時 2 秒後重新導向至 product_page.php
        header("refresh:2;url=fix_page.php");
    } else {
        echo "修繕表刪除時發生錯誤：" . $db_link->error;
    }
} else {
     echo "無效的修繕表ID。";
}
?>