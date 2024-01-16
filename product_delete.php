<?php
// 引入資料庫連接檔案
include 'connMysql.php';

// 獲取要刪除的商品ID
if (isset($_POST['deleteProduct'])) {
    try {
        $deleteProductId = $_POST['deleteProduct'];
        // 在這裡執行從資料庫中刪除商品的操作
        $deleteQuery = "DELETE FROM product WHERE product_id = $deleteProductId";
        if ($db_link->query($deleteQuery) === TRUE) {
            // 刪除成功，顯示訊息
            echo "商品已成功刪除！";
            // 執行倒數計時 2 秒後重新導向至 product_page.php
            header("refresh:2;url=product_page.php");
        } else {
            throw new Exception("刪除商品時發生錯誤：" . $db_link->error);
        }
    } catch (Exception $e) {
        echo "錯誤: " . $e->getMessage();
    }
} else {
     echo "無效的商品 ID。";
}
?>