<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Prof_ID = $_POST['Prof_ID'] ?? '';
    $Prof_Name = $_POST['Prof_Name'] ?? '';
    $Prof_title = $_POST['Prof_title'] ?? '';
    $Prof_EmailAddress = $_POST['Prof_EmailAddress'] ?? '';
    $Prof_ExtensionNumber = $_POST['Prof_ExtensionNumber'] ?? '';

    // Validate input data
    if (empty($Prof_ID) || empty($Prof_Name) || empty($Prof_title) || empty($Prof_EmailAddress)|| empty($Prof_ExtensionNumber)) {
        echo "所有欄位都是必填的！";
        exit();
    }

    // Prepare and execute the SQL INSERT statement
    $stmt = $mysqli->prepare("INSERT INTO teachers (Prof_ID, Prof_Name, Prof_title, Prof_EmailAddress, Prof_ExtensionNumber) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("SQL prepare failed: " . htmlspecialchars($mysqli->error));
    }
    $stmt->bind_param("sssss", $Prof_ID, $Prof_Name, $Prof_title, $Prof_EmailAddress, $Prof_ExtensionNumber);

    if ($stmt->error) {
        die("SQL execute failed: " . htmlspecialchars($stmt->error));
    }

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "新增失敗！";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>新增教師</title>
</head>
<body>
    <h1>新增教師</h1>
    <form method="post">
        <label>姓名：<input type="text" name="Prof_Name" required></label><br>
        <label>職稱：<input type="text" name="Prof_title" required></label><br>
        <label>電子郵件：<input type="email" name="Prof_EmailAddress" required></label><br>
        <label>電話分機：<input type="text" name="Prof_ExtensionNumber" required></label><br>
        <button type="submit">新增</button>
    </form>
</body>
</html>