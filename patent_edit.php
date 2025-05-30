<?php
// 修改專利資料
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit();
}
include('db.php');

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Patent_ID = $_POST['Patent_ID'] ?? '';
    $Prof_ID = $_POST['Prof_ID'] ?? '';
    $Patent_Type = $_POST['Patent_Type'] ?? '';
    $Patent_Term = $_POST['Patent_Term'] ?? '';

    if (empty($Patent_ID) || empty($Prof_ID) || empty($Patent_Type) || empty($Patent_Term)) {
        $error = "所有欄位都是必填的！";
    } else {
        $stmt = $mysqli->prepare("UPDATE Patent SET Prof_ID=?, Patent_Type=?, Patent_Term=? WHERE Patent_ID=?");
        $stmt->bind_param("sssi", $Prof_ID, $Patent_Type, $Patent_Term, $Patent_ID);
        if ($stmt->execute()) {
            $success = "修改成功！";
        } else {
            $error = "修改失敗！";
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $Patent_ID = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM Patent WHERE Patent_ID = ?");
    $stmt->bind_param("i", $Patent_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        $error = "找不到該專利";
    }
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] != "POST") {
    $error = "未指定專利編號";
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>修改專利資料</title>
</head>
<body>
    <form action="patent_edit.php" method="post">
        <h2>修改專利資料</h2>
        <?php if ($success): ?>
            <div style="color:green;">修改成功！</div>
            <a href="dashboard.php?tab=patent">回首頁</a>
        <?php elseif ($error): ?>
            <div style="color:red;"><?= $error ?></div>
            <a href="dashboard.php?tab=patent">回首頁</a>
        <?php elseif (isset($row)): ?>
            <input type="hidden" name="Patent_ID" value="<?= htmlspecialchars($row['Patent_ID']) ?>">
            <label>教師編號：<input type="text" name="Prof_ID" value="<?= htmlspecialchars($row['Prof_ID']) ?>" required></label><br>
            <label>專利類型：<input type="text" name="Patent_Type" value="<?= htmlspecialchars($row['Patent_Type']) ?>" required></label><br>
            <label>專利名稱/內容：<input type="text" name="Patent_Term" value="<?= htmlspecialchars($row['Patent_Term']) ?>" required></label><br>
            <button type="submit">儲存修改</button>
            <a href="dashboard.php?tab=patent">取消</a>
        <?php endif; ?>
    </form>
</body>
</html>
