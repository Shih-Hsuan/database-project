<?php
// 引入資料庫連接檔案
include 'connMysql.php';

try {
    // 確認 POST 變數是否存在並不為空
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["product_name"])) {
            throw new Exception("商品名稱不能為空！");
        }
        if (empty($_POST["product_price"])) {
            throw new Exception("商品價格不能為空！");
        }
        // 獲取 POST 資料
        $category_id = $_POST["category_id"];
        $place_id = $_POST["place_id"];
        $product_name = $_POST["product_name"];
        $product_price = $_POST["product_price"];
        $product_images = $_POST["product_images"];
        $product_counter = $_POST["product_counter"];
        // 新增商品到資料庫
        $sql = "INSERT INTO product (product_id, category_id, place_id, product_name, product_price, product_images, product_counter) VALUES (?,?,?,?,?,?,?)";
        // 預備語句
        $stmt = $db_link->prepare($sql);
        // 綁定參數 i: int s: string
        $stmt->bind_param("iiissss", $emptyValue, $category_id, $place_id, $product_name, $product_price, $product_images, $product_counter);
        // 空值用於預備語句的自增ID欄位
        $emptyValue = null;
        // 執行預備語句
        $stmt->execute();
        // 判斷是否成功新增商品
        if ($stmt->affected_rows > 0) {
            $message = "商品已成功上架！請稍後";
            echo "<p>{$message}</p>";
            // 倒數計時 2 秒後重新導向至 product_page.php
            header("refresh:2;url=product_page.php");
        } else {
            echo "新增商品失敗";
        }
        // 關閉預備語句
        $stmt->close();
    }
} catch (Exception $e) {
    echo "錯誤訊息：" . $e->getMessage();
}
?>