<?php
// 修改演講資料
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit();
}
include('db.php');

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Speech_ID = $_POST['Speech_ID'] ?? '';
    $Prof_ID = $_POST['Prof_ID'] ?? '';
    $Speech_Name = $_POST['Speech_Name'] ?? '';
    $Speech_Audience = $_POST['Speech_Audience'] ?? '';
    $Speech_Date = $_POST['Speech_Date'] ?? '';

    if (empty($Speech_ID) || empty($Prof_ID) || empty($Speech_Name) || empty($Speech_Audience) || empty($Speech_Date)) {
        $error = "所有欄位都是必填的！";
    } else {
        $stmt = $mysqli->prepare("UPDATE Speech SET Prof_ID=?, Speech_Name=?, Speech_Audience=?, Speech_Date=? WHERE Speech_ID=?");
        $stmt->bind_param("ssssi", $Prof_ID, $Speech_Name, $Speech_Audience, $Speech_Date, $Speech_ID);
        if ($stmt->execute()) {
            $success = "修改成功！";
        } else {
            $error = "修改失敗！";
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $Speech_ID = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM Speech WHERE Speech_ID = ?");
    $stmt->bind_param("i", $Speech_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        $error = "找不到該演講";
    }
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] != "POST") {
    $error = "未指定演講編號";
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>修改演講資料</title>
</head>
<body>
    <form action="speech_edit.php" method="post">
        <h2>修改演講資料</h2>
        <?php if ($success): ?>
            <div style="color:green;">修改成功！</div>
            <a href="dashboard.php?tab=speech">回首頁</a>
        <?php elseif ($error): ?>
            <div style="color:red;"><?= $error ?></div>
            <a href="dashboard.php?tab=speech">回首頁</a>
        <?php elseif (isset($row)): ?>
            <input type="hidden" name="Speech_ID" value="<?= htmlspecialchars($row['Speech_ID']) ?>">
            <label>教師編號：<input type="text" name="Prof_ID" value="<?= htmlspecialchars($row['Prof_ID']) ?>" required></label><br>
            <label>演講名稱：<input type="text" name="Speech_Name" value="<?= htmlspecialchars($row['Speech_Name']) ?>" required></label><br>
            <label>對象/場合：<input type="text" name="Speech_Audience" value="<?= htmlspecialchars($row['Speech_Audience']) ?>" required></label><br>
            <label>日期：<input type="date" name="Speech_Date" value="<?= htmlspecialchars($row['Speech_Date']) ?>" required></label><br>
            <button type="submit">儲存修改</button>
            <a href="dashboard.php?tab=speech">取消</a>
        <?php endif; ?>
    </form>
</body>
</html>
