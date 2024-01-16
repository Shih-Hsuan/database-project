<?php
// 引入資料庫連接檔案
include 'connMysql.php';

// Start the session
session_start();
// Check if the user is an admin
$isAdmin = isset($_SESSION['username']) && $_SESSION['username'] === 'admin';

// 顯示所有商品
$sql = "SELECT * FROM fixboard";
$result = $db_link->query($sql);

$sql_fixboard_id = "SELECT fixboard_id FROM fixboard";

$result_fixboard_id = $db_link->query($sql_fixboard_id);

$sql_fixboard_id_edit = "SELECT fixboard_id FROM fixboard";

$result_fixboard_id_edit = $db_link->query($sql_fixboard_id_edit);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        .btn-container {
            margin-top: 10px;
        }

        .footer-buttons {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            transition: bottom 0.3s ease;
            /* 添加過渡效果 */
            z-index: 1000;
            /* 確保位於最上層 */
        }


        .footer-buttons .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>修繕回報</h2>
        <!-- 搜尋欄 -->
        <div class="form-group">
            <input type="text" class="form-control" id="searchInput" placeholder="搜尋修繕表" oninput="searchFixboard()">
        </div>

        <!-- 操作按鈕 -->
        <div class="btn-group ml-2">
            <button class="btn btn-primary" data-toggle="modal" data-target="#addFixboardModal">新增修繕表</button>
            <button class="btn btn-danger <?php echo $isAdmin ? '' : 'd-none'; ?>" data-toggle="modal" data-target="#deleteModal">刪除修繕表</button>
            <button class="btn btn-info <?php echo $isAdmin ? '' : 'd-none'; ?>" data-toggle="modal" data-target="#editProductModal">編輯修繕表</button>
        </div>

        <!-- 修繕表格 -->
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>修繕表編號</th>
                        <th>販賣機地點</th>
                        <th>商品在哪一個櫃位</th>
                        <th>使用者名稱</th>
                        <th>主題</th>
                        <th>填寫時間</th>
                        <th>內容</th>
                    </tr>
                </thead>
                <tbody id="fixboardTableBody">
                    <tr>
                        <!-- 修繕表格資料將透過 JavaScript 動態填充至此 -->
                        <?php
                        $fixboards = $result->fetch_all(MYSQLI_ASSOC);
                        foreach ($fixboards as $fixboard) {

                            echo "<tr><div class='fixboard-block'>";
                            echo "<td>{$fixboard['fixboard_id']}</td>";
                            echo "<td>";

                            if ($fixboard['place_id'] == 1) {
                                echo "海大電資大樓一樓";
                            } else {
                                echo "第三餐廳門口";
                            }

                            echo "</td>";
                            echo "<td> {$fixboard['product_counter']} </td>";
                            echo "<td> {$fixboard['username']} </td>";
                            echo "<td> {$fixboard['fixboard_subject']} </td>";
                            echo "<td> {$fixboard['fixboard_time']} </td>";
                            echo "<td> {$fixboard['fixboard_content']} </td>";
                            echo "</div></tr>";
                        }
                        ?>
                    </tr>
                    <!-- 示範資料 -->

                </tbody>
            </table>
        </div>

        <!-- 刪除商品的模態 -->

        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">選擇要刪除的修繕表</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="fix_delete.php" method="post">
                            <!-- 下拉式選單 -->
                            <div class="form-group">
                                <label for="deleteFixboardId">選擇修繕表編號:</label>
                                <select class="form-control" id="deleteFixboardId" name="deleteFixboardId">
                                    <!-- 這裡是從資料庫取得的商品名稱 -->
                                    <?php
                                    $fixboards = $result_fixboard_id->fetch_all(MYSQLI_ASSOC);
                                    foreach ($fixboards as $fixboard) {
                                        echo "<option value='{$fixboard['fixboard_id']}'>{$fixboard['fixboard_id']}</option>";
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
    </div>

    <!-- 新增修繕表的 Modal -->
    <div class="modal" id="addFixboardModal">
        <!-- Modal 內容 -->
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">新增修繕表</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- 新增修繕表的表單 -->
                    <form method="post" action="fix_add.php">
                        <div class="form-group">
                            <label for="place_id">櫃位地點:</label>
                            <select class="form-control" id="place_id" name="place_id">
                                <option value="1">海大電資大樓一樓</option>
                                <option value="2">第三餐廳門口</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product_counter">商品在哪一個櫃位:</label>
                            <input type="number" class="form-control" name="product_counter" id="product_counter">
                        </div>
                        <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                        <div class="form-group">
                            <label for="fixboard_subject">主題:</label>
                            <input type="text" class="form-control" name="fixboard_subject" id="fixboard_subject">
                        </div>
                        <div class="form-group">
                            <label for="fixboard_time">填寫時間:</label>
                            <input type="datetime-local" class="form-control" name="fixboard_time" id="fixboard_time">
                        </div>
                        <div class="form-group">
                            <label for="fixboard_content">內容:</label>
                            <input type="text" class="form-control" name="fixboard_content" id="fixboard_content">
                        </div>
                        <!-- 其他表單欄位根據需求添加 -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">確定新增</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        </div>
                    </form>
                    <!-- Modal Footer -->

                </div>
            </div>
        </div>
    </div>

    <!-- 編輯商品的 Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">編輯修繕表</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="fix_mdy.php" method="post">
                            <!-- 這裡是您想要編輯的商品資訊 -->
                            <!-- 修改適當的表單元素來顯示編輯商品的不同屬性 -->
                            <div class="form-group">
                                <label for="edit_fixboard_id">選擇想修改的修繕表編號:</label>
                                <select class="form-control" id="edit_fixboard_id" name="edit_fixboard_id">
                                    <!-- 從資料庫取得的商品名稱 -->
                                    <?php
                                    $fixboards = $result_fixboard_id_edit->fetch_all(MYSQLI_ASSOC);
                                    foreach ($fixboards as $fixboard) {
                                        echo "<option value='{$fixboard['fixboard_id']}'>{$fixboard['fixboard_id']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_place_id">櫃位地點:</label>
                                <select class="form-control" id="edit_place_id" name="edit_place_id">
                                    <option value="1">海大電資大樓一樓</option>
                                    <option value="2">第三餐廳門口</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_product_counter">商品在哪一個櫃位:</label>
                                <input type="number" class="form-control" name="edit_product_counter" id="edit_product_counter">
                            </div>
                            <input type="hidden" name="edit_username" id="edit_username" value="<?php echo $_SESSION['username']; ?>">
                            <div class="form-group">
                                <label for="edit_fixboard_subject">主題:</label>
                                <input type="text" class="form-control" name="edit_fixboard_subject" id="edit_fixboard_subject">
                            </div>
                            <div class="form-group">
                                <label for="edit_fixboard_time">填寫時間:</label>
                                <input type="datetime-local" class="form-control" name="edit_fixboard_time" id="edit_fixboard_time">
                            </div>
                            <div class="form-group">
                                <label for="edit_fixboard_content">內容:</label>
                                <input type="text" class="form-control" name="edit_fixboard_content" id="edit_fixboard_content">
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

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- 自訂的 JavaScript -->
    <script>
        // 示範搜尋功能
        function searchFixboard() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("fixboardTableBody");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // 修改為要搜尋的欄位， 0~6 0=修繕表編號 6=內容	
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // 示範新增修繕表的邏輯
        function addFixboard() {
            // 獲取表單資料，這裡以示範的表單為例
            var location = document.getElementById("location").value;

            // 執行實際新增修繕表的邏輯，可以發送到後端處理，存入資料庫等等...

            // 清空表單
            document.getElementById("addFixboardForm").reset();

            // 隱藏新增修繕表的 Modal
            $('#addFixboardModal').modal('hide');
        }
    </script>

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
            <a href="product_page.php" class="btn btn-info btn-custom">商品頁面</a>
            <a href="wish_page.php" class="btn btn-success btn-custom">留言板許願</a>
        </div>
    </div>
</body>

</html>