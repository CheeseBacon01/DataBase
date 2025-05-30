<?php
// 刪除獎項資料
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
    $stmt = $mysqli->prepare("DELETE FROM Award WHERE Award_ID = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "刪除成功！";
    } else {
        $error = "刪除失敗！";
    }
    $stmt->close();
} else {
    $error = "未指定獎項編號";
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>刪除獎項資料</title>
</head>
<body>
    <?php if ($success): ?>
        <div style="color:green;">刪除成功！</div>
        <a href="dashboard.php?tab=award">回首頁</a>
    <?php else: ?>
        <div style="color:red;"><?= $error ?></div>
        <a href="dashboard.php?tab=award">回首頁</a>
    <?php endif; ?>
</body>
</html>
