<?php
// 修改教材與作品資料
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit();
}
include('db.php');

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TeachMat_ID = $_POST['TeachMat_ID'] ?? '';
    $Prof_ID = $_POST['Prof_ID'] ?? '';
    $TeachMat_Author = $_POST['TeachMat_Author'] ?? '';
    $TeachMat_Name = $_POST['TeachMat_Name'] ?? '';
    $TeachMat_Publisher = $_POST['TeachMat_Publisher'] ?? '';

    if (empty($TeachMat_ID) || empty($Prof_ID) || empty($TeachMat_Author) || empty($TeachMat_Name) || empty($TeachMat_Publisher)) {
        $error = "所有欄位都是必填的！";
    } else {
        $stmt = $mysqli->prepare("UPDATE TeachingMaterials SET Prof_ID=?, TeachMat_Author=?, TeachMat_Name=?, TeachMat_Publisher=? WHERE TeachMat_ID=?");
        $stmt->bind_param("ssssi", $Prof_ID, $TeachMat_Author, $TeachMat_Name, $TeachMat_Publisher, $TeachMat_ID);
        if ($stmt->execute()) {
            $success = "修改成功！";
        } else {
            $error = "修改失敗！";
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $TeachMat_ID = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM TeachingMaterials WHERE TeachMat_ID = ?");
    $stmt->bind_param("i", $TeachMat_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        $error = "找不到該教材/作品";
    }
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] != "POST") {
    $error = "未指定教材/作品編號";
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>修改教材與作品資料</title>
</head>
<body>
    <form action="teachmat_edit.php" method="post">
        <h2>修改教材與作品資料</h2>
        <?php if ($success): ?>
            <div style="color:green;">修改成功！</div>
            <a href="dashboard.php?tab=teachmat">回首頁</a>
        <?php elseif ($error): ?>
            <div style="color:red;"><?= $error ?></div>
            <a href="dashboard.php?tab=teachmat">回首頁</a>
        <?php elseif (isset($row)): ?>
            <input type="hidden" name="TeachMat_ID" value="<?= htmlspecialchars($row['TeachMat_ID']) ?>">
            <label>教師編號：<input type="text" name="Prof_ID" value="<?= htmlspecialchars($row['Prof_ID']) ?>" required></label><br>
            <label>作者：<input type="text" name="TeachMat_Author" value="<?= htmlspecialchars($row['TeachMat_Author']) ?>" required></label><br>
            <label>教材/作品名稱：<input type="text" name="TeachMat_Name" value="<?= htmlspecialchars($row['TeachMat_Name']) ?>" required></label><br>
            <label>出版社/發表單位：<input type="text" name="TeachMat_Publisher" value="<?= htmlspecialchars($row['TeachMat_Publisher']) ?>" required></label><br>
            <button type="submit">儲存修改</button>
            <a href="dashboard.php?tab=teachmat">取消</a>
        <?php endif; ?>
    </form>
</body>
</html>
