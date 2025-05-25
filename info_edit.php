<?php
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit();
}
?>
<?php
include('db.php');

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Prof_ID = $_POST['Prof_ID'] ?? '';
    $Prof_Name = $_POST['Prof_Name'] ?? '';
    $Prof_title = $_POST['Prof_title'] ?? '';
    $Prof_EmailAddress = $_POST['Prof_EmailAddress'] ?? '';
    $Prof_ExtensionNumber = $_POST['Prof_ExtensionNumber'] ?? '';

    if (empty($Prof_ID) || empty($Prof_Name) || empty($Prof_title) || empty($Prof_EmailAddress) || empty($Prof_ExtensionNumber)) {
        $error = "所有欄位都是必填的！";
    } else {
        $stmt = $mysqli->prepare("UPDATE teachers SET Prof_Name=?, Prof_title=?, Prof_EmailAddress=?, Prof_ExtensionNumber=? WHERE Prof_ID=?");
        $stmt->bind_param("sssss", $Prof_Name, $Prof_title, $Prof_EmailAddress, $Prof_ExtensionNumber, $Prof_ID);

        if ($stmt->execute()) {
            $success = "修改成功！";
        } else {
            $error = "修改失敗！";
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $Prof_ID = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM teachers WHERE Prof_ID = ?");
    $stmt->bind_param("s", $Prof_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        $error = "找不到該教師";
    }
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] != "POST") {
    $error = "未指定教師編號";
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>修改教師資料</title>
    <style>
        body { font-family: "Microsoft JhengHei", Arial, sans-serif; background: #f8f9fa; }
        .edit-form {
            background: #fff; padding: 20px; margin: 40px auto; width: 350px;
            border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        label { display: block; margin-bottom: 10px; }
        input[type="text"], input[type="email"] {
            width: 95%; padding: 6px 8px; margin-top: 4px; border: 1px solid #ccc; border-radius: 4px;
        }
        button {
            margin-top: 10px; padding: 8px 20px; background: #007bff; color: #fff;
            border: none; border-radius: 4px; cursor: pointer; font-size: 1em;
        }
        button:hover { background: #0056b3; }
        .msg-success { color: #28a745; margin-bottom: 12px; }
        .msg-error { color: #dc3545; margin-bottom: 12px; }
        .back-link { margin-left: 10px; }
    </style>
</head>
<body>
    <form class="edit-form" action="info_edit.php" method="post">
        <h2>修改教師資料</h2>
        <?php if ($success): ?>
            <div class="msg-success"><?= $success ?></div>
            <a href="dashboard.php" class="back-link">回首頁</a>
        <?php elseif ($error): ?>
            <div class="msg-error"><?= $error ?></div>
            <a href="dashboard.php" class="back-link">回首頁</a>
        <?php elseif (isset($row)): ?>
            <input type="hidden" name="Prof_ID" value="<?= htmlspecialchars($row['Prof_ID']) ?>">
            <label>姓名：
                <input type="text" name="Prof_Name" value="<?= htmlspecialchars($row['Prof_Name']) ?>" required>
            </label>
            <label>職稱：
                <input type="text" name="Prof_title" value="<?= htmlspecialchars($row['Prof_title']) ?>" required>
            </label>
            <label>電子郵件：
                <input type="email" name="Prof_EmailAddress" value="<?= htmlspecialchars($row['Prof_EmailAddress']) ?>" required>
            </label>
            <label>電話分機：
                <input type="text" name="Prof_ExtensionNumber" value="<?= htmlspecialchars($row['Prof_ExtensionNumber']) ?>" required>
            </label>
            <button type="submit">儲存修改</button>
            <a href="dashboard.php" class="back-link">取消</a>
        <?php endif; ?>
    </form>
</body>
</html>