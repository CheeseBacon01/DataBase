<?php
// 修改經歷資料
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit();
}
include('db.php');

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Experience_ID = $_POST['Experience_ID'] ?? '';
    $Prof_ID = $_POST['Prof_ID'] ?? '';
    $Experience_type = $_POST['Experience_type'] ?? '';
    $Experience_position = $_POST['Experience_position'] ?? '';

    if (empty($Experience_ID) || empty($Prof_ID) || empty($Experience_type) || empty($Experience_position)) {
        $error = "所有欄位都是必填的！";
    } else {
        $stmt = $mysqli->prepare("UPDATE Experience SET Prof_ID=?, Experience_type=?, Experience_position=? WHERE Experience_ID=?");
        $stmt->bind_param("sssi", $Prof_ID, $Experience_type, $Experience_position, $Experience_ID);
        if ($stmt->execute()) {
            $success = "修改成功！";
        } else {
            $error = "修改失敗！";
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $Experience_ID = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM Experience WHERE Experience_ID = ?");
    $stmt->bind_param("i", $Experience_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        $error = "找不到該經歷";
    }
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] != "POST") {
    $error = "未指定經歷編號";
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>修改經歷資料</title>
</head>
<body>
    <form action="exp_edit.php" method="post">
        <h2>修改經歷資料</h2>
        <?php if ($success): ?>
            <div style="color:green;">修改成功！</div>
            <a href="dashboard.php?tab=exp">回首頁</a>
        <?php elseif ($error): ?>
            <div style="color:red;"><?= $error ?></div>
            <a href="dashboard.php?tab=exp">回首頁</a>
        <?php elseif (isset($row)): ?>
            <input type="hidden" name="Experience_ID" value="<?= htmlspecialchars($row['Experience_ID']) ?>">
            <label>教師編號：<input type="text" name="Prof_ID" value="<?= htmlspecialchars($row['Prof_ID']) ?>" required></label><br>
            <label>經歷類型：<input type="text" name="Experience_type" value="<?= htmlspecialchars($row['Experience_type']) ?>" required></label><br>
            <label>職稱/職位：<input type="text" name="Experience_position" value="<?= htmlspecialchars($row['Experience_position']) ?>" required></label><br>
            <button type="submit">儲存修改</button>
            <a href="dashboard.php?tab=exp">取消</a>
        <?php endif; ?>
    </form>
</body>
</html>
