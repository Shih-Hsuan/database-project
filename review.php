<?php
include 'connMysql.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prod_id = $_POST['prod_id'];
    $usr_name = $_POST['username'];
    $rtg = $_POST['rating'];

    // 預備 SQL 語句
    $query = "SELECT add_product_review(?, ?, ?) AS review_id";

    // 預備語句並綁定參數
    $stmt = $db_link->prepare($query);
    $stmt->bind_param("isi", $prod_id, $usr_name, $rtg);

    // 執行預備語句
    $stmt->execute();

    // 取得結果
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $review_id = $row['review_id'];
        echo "新增評價成功，評價商品 ID: " . $review_id . "，正在計算平均分數";
        header("refresh:2;url=product_page.php");
    } else {
        echo "新增評價失敗";
    }

    // 關閉連線
    $stmt->close();
}
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>新增產品評價</title>
</head>
<body>
    <h2>新增產品評價</h2>
    <form action="review.php" method="post">
        <label for="prod_id">商品 ID:</label>
        <input type="text" id="prod_id" name="prod_id" required><br><br>

        <label for="username">使用者名稱:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="rating">評分 (1-5):</label>
        <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

        <input type="submit" value="新增評價">
    </form>
</body>
</html> -->
