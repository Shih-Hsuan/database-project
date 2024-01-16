<?php
// 引入資料庫連接檔案
include 'connMysql.php';

// Start the session
session_start();
// Check if the user is an admin
$isAdmin = isset($_SESSION['username']) && $_SESSION['username'] === 'admin';

// 顯示所有商品
$sql = "SELECT * FROM wishboard";
$result = $db_link->query($sql);

$sql_wishboard_id = "SELECT wishboard_id FROM wishboard";

$result_wishboard_id = $db_link->query($sql_wishboard_id);

$sql_wishboard_id_edit = "SELECT wishboard_id FROM wishboard";

$result_wishboard_id_edit = $db_link->query($sql_wishboard_id_edit);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wish Page</title>
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
        <h2>留言板許願</h2>

        <!-- 搜尋欄 -->
        <div class="form-group">
            <input type="text" class="form-control" id="searchInput" placeholder="搜尋許願表" oninput="searchWishboard()">
        </div>

        <!-- 操作按鈕 -->
        <div class="btn-group ml-2">
            <button class="btn btn-primary" data-toggle="modal" data-target="#addWishboardModal">新增許願表</button>
            <button class="btn btn-danger <?php echo $isAdmin ? '' : 'd-none'; ?>" data-toggle="modal" data-target="#deleteModal">刪除許願表</button>
            <button class="btn btn-info <?php echo $isAdmin ? '' : 'd-none'; ?>" data-toggle="modal" data-target="#editWishboardModal">編輯許願表</button>
        </div>

        <!-- 許願表格 -->
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>許願表編號</th>
                        <th>使用者名稱</th>
                        <th>主題</th>
                        <th>填寫時間</th>
                        <th>內容</th>
                    </tr>
                </thead>
                <tbody id="wishboardTableBody">
                    <!-- 許願表格資料將透過 JavaScript 動態填充至此 -->
                    <?php
                    $wishboards = $result->fetch_all(MYSQLI_ASSOC);
                    foreach ($wishboards as $wishboard) {

                        echo "<tr><div class='wishboard-block'>";
                        echo "<td> {$wishboard['wishboard_id']} </td>";
                        echo "<td> {$wishboard['username']} </td>";
                        echo "<td> {$wishboard['wishboard_subject']} </td>";
                        echo "<td> {$wishboard['wishboard_time']} </td>";
                        echo "<td> {$wishboard['wishboard_content']} </td>";
                        echo "</div></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- 刪除許願表的模態 -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">選擇要刪除的許願表</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="wish_delete.php" method="post">
                        <!-- 下拉式選單 -->
                        <div class="form-group">
                            <label for="deleteWishboardId">選擇許願表編號:</label>
                            <select class="form-control" id="deleteWishboardId" name="deleteWishboardId">
                                <!-- 這裡是從資料庫取得的許願表名稱 -->
                                <?php
                                $wishboards = $result_wishboard_id->fetch_all(MYSQLI_ASSOC);
                                foreach ($wishboards as $wishboard) {
                                    echo "<option value='{$wishboard['wishboard_id']}'>{$wishboard['wishboard_id']}</option>";
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

    <!-- 新增許願表的 Modal -->
    <div class="modal" id="addWishboardModal">
        <!-- Modal 內容 -->
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">新增許願表</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- 新增許願表的表單將透過 JavaScript 動態填充至此 -->
                    <form method="post" action="wish_add.php">
                        <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                        <div class="form-group">
                            <label for="wishboard_subject">主題:</label>
                            <input type="text" class="form-control" name="wishboard_subject" id="wishboard_subject">
                        </div>
                        <div class="form-group">
                            <label for="wishboard_time">填寫時間:</label>
                            <input type="datetime-local" class="form-control" name="wishboard_time" id="wishboard_time">
                        </div>
                        <div class="form-group">
                            <label for="wishboard_content">內容:</label>
                            <input type="text" class="form-control" name="wishboard_content" id="wishboard_content">
                        </div>
                    <!-- </form> -->
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">確定新增</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                </div>

            </div>
        </div>
    </div>



    <!-- 編輯許願表的 Modal -->
    <div class="modal fade" id="editWishboardModal" tabindex="-1" role="dialog" aria-labelledby="editWishModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWishModalLabel">編輯許願表</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="wish_mdy.php" method="post">
                            <!-- 這裡是您想要編輯的許願表資訊 -->
                            <!-- 修改適當的表單元素來顯示編輯許願表的不同屬性 -->
                            <div class="form-group">
                                <label for="edit_wishboard_id">選擇想修改的許願表編號:</label>
                                <select class="form-control" id="edit_wishboard_id" name="edit_wishboard_id">
                                    <!-- 從資料庫取得的許願表主題 -->
                                    <?php
                                    $wishboards = $result_wishboard_id_edit->fetch_all(MYSQLI_ASSOC);
                                    foreach ($wishboards as $wishboard) {
                                        echo "<option value='{$wishboard['wishboard_id']}'>{$wishboard['wishboard_id']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="edit_username" id="edit_username" value="<?php echo $_SESSION['username']; ?>">
                            <div class="form-group">
                                <label for="edit_wishboard_subject">主題:</label>
                                <input type="text" class="form-control" name="edit_wishboard_subject" id="edit_wishboard_subject">
                            </div>
                            <div class="form-group">
                                <label for="edit_wishboard_time">填寫時間:</label>
                                <input type="datetime-local" class="form-control" name="edit_wishboard_time" id="edit_wishboard_time">
                            </div>
                            <div class="form-group">
                                <label for="edit_wishboard_content">內容:</label>
                                <input type="text" class="form-control" name="edit_wishboard_content" id="edit_wishboard_content">
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
        // 這裡放置與許願表相關的 JavaScript 邏輯
        function searchWishboard() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("wishboardTableBody");
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
        // 範例中的函數
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
            <a href="product_page.php" class="btn btn-success btn-custom">商品頁面</a>
            <a href="fix_page.php" class="btn btn-info btn-custom">修繕回報頁面</a>
        </div>
    </div>

</body>

</html>