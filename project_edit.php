<?php
// 修改計畫資料
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit();
}
include('db.php');

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Project_ID = $_POST['Project_ID'] ?? '';
    $Prof_ID = $_POST['Prof_ID'] ?? '';
    $Project_Name = $_POST['Project_Name'] ?? '';
    $Project_Duration = $_POST['Project_Duration'] ?? '';
    $Project_Type = $_POST['Project_Type'] ?? '';
    $Project_TakenPosition = $_POST['Project_TakenPosition'] ?? '';

    if (empty($Project_ID) || empty($Prof_ID) || empty($Project_Name) || empty($Project_Duration) || empty($Project_Type) || empty($Project_TakenPosition)) {
        $error = "所有欄位都是必填的！";
    } else {
        $stmt = $mysqli->prepare("UPDATE Project SET Prof_ID=?, Project_Name=?, Project_Duration=?, Project_Type=?, Project_TakenPosition=? WHERE Project_ID=?");
        $stmt->bind_param("sssssi", $Prof_ID, $Project_Name, $Project_Duration, $Project_Type, $Project_TakenPosition, $Project_ID);
        if ($stmt->execute()) {
            $success = "修改成功！";
        } else {
            $error = "修改失敗！";
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $Project_ID = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM Project WHERE Project_ID = ?");
    $stmt->bind_param("i", $Project_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        $error = "找不到該計畫";
    }
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] != "POST") {
    $error = "未指定計畫編號";
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>修改計畫資料</title>
</head>
<body>
    <form action="project_edit.php" method="post">
        <h2>修改計畫資料</h2>
        <?php if ($success): ?>
            <div style="color:green;">修改成功！</div>
            <a href="dashboard.php?tab=project">回首頁</a>
        <?php elseif ($error): ?>
            <div style="color:red;"><?= $error ?></div>
            <a href="dashboard.php?tab=project">回首頁</a>
        <?php elseif (isset($row)): ?>
            <input type="hidden" name="Project_ID" value="<?= htmlspecialchars($row['Project_ID']) ?>">
            <label>教師編號：<input type="text" name="Prof_ID" value="<?= htmlspecialchars($row['Prof_ID']) ?>" required></label><br>
            <label>計畫名稱：<input type="text" name="Project_Name" value="<?= htmlspecialchars($row['Project_Name']) ?>" required></label><br>
            <label>計畫期間：<input type="text" name="Project_Duration" value="<?= htmlspecialchars($row['Project_Duration']) ?>" required></label><br>
            <label>計畫類型：
                <select name="Project_Type" required>
                    <option value="國科會" <?= $row['Project_Type']==='國科會'?'selected':'' ?>>國科會</option>
                    <option value="產學合作" <?= $row['Project_Type']==='產學合作'?'selected':'' ?>>產學合作</option>
                </select>
            </label><br>
            <label>擔任職務：<input type="text" name="Project_TakenPosition" value="<?= htmlspecialchars($row['Project_TakenPosition']) ?>" required></label><br>
            <button type="submit">儲存修改</button>
            <a href="dashboard.php?tab=project">取消</a>
        <?php endif; ?>
    </form>
</body>
</html>
