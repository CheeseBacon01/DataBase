<?php
// 修改獎項資料
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit();
}
include('db.php');

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Award_ID = $_POST['Award_ID'] ?? '';
    $Prof_ID = $_POST['Prof_ID'] ?? '';
    $Award_Advisee = $_POST['Award_Advisee'] ?? '';
    $Award_ProjectName = $_POST['Award_ProjectName'] ?? '';
    $Award_CompName_Position = $_POST['Award_CompName_Position'] ?? '';
    $Award_Date = $_POST['Award_Date'] ?? '';
    $Award_organizer = $_POST['Award_organizer'] ?? '';

    if (empty($Award_ID) || empty($Prof_ID) || empty($Award_Advisee) || empty($Award_ProjectName) || empty($Award_CompName_Position) || empty($Award_Date) || empty($Award_organizer)) {
        $error = "所有欄位都是必填的！";
    } else {
        $stmt = $mysqli->prepare("UPDATE Award SET Prof_ID=?, Award_Advisee=?, Award_ProjectName=?, Award_CompName_Position=?, Award_Date=?, Award_organizer=? WHERE Award_ID=?");
        $stmt->bind_param("ssssssi", $Prof_ID, $Award_Advisee, $Award_ProjectName, $Award_CompName_Position, $Award_Date, $Award_organizer, $Award_ID);
        if ($stmt->execute()) {
            $success = "修改成功！";
        } else {
            $error = "修改失敗！";
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $Award_ID = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM Award WHERE Award_ID = ?");
    $stmt->bind_param("i", $Award_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        $error = "找不到該獎項";
    }
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] != "POST") {
    $error = "未指定獎項編號";
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>修改獎項資料</title>
</head>
<body>
    <form action="award_edit.php" method="post">
        <h2>修改獎項資料</h2>
        <?php if ($success): ?>
            <div style="color:green;">修改成功！</div>
            <a href="dashboard.php?tab=award">回首頁</a>
        <?php elseif ($error): ?>
            <div style="color:red;"><?= $error ?></div>
            <a href="dashboard.php?tab=award">回首頁</a>
        <?php elseif (isset($row)): ?>
            <input type="hidden" name="Award_ID" value="<?= htmlspecialchars($row['Award_ID']) ?>">
            <label>教師編號：<input type="text" name="Prof_ID" value="<?= htmlspecialchars($row['Prof_ID']) ?>" required></label><br>
            <label>學生姓名：<input type="text" name="Award_Advisee" value="<?= htmlspecialchars($row['Award_Advisee']) ?>" required></label><br>
            <label>作品/計畫名稱：<input type="text" name="Award_ProjectName" value="<?= htmlspecialchars($row['Award_ProjectName']) ?>" required></label><br>
            <label>競賽名稱與名次：<input type="text" name="Award_CompName_Position" value="<?= htmlspecialchars($row['Award_CompName_Position']) ?>" required></label><br>
            <label>得獎日期：<input type="date" name="Award_Date" value="<?= htmlspecialchars($row['Award_Date']) ?>" required></label><br>
            <label>主辦單位：<input type="text" name="Award_organizer" value="<?= htmlspecialchars($row['Award_organizer']) ?>" required></label><br>
            <button type="submit">儲存修改</button>
            <a href="dashboard.php?tab=award">取消</a>
        <?php endif; ?>
    </form>
</body>
</html>
