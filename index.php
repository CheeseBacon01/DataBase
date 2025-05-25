<?php
session_start();
include('db.php');
$result = $mysqli->query("SELECT * FROM teachers");
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>逢甲資訊系統</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", Arial, sans-serif;
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            min-height: 100vh;
            margin: 0;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 0 40px;
            height: 64px;
        }
        .navbar-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 2px;
        }
        .navbar-menu {
            display: flex;
            gap: 32px;
        }
        .navbar-menu a {
            color: #2d3a4b;
            text-decoration: none;
            font-size: 1.1em;
            padding: 8px 0;
            transition: color 0.2s;
        }
        .navbar-menu a:hover {
            color: #007bff;
        }
        .msg-warning {
            color: #dc3545;
            text-align: center;
            margin-top: 16px;
            font-size: 1.1em;
        }
        h1 {
            text-align: center;
            margin-top: 40px;
            color: #2d3a4b;
            letter-spacing: 2px;
            text-shadow: 1px 2px 8px #fff8;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 24px;
            margin: 40px auto;
            max-width: 1100px;
        }
        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
            padding: 28px 36px;
            width: 320px;
            margin-bottom: 20px;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
            transform: translateY(-4px) scale(1.03);
        }
        .card-title {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 8px;
            color: #007bff;
        }
        .card-info {
            margin-bottom: 6px;
            color: #333;
        }
        .card-label {
            color: #888;
            font-size: 0.98em;
            margin-right: 4px;
        }
        @media (max-width: 700px) {
            .navbar { flex-direction: column; height: auto; padding: 0 10px; }
            .navbar-menu { gap: 16px; }
            .container { flex-direction: column; align-items: center; }
            .card { width: 90%; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-title">逢甲資訊系統</div>
        <div class="navbar-menu">
            <a href="index.php">教授資訊</a>
            <a href="#">課表</a>
            <?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true): ?>
                <a href="logout.php">登出</a>
            <?php else: ?>
                <a href="login.php">登入</a>
            <?php endif; ?>
            <a href="<?php echo (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) ? 'dashboard.php' : '#'; ?>"
               onclick="return <?php if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) { ?>alert('請先登入才能進入控制台！');false;<?php } else { ?>true;<?php } ?>">
               控制台
            </a>
        </div>
    </div>
    <?php if (isset($_GET['need_login'])): ?>
        <div class="msg-warning">請先登入才能進入控制台！</div>
    <?php endif; ?>
    <h1>教師資料一覽</h1>
    <div class="container">
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="card">
            <div class="card-title"><?= htmlspecialchars($row['Prof_Name']) ?></div>
            <div class="card-info">
                <span class="card-label">職稱：</span><?= htmlspecialchars($row['Prof_title']) ?>
            </div>
            <div class="card-info">
                <span class="card-label">電子郵件：</span><?= htmlspecialchars($row['Prof_EmailAddress']) ?>
            </div>
            <div class="card-info">
                <span class="card-label">電話分機：</span><?= htmlspecialchars($row['Prof_ExtensionNumber']) ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>