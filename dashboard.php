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
        function showEduSection() {
            var select = document.getElementById('eduFunctionSelect');
            var addSection = document.getElementById('addEduSection');
            var deleteSection = document.getElementById('deleteEduSection');
            var searchSection = document.getElementById('searchEduSection');
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
        function showExpSection() {
            var select = document.getElementById('expFunctionSelect');
            var addSection = document.getElementById('addExpSection');
            var deleteSection = document.getElementById('deleteExpSection');
            var searchSection = document.getElementById('searchExpSection');
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
        function showAwardSection() {
            var select = document.getElementById('awardFunctionSelect');
            var addSection = document.getElementById('addAwardSection');
            var deleteSection = document.getElementById('deleteAwardSection');
            var searchSection = document.getElementById('searchAwardSection');
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
        function showProjectSection() {
            var select = document.getElementById('projectFunctionSelect');
            var addSection = document.getElementById('addProjectSection');
            var deleteSection = document.getElementById('deleteProjectSection');
            var searchSection = document.getElementById('searchProjectSection');
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
        function showSpeechSection() {
            var select = document.getElementById('speechFunctionSelect');
            var addSection = document.getElementById('addSpeechSection');
            var deleteSection = document.getElementById('deleteSpeechSection');
            var searchSection = document.getElementById('searchSpeechSection');
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
        function showTeachMatSection() {
            var select = document.getElementById('teachmatFunctionSelect');
            var addSection = document.getElementById('addTeachMatSection');
            var deleteSection = document.getElementById('deleteTeachMatSection');
            var searchSection = document.getElementById('searchTeachMatSection');
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
        function showPatentSection() {
            var select = document.getElementById('patentFunctionSelect');
            var addSection = document.getElementById('addPatentSection');
            var deleteSection = document.getElementById('deletePatentSection');
            var searchSection = document.getElementById('searchPatentSection');
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
            // 預設不顯示教師功能選單
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('eduMenu').style.display = 'none';
            document.getElementById('expMenu').style.display = 'none';
            document.getElementById('awardMenu').style.display = 'none';
            document.getElementById('projectMenu').style.display = 'none';
            document.getElementById('speechMenu').style.display = 'none';
            document.getElementById('teachmatMenu').style.display = 'none';
            document.getElementById('patentMenu').style.display = 'none';
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
                <a href="#" id="navEdu" style="margin:0 18px;font-weight:bold;">維護學歷資料</a>
                <a href="#" id="navExp" style="margin:0 18px;font-weight:bold;">維護經歷資料</a>
                <a href="#" id="navAward" style="margin:0 18px;font-weight:bold;">維護獲獎資料</a>
                <a href="#" id="navProject" style="margin:0 18px;font-weight:bold;">維護計畫資料</a>
                <a href="#" id="navSpeech" style="margin:0 18px;font-weight:bold;">維護演講資料</a>
                <a href="#" id="navTeachMat" style="margin:0 18px;font-weight:bold;">維護教材與作品</a>
                <a href="#" id="navPatent" style="margin:0 18px;font-weight:bold;">維護專利</a>
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

        <div id="eduMenu" style="display:none;">
            <label for="eduFunctionSelect"><strong>請選擇功能：</strong></label>
            <select id="eduFunctionSelect" onchange="showEduSection()">
                <option value="">-- 請選擇 --</option>
                <option value="add">新增學歷</option>
                <option value="delete">修改/刪除學歷</option>
                <option value="search">查詢學歷</option>
            </select>
            <div id="addEduSection" class="section">
                <h2>新增學歷</h2>
                <form action="edu_add.php" method="post">
                    <label>教師編號：<input type="text" name="Prof_ID" required></label>
                    <label>學校：<input type="text" name="EduBG_University" required></label>
                    <label>系所：<input type="text" name="EduBG_Department" required></label>
                    <label>學位：<input type="text" name="EduBG_Degree" required></label>
                    <button type="submit">新增</button>
                </form>
            </div>
            <div id="deleteEduSection" class="section">
                <h2>修改/刪除學歷</h2>
                <table>
                    <tr>
                        <th>學歷ID</th>
                        <th>教師編號</th>
                        <th>學校</th>
                        <th>系所</th>
                        <th>學位</th>
                        <th>操作</th>
                    </tr>
                    <?php
                    $resultEdu = $mysqli->query("SELECT * FROM EducationalBackground");
                    while($row = $resultEdu->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['EduBG_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Prof_ID']) ?></td>
                        <td><?= htmlspecialchars($row['EduBG_University']) ?></td>
                        <td><?= htmlspecialchars($row['EduBG_Department']) ?></td>
                        <td><?= htmlspecialchars($row['EduBG_Degree']) ?></td>
                        <td>
                            <a href="edu_edit.php?id=<?= urlencode($row['EduBG_ID']) ?>">修改</a>
                            <a class="delete-link" href="edu_delete.php?id=<?= urlencode($row['EduBG_ID']) ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <div id="searchEduSection" class="section">
                <h2>查詢學歷</h2>
                <form id="searchEduForm" method="post" action="edu_search.php" onsubmit="return false;">
                    <input type="text" name="search_keyword" placeholder="請輸入學校、系所或學位關鍵字" required>
                    <button type="submit">查詢</button>
                </form>
                <div id="searchEduResult"></div>
                <script>
                document.getElementById('searchEduForm').onsubmit = function() {
                    var form = this;
                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.onload = function() {
                        document.getElementById('searchEduResult').innerHTML = xhr.responseText;
                    };
                    xhr.send(formData);
                };
                </script>
            </div>
        </div>

        <div id="expMenu" style="display:none;">
            <label for="expFunctionSelect"><strong>請選擇功能：</strong></label>
            <select id="expFunctionSelect" onchange="showExpSection()">
                <option value="">-- 請選擇 --</option>
                <option value="add">新增經歷</option>
                <option value="delete">修改/刪除經歷</option>
                <option value="search">查詢經歷</option>
            </select>
            <div id="addExpSection" class="section">
                <h2>新增經歷</h2>
                <form action="exp_add.php" method="post">
                    <label>教師編號：<input type="text" name="Prof_ID" required></label>
                    <label>經歷類型：<input type="text" name="Experience_type" required></label>
                    <label>職稱/職位：<input type="text" name="Experience_position" required></label>
                    <button type="submit">新增</button>
                </form>
            </div>
            <div id="deleteExpSection" class="section">
                <h2>修改/刪除經歷</h2>
                <table>
                    <tr>
                        <th>經歷ID</th>
                        <th>教師編號</th>
                        <th>經歷類型</th>
                        <th>職稱/職位</th>
                        <th>操作</th>
                    </tr>
                    <?php
                    $resultExp = $mysqli->query("SELECT * FROM Experience");
                    while($row = $resultExp->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Experience_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Prof_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Experience_type']) ?></td>
                        <td><?= htmlspecialchars($row['Experience_position']) ?></td>
                        <td>
                            <a href="exp_edit.php?id=<?= urlencode($row['Experience_ID']) ?>">修改</a>
                            <a class="delete-link" href="exp_delete.php?id=<?= urlencode($row['Experience_ID']) ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <div id="searchExpSection" class="section">
                <h2>查詢經歷</h2>
                <form id="searchExpForm" method="post" action="exp_search.php" onsubmit="return false;">
                    <input type="text" name="search_keyword" placeholder="請輸入經歷類型或職稱關鍵字" required>
                    <button type="submit">查詢</button>
                </form>
                <div id="searchExpResult"></div>
                <script>
                document.getElementById('searchExpForm').onsubmit = function() {
                    var form = this;
                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.onload = function() {
                        document.getElementById('searchExpResult').innerHTML = xhr.responseText;
                    };
                    xhr.send(formData);
                };
                </script>
            </div>
        </div>

        <div id="awardMenu" style="display:none;">
            <label for="awardFunctionSelect"><strong>請選擇功能：</strong></label>
            <select id="awardFunctionSelect" onchange="showAwardSection()">
                <option value="">-- 請選擇 --</option>
                <option value="add">新增獎項</option>
                <option value="delete">修改/刪除獎項</option>
                <option value="search">查詢獎項</option>
            </select>
            <div id="addAwardSection" class="section">
                <h2>新增獎項</h2>
                <form action="award_add.php" method="post">
                    <label>教師編號：<input type="text" name="Prof_ID" required></label>
                    <label>學生姓名：<input type="text" name="Award_Advisee" required></label>
                    <label>作品/計畫名稱：<input type="text" name="Award_ProjectName" required></label>
                    <label>競賽名稱與名次：<input type="text" name="Award_CompName_Position" required></label>
                    <label>得獎日期：<input type="date" name="Award_Date" required></label>
                    <label>主辦單位：<input type="text" name="Award_organizer" required></label>
                    <button type="submit">新增</button>
                </form>
            </div>
            <div id="deleteAwardSection" class="section">
                <h2>修改/刪除獎項</h2>
                <table>
                    <tr>
                        <th>獎項ID</th>
                        <th>教師編號</th>
                        <th>學生姓名</th>
                        <th>作品/計畫名稱</th>
                        <th>競賽名稱與名次</th>
                        <th>得獎日期</th>
                        <th>主辦單位</th>
                        <th>操作</th>
                    </tr>
                    <?php
                    $resultAward = $mysqli->query("SELECT * FROM Award");
                    while($row = $resultAward->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Award_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Prof_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Award_Advisee']) ?></td>
                        <td><?= htmlspecialchars($row['Award_ProjectName']) ?></td>
                        <td><?= htmlspecialchars($row['Award_CompName_Position']) ?></td>
                        <td><?= htmlspecialchars($row['Award_Date']) ?></td>
                        <td><?= htmlspecialchars($row['Award_organizer']) ?></td>
                        <td>
                            <a href="award_edit.php?id=<?= urlencode($row['Award_ID']) ?>">修改</a>
                            <a class="delete-link" href="award_delete.php?id=<?= urlencode($row['Award_ID']) ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <div id="searchAwardSection" class="section">
                <h2>查詢獎項</h2>
                <form id="searchAwardForm" method="post" action="award_search.php" onsubmit="return false;">
                    <input type="text" name="search_keyword" placeholder="請輸入學生、作品、競賽或主辦單位關鍵字" required>
                    <button type="submit">查詢</button>
                </form>
                <div id="searchAwardResult"></div>
                <script>
                document.getElementById('searchAwardForm').onsubmit = function() {
                    var form = this;
                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.onload = function() {
                        document.getElementById('searchAwardResult').innerHTML = xhr.responseText;
                    };
                    xhr.send(formData);
                };
                </script>
            </div>
        </div>

        <div id="projectMenu" style="display:none;">
            <label for="projectFunctionSelect"><strong>請選擇功能：</strong></label>
            <select id="projectFunctionSelect" onchange="showProjectSection()">
                <option value="">-- 請選擇 --</option>
                <option value="add">新增計畫</option>
                <option value="delete">修改/刪除計畫</option>
                <option value="search">查詢計畫</option>
            </select>
            <div id="addProjectSection" class="section">
                <h2>新增計畫</h2>
                <form action="project_add.php" method="post">
                    <label>教師編號：<input type="text" name="Prof_ID" required></label>
                    <label>計畫名稱：<input type="text" name="Project_Name" required></label>
                    <label>計畫期間：<input type="text" name="Project_Duration" required></label>
                    <label>計畫類型：
                        <select name="Project_Type" required>
                            <option value="">--請選擇--</option>
                            <option value="國科會">國科會</option>
                            <option value="產學合作">產學合作</option>
                        </select>
                    </label>
                    <label>擔任職務：<input type="text" name="Project_TakenPosition" required></label>
                    <button type="submit">新增</button>
                </form>
            </div>
            <div id="deleteProjectSection" class="section">
                <h2>修改/刪除計畫</h2>
                <table>
                    <tr>
                        <th>計畫ID</th>
                        <th>教師編號</th>
                        <th>計畫名稱</th>
                        <th>計畫期間</th>
                        <th>計畫類型</th>
                        <th>擔任職務</th>
                        <th>操作</th>
                    </tr>
                    <?php
                    $resultProject = $mysqli->query("SELECT * FROM Project");
                    while($row = $resultProject->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Project_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Prof_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Project_Name']) ?></td>
                        <td><?= htmlspecialchars($row['Project_Duration']) ?></td>
                        <td><?= htmlspecialchars($row['Project_Type']) ?></td>
                        <td><?= htmlspecialchars($row['Project_TakenPosition']) ?></td>
                        <td>
                            <a href="project_edit.php?id=<?= urlencode($row['Project_ID']) ?>">修改</a>
                            <a class="delete-link" href="project_delete.php?id=<?= urlencode($row['Project_ID']) ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <div id="searchProjectSection" class="section">
                <h2>查詢計畫</h2>
                <form id="searchProjectForm" method="post" action="project_search.php" onsubmit="return false;">
                    <input type="text" name="search_keyword" placeholder="請輸入計畫名稱、期間、類型或職務關鍵字" required>
                    <button type="submit">查詢</button>
                </form>
                <div id="searchProjectResult"></div>
                <script>
                document.getElementById('searchProjectForm').onsubmit = function() {
                    var form = this;
                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.onload = function() {
                        document.getElementById('searchProjectResult').innerHTML = xhr.responseText;
                    };
                    xhr.send(formData);
                };
                </script>
            </div>
        </div>

        <div id="speechMenu" style="display:none;">
            <label for="speechFunctionSelect"><strong>請選擇功能：</strong></label>
            <select id="speechFunctionSelect" onchange="showSpeechSection()">
                <option value="">-- 請選擇 --</option>
                <option value="add">新增演講</option>
                <option value="delete">修改/刪除演講</option>
                <option value="search">查詢演講</option>
            </select>
            <div id="addSpeechSection" class="section">
                <h2>新增演講</h2>
                <form action="speech_add.php" method="post">
                    <label>教師編號：<input type="text" name="Prof_ID" required></label>
                    <label>演講名稱：<input type="text" name="Speech_Name" required></label>
                    <label>對象/場合：<input type="text" name="Speech_Audience" required></label>
                    <label>日期：<input type="date" name="Speech_Date" required></label>
                    <button type="submit">新增</button>
                </form>
            </div>
            <div id="deleteSpeechSection" class="section">
                <h2>修改/刪除演講</h2>
                <table>
                    <tr>
                        <th>演講ID</th>
                        <th>教師編號</th>
                        <th>演講名稱</th>
                        <th>對象/場合</th>
                        <th>日期</th>
                        <th>操作</th>
                    </tr>
                    <?php
                    $resultSpeech = $mysqli->query("SELECT * FROM Speech");
                    while($row = $resultSpeech->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Speech_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Prof_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Speech_Name']) ?></td>
                        <td><?= htmlspecialchars($row['Speech_Audience']) ?></td>
                        <td><?= htmlspecialchars($row['Speech_Date']) ?></td>
                        <td>
                            <a href="speech_edit.php?id=<?= urlencode($row['Speech_ID']) ?>">修改</a>
                            <a class="delete-link" href="speech_delete.php?id=<?= urlencode($row['Speech_ID']) ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <div id="searchSpeechSection" class="section">
                <h2>查詢演講</h2>
                <form id="searchSpeechForm" method="post" action="speech_search.php" onsubmit="return false;">
                    <input type="text" name="search_keyword" placeholder="請輸入演講名稱或對象關鍵字" required>
                    <button type="submit">查詢</button>
                </form>
                <div id="searchSpeechResult"></div>
                <script>
                document.getElementById('searchSpeechForm').onsubmit = function() {
                    var form = this;
                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.onload = function() {
                        document.getElementById('searchSpeechResult').innerHTML = xhr.responseText;
                    };
                    xhr.send(formData);
                };
                </script>
            </div>
        </div>

        <div id="teachmatMenu" style="display:none;">
            <label for="teachmatFunctionSelect"><strong>請選擇功能：</strong></label>
            <select id="teachmatFunctionSelect" onchange="showTeachMatSection()">
                <option value="">-- 請選擇 --</option>
                <option value="add">新增教材與作品</option>
                <option value="delete">修改/刪除教材與作品</option>
                <option value="search">查詢教材與作品</option>
            </select>
            <div id="addTeachMatSection" class="section">
                <h2>新增教材與作品</h2>
                <form action="teachmat_add.php" method="post">
                    <label>教師編號：<input type="text" name="Prof_ID" required></label>
                    <label>作者：<input type="text" name="TeachMat_Author" required></label>
                    <label>教材/作品名稱：<input type="text" name="TeachMat_Name" required></label>
                    <label>出版社/發表單位：<input type="text" name="TeachMat_Publisher" required></label>
                    <button type="submit">新增</button>
                </form>
            </div>
            <div id="deleteTeachMatSection" class="section">
                <h2>修改/刪除教材與作品</h2>
                <table>
                    <tr>
                        <th>教材ID</th>
                        <th>教師編號</th>
                        <th>作者</th>
                        <th>教材/作品名稱</th>
                        <th>出版社/發表單位</th>
                        <th>操作</th>
                    </tr>
                    <?php
                    $resultTeachMat = $mysqli->query("SELECT * FROM TeachingMaterials");
                    while($row = $resultTeachMat->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['TeachMat_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Prof_ID']) ?></td>
                        <td><?= htmlspecialchars($row['TeachMat_Author']) ?></td>
                        <td><?= htmlspecialchars($row['TeachMat_Name']) ?></td>
                        <td><?= htmlspecialchars($row['TeachMat_Publisher']) ?></td>
                        <td>
                            <a href="teachmat_edit.php?id=<?= urlencode($row['TeachMat_ID']) ?>">修改</a>
                            <a class="delete-link" href="teachmat_delete.php?id=<?= urlencode($row['TeachMat_ID']) ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <div id="searchTeachMatSection" class="section">
                <h2>查詢教材與作品</h2>
                <form id="searchTeachMatForm" method="post" action="teachmat_search.php" onsubmit="return false;">
                    <input type="text" name="search_keyword" placeholder="請輸入作者、名稱或出版社關鍵字" required>
                    <button type="submit">查詢</button>
                </form>
                <div id="searchTeachMatResult"></div>
                <script>
                document.getElementById('searchTeachMatForm').onsubmit = function() {
                    var form = this;
                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.onload = function() {
                        document.getElementById('searchTeachMatResult').innerHTML = xhr.responseText;
                    };
                    xhr.send(formData);
                };
                </script>
            </div>
        </div>

        <div id="patentMenu" style="display:none;">
            <label for="patentFunctionSelect"><strong>請選擇功能：</strong></label>
            <select id="patentFunctionSelect" onchange="showPatentSection()">
                <option value="">-- 請選擇 --</option>
                <option value="add">新增專利</option>
                <option value="delete">修改/刪除專利</option>
                <option value="search">查詢專利</option>
            </select>
            <div id="addPatentSection" class="section">
                <h2>新增專利</h2>
                <form action="patent_add.php" method="post">
                    <label>教師編號：<input type="text" name="Prof_ID" required></label>
                    <label>專利類型：<input type="text" name="Patent_Type" required></label>
                    <label>專利名稱/內容：<input type="text" name="Patent_Term" required></label>
                    <button type="submit">新增</button>
                </form>
            </div>
            <div id="deletePatentSection" class="section">
                <h2>修改/刪除專利</h2>
                <table>
                    <tr>
                        <th>專利ID</th>
                        <th>教師編號</th>
                        <th>專利類型</th>
                        <th>專利名稱/內容</th>
                        <th>操作</th>
                    </tr>
                    <?php
                    $resultPatent = $mysqli->query("SELECT * FROM Patent");
                    while($row = $resultPatent->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Patent_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Prof_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Patent_Type']) ?></td>
                        <td><?= htmlspecialchars($row['Patent_Term']) ?></td>
                        <td>
                            <a href="patent_edit.php?id=<?= urlencode($row['Patent_ID']) ?>">修改</a>
                            <a class="delete-link" href="patent_delete.php?id=<?= urlencode($row['Patent_ID']) ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <div id="searchPatentSection" class="section">
                <h2>查詢專利</h2>
                <form id="searchPatentForm" method="post" action="patent_search.php" onsubmit="return false;">
                    <input type="text" name="search_keyword" placeholder="請輸入專利類型或名稱關鍵字" required>
                    <button type="submit">查詢</button>
                </form>
                <div id="searchPatentResult"></div>
                <script>
                document.getElementById('searchPatentForm').onsubmit = function() {
                    var form = this;
                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.onload = function() {
                        document.getElementById('searchPatentResult').innerHTML = xhr.responseText;
                    };
                    xhr.send(formData);
                };
                </script>
            </div>
        </div>
        <script>
        document.getElementById('navTeacher').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'block';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('eduMenu').style.display = 'none';
            document.getElementById('expMenu').style.display = 'none';
            document.getElementById('awardMenu').style.display = 'none';
            document.getElementById('projectMenu').style.display = 'none';
            document.getElementById('speechMenu').style.display = 'none';
            document.getElementById('teachmatMenu').style.display = 'none';
            document.getElementById('patentMenu').style.display = 'none';
        };
        document.getElementById('navEdu').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('eduMenu').style.display = 'block';
            document.getElementById('expMenu').style.display = 'none';
            document.getElementById('awardMenu').style.display = 'none';
            document.getElementById('projectMenu').style.display = 'none';
            document.getElementById('speechMenu').style.display = 'none';
            document.getElementById('teachmatMenu').style.display = 'none';
            document.getElementById('patentMenu').style.display = 'none';
        };
        document.getElementById('navExp').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('eduMenu').style.display = 'none';
            document.getElementById('expMenu').style.display = 'block';
            document.getElementById('awardMenu').style.display = 'none';
            document.getElementById('projectMenu').style.display = 'none';
            document.getElementById('speechMenu').style.display = 'none';
            document.getElementById('teachmatMenu').style.display = 'none';
            document.getElementById('patentMenu').style.display = 'none';
        };
        document.getElementById('navAward').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('eduMenu').style.display = 'none';
            document.getElementById('expMenu').style.display = 'none';
            document.getElementById('awardMenu').style.display = 'block';
            document.getElementById('projectMenu').style.display = 'none';
            document.getElementById('speechMenu').style.display = 'none';
            document.getElementById('teachmatMenu').style.display = 'none';
            document.getElementById('patentMenu').style.display = 'none';
        };
        document.getElementById('navProject').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('eduMenu').style.display = 'none';
            document.getElementById('expMenu').style.display = 'none';
            document.getElementById('awardMenu').style.display = 'none';
            document.getElementById('projectMenu').style.display = 'block';
            document.getElementById('speechMenu').style.display = 'none';
            document.getElementById('teachmatMenu').style.display = 'none';
            document.getElementById('patentMenu').style.display = 'none';
        };
        document.getElementById('navSpeech').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('eduMenu').style.display = 'none';
            document.getElementById('expMenu').style.display = 'none';
            document.getElementById('awardMenu').style.display = 'none';
            document.getElementById('projectMenu').style.display = 'none';
            document.getElementById('speechMenu').style.display = 'block';
            document.getElementById('teachmatMenu').style.display = 'none';
            document.getElementById('patentMenu').style.display = 'none';
        };
        document.getElementById('navTeachMat').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('eduMenu').style.display = 'none';
            document.getElementById('expMenu').style.display = 'none';
            document.getElementById('awardMenu').style.display = 'none';
            document.getElementById('projectMenu').style.display = 'none';
            document.getElementById('speechMenu').style.display = 'none';
            document.getElementById('teachmatMenu').style.display = 'block';
            document.getElementById('patentMenu').style.display = 'none';
        };
        document.getElementById('navPatent').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('eduMenu').style.display = 'none';
            document.getElementById('expMenu').style.display = 'none';
            document.getElementById('awardMenu').style.display = 'none';
            document.getElementById('projectMenu').style.display = 'none';
            document.getElementById('speechMenu').style.display = 'none';
            document.getElementById('teachmatMenu').style.display = 'none';
            document.getElementById('patentMenu').style.display = 'block';
        };
        document.getElementById('navSchedule').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('eduMenu').style.display = 'none';
            document.getElementById('expMenu').style.display = 'none';
            document.getElementById('awardMenu').style.display = 'none';
            document.getElementById('projectMenu').style.display = 'none';
            alert('尚未實作課表維護功能');
        };
        document.getElementById('navLogin').onclick = function(e) {
            e.preventDefault();
            document.getElementById('teacherMenu').style.display = 'none';
            document.getElementById('userMenu').style.display = 'block';
            document.getElementById('eduMenu').style.display = 'none';
            document.getElementById('expMenu').style.display = 'none';
            document.getElementById('awardMenu').style.display = 'none';
            document.getElementById('projectMenu').style.display = 'none';
            document.getElementById('speechMenu').style.display = 'none';
            document.getElementById('teachmatMenu').style.display = 'none';
            document.getElementById('patentMenu').style.display = 'none';
        };
        </script>
    </div>
</body>
</html>