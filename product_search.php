<?php
// 引入資料庫連接檔案
include 'connMysql.php';

// 處理搜尋功能
if (isset($_GET['searchInput'])) {
    $searchKeyword = $_GET['searchInput'];

    // 根據產品名稱搜尋
    $searchQueryByProductName = "SELECT p.product_price, p.product_images, p.product_id, p.product_name, pl.place_name,
                COALESCE((SELECT AVG(pr.rating) 
                        FROM product_reviews pr 
                        WHERE pr.product_id = p.product_id), 0) AS average_rating
                        FROM product p
                        INNER JOIN place pl ON p.place_id = pl.place_id
                        WHERE p.product_name LIKE '%$searchKeyword%' or pl.place_name LIKE '%$searchKeyword%';";

    // 查詢搜尋到的商品總量
    $searchProductAmount = "SELECT *, COUNT(*) AS total_products FROM product WHERE product_name LIKE '%$searchKeyword%'";
    $result_search_productAmount = $db_link->query($searchProductAmount);

    // 查詢搜尋到的商品總量（基於地點名稱）
    $searchProductAmountByPlaceName = "SELECT COUNT(*) AS total_products 
        FROM product p 
        INNER JOIN place pl ON p.place_id = pl.place_id
        WHERE pl.place_name LIKE '%$searchKeyword%'";
    $result_search_productAmountByPlaceName = $db_link->query($searchProductAmountByPlaceName);

    // 合併兩個查询结果
    $result_search = $db_link->query($searchQueryByProductName);

    // 顯示搜尋結果
    echo "<h2>搜索结果</h2>";
    // 顯示搜尋到的商品總量
    $row = $result_search_productAmount->fetch_assoc();
    $totalProducts = $row['total_products'];
    if ($totalProducts == 0) {
        $row = $result_search_productAmountByPlaceName->fetch_assoc();
        $totalProducts = $row['total_products'];
    }

    echo "<h5>共找到 {$totalProducts} 個與 '{$searchKeyword}' 相關的商品</h5>";

    if ($result_search && $result_search->num_rows > 0) {
        echo "<div class='d-flex flex-wrap'>";
        while ($product_search = $result_search->fetch_assoc()) {
            echo "<div class='product-block'>";
            if (filter_var($product_search['product_images'], FILTER_VALIDATE_URL)) {
                // 如果 'product_images' 是一個有效的 URL，顯示圖片
                echo "<img src='{$product_search['product_images']}' alt='商品圖片'>";
            } else {
                echo "<img src='images/{$product_search['product_images']}.png' alt='商品圖片'>";
            }
            // 展示產品訊息
            echo "<h3>{$product_search['product_name']}</h3>";
            if ($product_search['place_name'] != 'N/A')
                echo "<div>{$product_search['place_name']}</div>";
            echo "價格: {$product_search['product_price']} 元";
            // 顯示商品評分
            $average_rating = $product_search['average_rating'];
            echo "<div class='rating'>";  // 加入 '.rating' 類別名稱
            echo ($average_rating > 0 ? "評分: " . number_format($average_rating, 1) . " 分" : "尚未有評分") . "";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "未找到與 '{$searchKeyword}' 相關的商品或地點";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品展示頁面</title>
    <!-- 引入商品區塊 CSS 樣式 -->
    <link rel="stylesheet" href="style/product_block.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        var lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            var currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > lastScrollTop) {
                // 向下滾動
                hideFooter();
            } else {
                // 向上滾動
                showFooter();
            }
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        });

        function showFooter() {
            var footer = document.getElementById('footerButtons');
            footer.style.bottom = '0';
        }

        function hideFooter() {
            var footer = document.getElementById('footerButtons');
            footer.style.bottom = `-${footer.clientHeight}px`;
        }
    </script>
    <div class="footer-buttons" id="footerButtons">
        <div class="container">
            <a href="product_page.php" class="btn btn-primary btn-custom">返回商品頁面</a>
        </div>
    </div>
</body>

</html>