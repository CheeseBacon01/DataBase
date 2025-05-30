<?php
// professor.php
// 顯示單一教授詳細頁面
include('db.php');
$Prof_ID = $_GET['id'] ?? '';
if (!$Prof_ID) {
    echo '未指定教授';
    exit();
}
$stmt = $mysqli->prepare("SELECT * FROM teachers WHERE Prof_ID = ?");
$stmt->bind_param("s", $Prof_ID);
$stmt->execute();
$prof = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$prof) {
    echo '找不到該教授';
    exit();
}
// 取得學歷資料
$stmt2 = $mysqli->prepare("SELECT * FROM EducationalBackground WHERE Prof_ID = ?");
$stmt2->bind_param("s", $Prof_ID);
$stmt2->execute();
$edus = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt2->close();
// 取得經歷資料，分校內/校外
$stmt3 = $mysqli->prepare("SELECT * FROM Experience WHERE Prof_ID = ?");
$stmt3->bind_param("s", $Prof_ID);
$stmt3->execute();
$exps = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt3->close();
$exp_in = [];
$exp_out = [];
foreach ($exps as $exp) {
    if (strpos($exp['Experience_type'], '校內') !== false) {
        $exp_in[] = $exp;
    } else {
        $exp_out[] = $exp;
    }
}
// 取得獲獎資料
$stmt4 = $mysqli->prepare("SELECT * FROM Award WHERE Prof_ID = ? ORDER BY Award_Date DESC");
$stmt4->bind_param("s", $Prof_ID);
$stmt4->execute();
$awards = $stmt4->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt4->close();
// 取得計畫資料，分國科會/產學合作
$stmt5 = $mysqli->prepare("SELECT * FROM Project WHERE Prof_ID = ?");
$stmt5->bind_param("s", $Prof_ID);
$stmt5->execute();
$projects = $stmt5->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt5->close();
$proj_nstc = [];
$proj_industry = [];
foreach ($projects as $proj) {
    if ($proj['Project_Type'] === '國科會') {
        $proj_nstc[] = $proj;
    } else if ($proj['Project_Type'] === '產學合作') {
        $proj_industry[] = $proj;
    }
}
// 取得演講資料
$stmt6 = $mysqli->prepare("SELECT * FROM Speech WHERE Prof_ID = ? ORDER BY Speech_Date DESC");
$stmt6->bind_param("s", $Prof_ID);
$stmt6->execute();
$speeches = $stmt6->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt6->close();
// 取得教材與作品資料
$stmt7 = $mysqli->prepare("SELECT * FROM TeachingMaterials WHERE Prof_ID = ?");
$stmt7->bind_param("s", $Prof_ID);
$stmt7->execute();
$teachmats = $stmt7->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt7->close();
// 取得專利資料
$stmt8 = $mysqli->prepare("SELECT * FROM Patent WHERE Prof_ID = ?");
$stmt8->bind_param("s", $Prof_ID);
$stmt8->execute();
$patents = $stmt8->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt8->close();
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($prof['Prof_Name']) ?> - 教授資訊</title>
    <style>
        body { font-family: "Microsoft JhengHei", Arial, sans-serif; background: #e0eafc; margin:0; }
        .container { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.10); padding: 36px; }
        h1 { color: #007bff; margin-bottom: 18px; }
        .info { margin-bottom: 18px; }
        .label { color: #888; margin-right: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #e3e6ea; padding: 10px; text-align: center; }
        th { background: #007bff; color: #fff; }
        tr:nth-child(even) { background: #f4f8fb; }
        a { color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php">← 回教授列表</a>
        <h1><?= htmlspecialchars($prof['Prof_Name']) ?></h1>
        <div class="info"><span class="label">職稱：</span><?= htmlspecialchars($prof['Prof_title']) ?></div>
        <div class="info"><span class="label">電子郵件：</span><?= htmlspecialchars($prof['Prof_EmailAddress']) ?></div>
        <div class="info"><span class="label">電話分機：</span><?= htmlspecialchars($prof['Prof_ExtensionNumber']) ?></div>
        <h2>學歷</h2>
        <?php if (count($edus) > 0): ?>
        <table>
            <tr><th>學校</th><th>系所</th><th>學位</th></tr>
            <?php foreach($edus as $edu): ?>
            <tr>
                <td><?= htmlspecialchars($edu['EduBG_University']) ?></td>
                <td><?= htmlspecialchars($edu['EduBG_Department']) ?></td>
                <td><?= htmlspecialchars($edu['EduBG_Degree']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <div>尚無學歷資料</div>
        <?php endif; ?>
        <h2>經歷</h2>
        <?php if (count($exp_in) > 0): ?>
            <h3>校內經歷</h3>
            <table>
                <tr><th>經歷類型</th><th>職稱/職位</th></tr>
                <?php foreach($exp_in as $exp): ?>
                <tr>
                    <td><?= htmlspecialchars($exp['Experience_type']) ?></td>
                    <td><?= htmlspecialchars($exp['Experience_position']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <?php if (count($exp_out) > 0): ?>
            <h3>校外經歷</h3>
            <table>
                <tr><th>經歷類型</th><th>職稱/職位</th></tr>
                <?php foreach($exp_out as $exp): ?>
                <tr>
                    <td><?= htmlspecialchars($exp['Experience_type']) ?></td>
                    <td><?= htmlspecialchars($exp['Experience_position']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <?php if (count($exp_in) === 0 && count($exp_out) === 0): ?>
            <div>尚無經歷資料</div>
        <?php endif; ?>
        <h2>指導學生獲獎</h2>
        <?php if (count($awards) > 0): ?>
        <table>
            <tr>
                <th>學生姓名</th>
                <th>作品/計畫名稱</th>
                <th>競賽名稱與名次</th>
                <th>得獎日期</th>
                <th>主辦單位</th>
            </tr>
            <?php foreach($awards as $award): ?>
            <tr>
                <td><?= htmlspecialchars($award['Award_Advisee']) ?></td>
                <td><?= htmlspecialchars($award['Award_ProjectName']) ?></td>
                <td><?= htmlspecialchars($award['Award_CompName_Position']) ?></td>
                <td><?= htmlspecialchars($award['Award_Date']) ?></td>
                <td><?= htmlspecialchars($award['Award_organizer']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <div>尚無獲獎資料</div>
        <?php endif; ?>
        <h2>計畫</h2>
        <?php if (count($proj_nstc) > 0): ?>
            <h3>國科會計畫</h3>
            <table>
                <tr><th>計畫名稱</th><th>期間</th><th>擔任職務</th></tr>
                <?php foreach($proj_nstc as $proj): ?>
                <tr>
                    <td><?= htmlspecialchars($proj['Project_Name']) ?></td>
                    <td><?= htmlspecialchars($proj['Project_Duration']) ?></td>
                    <td><?= htmlspecialchars($proj['Project_TakenPosition']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <?php if (count($proj_industry) > 0): ?>
            <h3>產學合作計畫</h3>
            <table>
                <tr><th>計畫名稱</th><th>期間</th><th>擔任職務</th></tr>
                <?php foreach($proj_industry as $proj): ?>
                <tr>
                    <td><?= htmlspecialchars($proj['Project_Name']) ?></td>
                    <td><?= htmlspecialchars($proj['Project_Duration']) ?></td>
                    <td><?= htmlspecialchars($proj['Project_TakenPosition']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <?php if (count($proj_nstc) === 0 && count($proj_industry) === 0): ?>
            <div>尚無計畫資料</div>
        <?php endif; ?>
        <h2>演講</h2>
        <?php if (count($speeches) > 0): ?>
        <table>
            <tr>
                <th>演講名稱</th>
                <th>對象/場合</th>
                <th>日期</th>
            </tr>
            <?php foreach($speeches as $speech): ?>
            <tr>
                <td><?= htmlspecialchars($speech['Speech_Name']) ?></td>
                <td><?= htmlspecialchars($speech['Speech_Audience']) ?></td>
                <td><?= htmlspecialchars($speech['Speech_Date']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <div>尚無演講資料</div>
        <?php endif; ?>
        <h2>教材與作品</h2>
        <?php if (count($teachmats) > 0): ?>
        <table>
            <tr>
                <th>作者</th>
                <th>教材/作品名稱</th>
                <th>出版社/發表單位</th>
            </tr>
            <?php foreach($teachmats as $tm): ?>
            <tr>
                <td><?= htmlspecialchars($tm['TeachMat_Author']) ?></td>
                <td><?= htmlspecialchars($tm['TeachMat_Name']) ?></td>
                <td><?= htmlspecialchars($tm['TeachMat_Publisher']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <div>尚無教材與作品資料</div>
        <?php endif; ?>
        <h2>核准專利</h2>
        <?php if (count($patents) > 0): ?>
        <table>
            <tr>
                <th>專利類型</th>
                <th>專利名稱/內容</th>
            </tr>
            <?php foreach($patents as $pat): ?>
            <tr>
                <td><?= htmlspecialchars($pat['Patent_Type']) ?></td>
                <td><?= htmlspecialchars($pat['Patent_Term']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <div>尚無專利資料</div>
        <?php endif; ?>
    </div>
</body>
</html>
