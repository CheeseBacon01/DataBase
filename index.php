<?php
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: front.php");
    exit();
}
?>

<?php
include('db.php');

// 查詢功能
$search_results = [];
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['search_keyword'])) {
    $keyword = '%' . $_POST['search_keyword'] . '%';
    $stmt = $mysqli->prepare("SELECT * FROM teachers WHERE Prof_Name LIKE ? OR Prof_title LIKE ?");
    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();
    $search_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>教師後台管理</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        h1 {
            background: #343a40;
            color: #fff;
            padding: 20px 0;
            margin: 0 0 30px 0;
            text-align: center;
        }
        .container {
            width: 95%;
            max-width: 800px;
            margin: 30px auto;
        }
        .section {
            display: none;
            margin-top: 30px;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        form label {
            display: block;
            margin-bottom: 10px;
        }
        form input[type="text"],
        form input[type="email"] {
            width: 95%;
            padding: 6px 8px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            margin-top: 10px;
            padding: 8px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        form button:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        th, td {
            padding: 10px 12px;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        th {
            background: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            margin-right: 8px;
        }
        a.delete-link {
            color: #dc3545;
        }
        a:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .container {
                width: 99%;
            }
            th, td {
                font-size: 0.95em;
                padding: 6px 4px;
            }
        }
    </style>
    <script>
        function showSection() {
            var select = document.getElementById('functionSelect');
            var addSection = document.getElementById('addSection');
            var deleteSection = document.getElementById('deleteSection');
            var searchSection = document.getElementById('searchSection');
            addSection.style.display = 'none';
            deleteSection.style.display = 'none';
            searchSection.style.display = 'none';
            if (select.value === 'add') {
                addSection.style.display = 'block';
            } else if (select.value === 'delete') {
                deleteSection.style.display = 'block';
            } else if (select.value === 'search') {
                searchSection.style.display = 'block';
            }
        }
        window.onload = function() {
            showSection();
        };
    </script>
</head>
<body>
    <h1>教師後台管理</h1>
    <a href="logout.php" style="float:right; margin: 20px 40px 0 0; font-size: 1.1em;">登出</a>
    <div class="container">
        <label for="functionSelect"><strong>請選擇功能：</strong></label>
        <select id="functionSelect" onchange="showSection()">
            <option value="">-- 請選擇 --</option>
            <option value="add">新增教師</option>
            <option value="delete">修改/刪除教師</option>
            <option value="search">查詢教師</option>
        </select>

        <div id="addSection" class="section">
            <h2>新增教師</h2>
            <form action="info_add.php" method="post">
                <label>教師編號：
                    <input type="text" name="Prof_ID" required>
                </label>
                <label>姓名：
                    <input type="text" name="Prof_Name" required>
                </label>
                <label>職稱：
                    <input type="text" name="Prof_title" required>
                </label>
                <label>電子郵件：
                    <input type="email" name="Prof_EmailAddress" required>
                </label>
                <label>電話分機：
                    <input type="text" name="Prof_ExtensionNumber" required>
                </label>
                <button type="submit">新增</button>
            </form>
        </div>

        <div id="deleteSection" class="section">
            <h2>修改/刪除教師</h2>
            <table>
                <tr>
                    <th>教師編號</th>
                    <th>姓名</th>
                    <th>職稱</th>
                    <th>電子郵件</th>
                    <th>電話分機</th>
                    <th>操作</th>
                </tr>
                <?php
                $result2 = $mysqli->query("SELECT * FROM teachers");
                while($row = $result2->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Prof_ID']) ?></td>
                    <td><?= htmlspecialchars($row['Prof_Name']) ?></td>
                    <td><?= htmlspecialchars($row['Prof_title']) ?></td>
                    <td><?= htmlspecialchars($row['Prof_EmailAddress']) ?></td>
                    <td><?= htmlspecialchars($row['Prof_ExtensionNumber']) ?></td>
                    <td>
                        <a href="info_edit.php?id=<?= urlencode($row['Prof_ID']) ?>">修改</a>
                        <a class="delete-link" href="info_delete.php?id=<?= urlencode($row['Prof_ID']) ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div id="searchSection" class="section">
            <h2>查詢教師</h2>
            <form method="post" action="">
                <label>關鍵字（姓名或職稱）：<input type="text" name="search_keyword" required></label>
                <button type="submit">查詢</button>
            </form>
            <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['search_keyword'])): ?>
                <h3>查詢結果：</h3>
                <table>
                    <tr>
                        <th>教師編號</th>
                        <th>姓名</th>
                        <th>職稱</th>
                        <th>電子郵件</th>
                        <th>電話分機</th>
                        <th>操作</th>
                    </tr>
                    <?php if (count($search_results) === 0): ?>
                        <tr><td colspan="6">查無資料</td></tr>
                    <?php else: ?>
                        <?php foreach($search_results as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Prof_ID']) ?></td>
                            <td><?= htmlspecialchars($row['Prof_Name']) ?></td>
                            <td><?= htmlspecialchars($row['Prof_title']) ?></td>
                            <td><?= htmlspecialchars($row['Prof_EmailAddress']) ?></td>
                            <td><?= htmlspecialchars($row['Prof_ExtensionNumber']) ?></td>
                            <td>
                                <a href="info_edit.php?id=<?= urlencode($row['Prof_ID']) ?>">修改</a>
                                <a class="delete-link" href="info_delete.php?id=<?= urlencode($row['Prof_ID']) ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>