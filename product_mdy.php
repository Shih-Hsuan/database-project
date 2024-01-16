<?php
// 引入資料庫連接檔案
include 'connMysql.php';

// 確認是否有提交表單
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 處理表單資料
        $product_id = $_POST['edit_product_id'];
        $category_id = $_POST['edit_category_id'];
        $place_id = $_POST['edit_place_id'];
        $product_price = $_POST['edit_product_price'];
        $product_images = $_POST['edit_product_images'];
        $product_counter = $_POST['edit_product_counter'];
        // 做資料庫更新
        $update_sql = "UPDATE product SET 
                        category_id = '$category_id', 
                        place_id = '$place_id', 
                        product_price = '$product_price', 
                        product_images = '$product_images', 
                        product_counter = '$product_counter' 
                        WHERE product_id = $product_id";

        if ($db_link->query($update_sql) === TRUE) {
            // 更新成功後導向到商品列表頁面
            header("Location: product_page.php");
            exit();
        } else {
            throw new Exception("更新失敗: " . $db_link->error);
        }
    } catch (Exception $e) {
        echo "錯誤: " . $e->getMessage();
    }
}
?>