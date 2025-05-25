<?php
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: front.php");
    exit();
}
?>

<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>教師後台管理</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", Arial, sans-serif;
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
            margin: 0;
            padding: 0;
        }
        h1 {
            background: #343a40;
            color: #fff;
            padding: 24px 0 20px 0;
            margin: 0 0 30px 0;
            text-align: center;
            letter-spacing: 2px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        nav {
            background: #fff;
            padding: 16px 0 12px 0;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.10);
            text-align: center;
            margin-bottom: 18px;
        }
        nav a {
            margin: 0 24px;
            font-weight: bold;
            color: #007bff;
            font-size: 1.15em;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background 0.2s, color 0.2s;
        }
        nav a:hover, nav a.active {
            background: #007bff;
            color: #fff;
        }
        .container {
            width: 97%;
            max-width: 900px;
            margin: 30px auto;
        }
        label[for="functionSelect"] {
            font-size: 1.08em;
            color: #343a40;
        }
        select {
            font-size: 1em;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #b0b0b0;
            margin-left: 8px;
        }
        .section {
            display: none;
            margin-top: 30px;
        }
        form {
            background: #fff;
            padding: 24px 20px 18px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 18px;
        }
        form label {
            display: block;
            margin-bottom: 12px;
            color: #2d3a4b;
        }
        form input[type="text"],
        form input[type="email"] {
            width: 97%;
            padding: 8px 10px;
            margin-top: 4px;
            border: 1px solid #b0b0b0;
            border-radius: 5px;
            font-size: 1em;
            background: #f7faff;
        }
        form button {
            margin-top: 14px;
            padding: 10px 28px;
            background: linear-gradient(90deg, #007bff 60%, #5bc0eb 100%);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.08em;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: background 0.2s;
        }
        form button:hover {
            background: linear-gradient(90deg, #0056b3 60%, #3a8dde 100%);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 14px;
            border: 1px solid #e3e6ea;
            text-align: center;
        }
        th {
            background: linear-gradient(90deg, #007bff 60%, #5bc0eb 100%);
            color: #fff;
            font-size: 1.05em;
        }
        tr:nth-child(even) {
            background: #f4f8fb;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            margin-right: 8px;
            transition: color 0.2s;
        }
        a.delete-link {
            color: #dc3545;
        }
        a:hover {
            text-decoration: underline;
            color: #0056b3;
        }
        @media (max-width: 700px) {
            .container { width: 99%; }
            th, td { font-size: 0.97em; padding: 8px 4px; }
            nav a { margin: 0 8px; font-size: 1em; }
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
        function showUserSection() {
            var select = document.getElementById('userFunctionSelect');
            var addUserSection = document.getElementById('addUserSection');
            var editUserSection = document.getElementById('editUserSection');
            addUserSection.style.display = 'none';
            editUserSection.style.display = 'none';
            if (select.value === 'add') {
                addUserSection.style.display = 'block';
            } else if (select.value === 'edit') {
                editUserSection.style.display = 'block';
            }
        }
        window.onload = function() {
            showSection();
            // 預設不顯示教師功能選單
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
        };
    </script>
</head>
<body>
    <h1>教師後台管理</h1>
    <a href="index.php" style="float:right; margin: 20px 120px 0 0; font-size: 1.1em;">回前台</a>
    <a href="logout.php" style="float:right; margin: 20px 40px 0 0; font-size: 1.1em;">登出</a>
    <div class="container">
        <div style="margin-bottom: 24px;">
            <nav style="background:#fff;padding:12px 0 12px 0;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.07);text-align:center;">
                <a href="#" id="navTeacher" style="margin:0 18px;font-weight:bold;">維護教師資訊</a>
                <a href="#" id="navSchedule" style="margin:0 18px;font-weight:bold;">維護課表</a>
                <a href="#" id="navLogin" style="margin:0 18px;font-weight:bold;">維護登入資訊</a>
            </nav>
        </div>
        <div id="teacherMenu" style="display:none;">
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
                <form id="searchForm" method="post" action="info_search.php" onsubmit="return false;">
                    <input type="text" name="search_keyword" placeholder="請輸入姓名或職稱關鍵字" required>
                    <button type="submit">查詢</button>
                </form>
                <div id="searchResult"></div>
                <script>
                document.getElementById('searchForm').onsubmit = function() {
                    var form = this;
                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.onload = function() {
                        document.getElementById('searchResult').innerHTML = xhr.responseText;
                    };
                    xhr.send(formData);
                };
                </script>
            </div>
        </div>

        <div id="userMenu" style="display:none;">
            <label for="userFunctionSelect"><strong>請選擇功能：</strong></label>
            <select id="userFunctionSelect" onchange="showUserSection()">
                <option value="">-- 請選擇 --</option>
                <option value="add">新增帳號</option>
                <option value="edit">修改帳號</option>
            </select>
            <div id="addUserSection" class="section">
                <h2>新增帳號</h2>
                <form method="post" action="user_manage.php">
                    <input type="text" name="username" placeholder="帳號" required>
                    <input type="password" name="password" placeholder="密碼" required>
                    <button type="submit" name="add_user">新增帳號</button>
                </form>
            </div>
            <div id="editUserSection" class="section">
                <h2>修改帳號</h2>
                <form method="post" action="user_manage.php">
                    <table>
                        <tr><th>帳號</th><th>操作</th></tr>
                        <?php 
                        $users = $mysqli->query("SELECT * FROM users");
                        while($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>
                                <form class="edit-form" method="post" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>" required style="width:90px;">
                                    <input type="password" name="password" placeholder="新密碼(不修改可留空)">
                                    <button type="submit" name="edit_user">修改</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </form>
            </div>
        </div>
        <script>
        document.getElementById('navTeacher').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'block';
            document.getElementById('userMenu').style.display = 'none';
        };
        document.getElementById('navSchedule').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            // 這裡可加上顯示課表維護內容
            alert('尚未實作課表維護功能');
        };
        document.getElementById('navLogin').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'block';
        };
        </script>
    </div>
</body>
</html>