<?php
// 引入資料庫連接檔案
include 'connMysql.php';

// Start the session
session_start();
// Check if the user is an admin
$isAdmin = isset($_SESSION['username']) && $_SESSION['username'] === 'admin';

// 顯示所有商品
$sql = "SELECT p.*, COALESCE(AVG(pr.rating), 0) AS average_rating
        FROM product p
        LEFT JOIN product_reviews pr ON p.product_id = pr.product_id
        GROUP BY p.product_id;";
$result = $db_link->query($sql);
// 顯示所有商品的名稱 當作刪除的下拉式選單的選項
$sql_product_names = "SELECT product_id, product_name FROM product";
$result_product_names = $db_link->query($sql_product_names);

$sql_product_names_edit = "SELECT product_id, product_name FROM product";
$result_product_names_edit = $db_link->query($sql_product_names_edit);

$sql_product_names_review = "SELECT product_id, product_name FROM product";
$result_product_names_review = $db_link->query($sql_product_names_review);
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
    <div class="container">
        <h2>商品列表</h2>

        <form action="product_search.php" method="GET" class="form-inline">
            <div class="form-group mr-2">
                <input type="text" class="form-control" name="searchInput" id="searchInput" placeholder="搜尋商品">
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">搜尋</button>
            </div>
        </form>

        <!-- 其他按钮 -->
        <div class="btn-group ml-2">
            <!-- 使用 PHP 判断是否为管理员，如果不是则隐藏按钮 -->
            <button class="btn btn-primary <?php echo $isAdmin ? '' : 'd-none'; ?>" data-toggle="modal" data-target="#addProductModal">新增商品</button>
            <button class="btn btn-danger <?php echo $isAdmin ? '' : 'd-none'; ?>" data-toggle="modal" data-target="#deleteModal">下架商品</button>
            <button class="btn btn-info <?php echo $isAdmin ? '' : 'd-none'; ?>" data-toggle="modal" data-target="#editProductModal">編輯商品資訊</button>
            <button class="btn btn-warning" data-toggle="modal" data-target="#reviewProductModal">商品評分</button>
        </div>

        <!-- 刪除商品的模態 -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">選擇要刪除的商品</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="product_delete.php" method="post">
                            <!-- 下拉式選單 -->
                            <div class="form-group">
                                <label for="deleteProduct">選擇商品:</label>
                                <select class="form-control" id="deleteProduct" name="deleteProduct">
                                    <!-- 這裡是從資料庫取得的商品名稱 -->
                                    <?php
                                    $products = $result_product_names->fetch_all(MYSQLI_ASSOC);
                                    foreach ($products as $product) {
                                        echo "<option value='{$product['product_id']}'>{$product['product_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger">刪除</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 商品列表 -->
        <div class="d-flex flex-wrap">
            <!-- 商品資料將透過 JavaScript 動態填充至此 -->
            <?php
            $products = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($products as $product) {
                echo "<div class='product-block'>";
                if (filter_var($product['product_images'], FILTER_VALIDATE_URL)) {
                    // 如果 'product_images' 是一個有效的 URL，顯示圖片
                    echo "<img src='{$product['product_images']}' alt='商品圖片'>";
                } else {
                    echo "<img src='images/{$product['product_images']}.png' alt='商品圖片'>";
                }
                echo "<h3>{$product['product_name']}</h3>";
                if ($product['place_id'] == 1) {
                    echo "海大電資大樓一樓: {$product['product_counter']}號";
                } else {
                    echo "第三餐廳門口: {$product['product_counter']}號";
                }

                // 顯示商品價格
                echo "<div class='price'>";  // 加入 '.price' 類別名稱
                echo "價格: {$product['product_price']} 元";
                echo "</div>";
                // 顯示商品評分
                $average_rating = $product['average_rating'];
                echo "<div class='rating'>";  // 加入 '.rating' 類別名稱
                echo ($average_rating > 0 ? "評分: " . number_format($average_rating, 1). " 分" : "尚未有評分") . "";
                echo "</div>";

                echo "</div>";
            }
            ?>
        </div>
    </div>


    <!-- 新增商品的 Modal -->
    <div class="modal" id="addProductModal">
        <!-- Modal 內容 -->
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">新增商品</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="container">
                        <form action="product_add.php" method="post">
                            <div class="form-group">
                                <label for="category_id">商品分類:</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="1">零食點心</option>
                                    <option value="2">常溫/冷藏飲料</option>
                                    <option value="3">三明治</option>
                                    <option value="4">御飯糰/鮮食便當</option>
                                    <option value="5">杯裝泡麵/湯品</option>
                                    <option value="6">生理用品</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="place_id">櫃位地點:</label>
                                <select class="form-control" id="place_id" name="place_id">
                                    <option value="1">海大電資大樓一樓</option>
                                    <option value="2">第三餐廳門口</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product_name">商品名稱:</label>
                                <input type="text" class="form-control" id="product_name" name="product_name">
                            </div>
                            <div class="form-group">
                                <label for="product_price">商品價格:</label>
                                <input type="text" class="form-control" id="product_price" name="product_price">
                            </div>
                            <div class="form-group">
                                <label for="product_images">商品圖片:</label>
                                <input type="text" class="form-control" id="product_images" name="product_images">
                            </div>
                            <div class="form-group">
                                <label for="product_counter">商品櫃位:</label>
                                <input type="text" class="form-control" id="product_counter" name="product_counter">
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">提交</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 編輯商品的 Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">編輯商品資訊</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="product_mdy.php" method="post">
                            <!-- 這裡是您想要編輯的商品資訊 -->
                            <!-- 修改適當的表單元素來顯示編輯商品的不同屬性 -->
                            <div class="form-group">
                                <label for="edit_product_id">選擇想變更資訊的商品:</label>
                                <select class="form-control" id="edit_product_id" name="edit_product_id">
                                    <!-- 從資料庫取得的商品名稱 -->
                                    <?php
                                    $products = $result_product_names_edit->fetch_all(MYSQLI_ASSOC);
                                    foreach ($products as $product) {
                                        echo "<option value='{$product['product_id']}'>{$product['product_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_category_id">商品分類:</label>
                                <select class="form-control" id="edit_category_id" name="edit_category_id">
                                    <option value="1">零食點心</option>
                                    <option value="2">常溫/冷藏飲料</option>
                                    <option value="3">三明治</option>
                                    <option value="4">御飯糰/鮮食便當</option>
                                    <option value="5">杯裝泡麵/湯品</option>
                                    <option value="6">生理用品</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_place_id">櫃位地點:</label>
                                <select class="form-control" id="edit_place_id" name="edit_place_id">
                                    <option value="1">海大電資大樓一樓</option>
                                    <option value="2">第三餐廳門口</option>
                                </select>
                            </div>
                            <!-- 隱藏的商品ID欄位 -->
                            <!-- <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>"> -->
                            <div class="form-group">
                                <label for="edit_product_price">商品價格:</label>
                                <input type="text" class="form-control" id="edit_product_price" name="edit_product_price">
                            </div>
                            <div class="form-group">
                                <label for="edit_product_images">商品圖片:</label>
                                <input type="text" class="form-control" id="edit_product_images" name="edit_product_images">
                            </div>
                            <div class="form-group">
                                <label for="edit_product_counter">商品櫃位:</label>
                                <input type="text" class="form-control" id="edit_product_counter" name="edit_product_counter">
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">更新</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 商品評論的 Modal -->
    <div class="modal fade" id="reviewProductModal" tabindex="-1" role="dialog" aria-labelledby="reviewProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewProductModalLabel">新增商品評價</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="review.php" method="post">
                            <div class="form-group">
                                <label for="prod_id">選擇評價的商品:</label>
                                <select class="form-control" id="prod_id" name="prod_id">
                                    <!-- 從資料庫取得的商品名稱 -->
                                    <?php
                                    $products = $result_product_names_review->fetch_all(MYSQLI_ASSOC);
                                    foreach ($products as $product) {
                                        echo "<option value='{$product['product_id']}'>{$product['product_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                            <div class="form-group">
                                <label for="rating">評分 (1-5):</label>
                                <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">新增評價</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            <a href="index.php" class="btn btn-primary btn-custom">主頁面</a>
            <a href="fix_page.php" class="btn btn-info btn-custom">修繕回報頁面</a>
            <a href="wish_page.php" class="btn btn-success btn-custom">留言板許願</a>
        </div>
    </div>
</body>

</html>