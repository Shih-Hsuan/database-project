 <?php
// 引入資料庫連接檔案
include 'connMysql.php';

// 獲取要刪除的許願表ID
if (isset($_POST['deleteWishboardId'])) {
    $deleteWishboardId = $_POST['deleteWishboardId'];
    // 在這裡執行從資料庫中刪除許願表的操作
    $deleteQuery = "DELETE FROM wishboard WHERE wishboard_id = $deleteWishboardId";
    if ($db_link->query($deleteQuery) === TRUE) {
        // 刪除成功，顯示訊息
        echo "許願表已成功刪除！";
        // 執行倒數計時 2 秒後重新導向至 wish_page.php
        header("refresh:2;url=wish_page.php");
    } else {
        echo "許願表刪除時發生錯誤：" . $db_link->error;
    }
} else {
     echo "無效的許願表ID。";
}
?>