<?php
include 'connMysql.php'; // 假设这里包含了数据库连接的代码

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 預備 SQL 語句
    $query = "SELECT get_product_average_rating(?) AS average_rating";

    // 預備語句並綁定參數
    $stmt = $db_link->prepare($query);
    $stmt->bind_param("i", $prod_id);

    $prod_id = $_POST['prod_id']; // 從表單中取得商品 ID

    // 執行預備語句
    $stmt->execute();

    // 取得結果
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $average_rating = $row['average_rating'];
        echo "商品的平均評分為: " . $average_rating;
    } else {
        echo "無法取得商品評分";
    }

    // 關閉連線
    $stmt->close();
}
?>