<?php
// 帳號密碼管理功能
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php");
    exit();
}
include('db.php');

// 建立 user 資料表（如尚未建立）
$mysqli->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$success = '';
$error = '';
$row = null;

// 修改帳號密碼
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $id = intval($_POST['id']);
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username) {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("UPDATE users SET username=?, password=? WHERE id=?");
            $stmt->bind_param("ssi", $username, $hash, $id);
        } else {
            $stmt = $mysqli->prepare("UPDATE users SET username=? WHERE id=?");
            $stmt->bind_param("si", $username, $id);
        }
        if ($stmt->execute()) {
            $success = "修改成功！";
        } else {
            $error = "修改失敗，帳號可能重複。";
        }
        $stmt->close();
    } else {
        $error = "帳號不得為空。";
    }
    // 取得該帳號資料
    if ($id) {
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    }
}
// GET 顯示單一帳號編輯
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        $error = "找不到該帳號";
    }
    $stmt->close();
}
// 新增帳號
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash);
        if ($stmt->execute()) {
            $success = "新增帳號成功！";
        } else {
            $error = "新增失敗，帳號可能已存在。";
        }
        $stmt->close();
    } else {
        $error = "帳號與密碼皆必填。";
    }
}
// 預設顯示帳號列表
$users = $mysqli->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>帳號管理</title>
    <style>
        body { font-family: "Microsoft JhengHei", Arial, sans-serif; background: #f8f9fa; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.10); padding: 30px; }
        h2 { color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #e3e6ea; padding: 10px; text-align: center; }
        th { background: #007bff; color: #fff; }
        tr:nth-child(even) { background: #f4f8fb; }
        .msg-success { color: #28a745; margin-bottom: 12px; }
        .msg-error { color: #dc3545; margin-bottom: 12px; }
        form { margin-bottom: 18px; }
        input[type="text"], input[type="password"] { padding: 6px 10px; border-radius: 5px; border: 1px solid #b0b0b0; margin-right: 8px; }
        button { padding: 6px 18px; background: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .edit-form { background: #fff; padding: 20px; margin: 40px auto; width: 350px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .back-link { margin-left: 10px; }
    </style>
    <script>
    function showResultAndRedirect(msg, isSuccess, redirectUrl) {
        alert(msg);
        if (isSuccess && redirectUrl) {
            window.location.href = redirectUrl;
        }
    }
    </script>
</head>
<body>
<div class="container">
    <h2>帳號管理</h2>
    <?php if ($success): ?>
        <script>showResultAndRedirect('<?= $success ?>', true, 'dashboard.php');</script>
    <?php elseif ($error): ?>
        <script>showResultAndRedirect('<?= $error ?>', false, 'dashboard.php');</script>
    <?php elseif ($row): ?>
        <form class="edit-form" action="user_manage.php" method="post" onsubmit="return true;">
            <h3>修改帳號</h3>
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
            <label>帳號：<input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>" required></label><br>
            <label>新密碼：<input type="password" name="password" placeholder="不修改可留空"></label><br>
            <button type="submit" name="edit_user">儲存修改</button>
            <a href="dashboard.php" class="back-link">取消</a>
        </form>
    <?php else: ?>
        <form method="post" style="margin-bottom:24px;">
            <input type="text" name="username" placeholder="帳號" required>
            <input type="password" name="password" placeholder="密碼" required>
            <button type="submit" name="add_user">新增帳號</button>
        </form>
        <table>
            <tr><th>帳號</th><th>操作</th></tr>
            <?php while($row = $users->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td>
                    <a href="user_manage.php?id=<?= $row['id'] ?>">修改</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <div style="margin-top:18px;"><a href="dashboard.php">回後台</a></div>
    <?php endif; ?>
</div>
</body>
</html>
