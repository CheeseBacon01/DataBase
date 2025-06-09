<?php
// 刪除經歷資料
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit();
}
include('db.php');

$id = $_GET['id'] ?? '';
$success = '';
$error = '';

if ($id) {
    $user_id = $_SESSION['user_id'];
    $stmt = $mysqli->prepare("DELETE FROM Experience WHERE Experience_ID = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    if ($stmt->execute()) {
        $success = "刪除成功！";
    } else {
        $error = "刪除失敗！";
    }
} else {
    $error = "未指定經歷編號";
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>刪除經歷資料</title>
    <script>
    window.onload = function() {
        var msg = '';
        <?php if ($success): ?>
            msg = '刪除成功！';
        <?php else: ?>
            msg = <?= json_encode($error) ?>;
        <?php endif; ?>
        alert(msg);
        window.location.href = 'dashboard.php?tab=exp';
    };
    </script>
</head>
<body>
</body>
</html>
