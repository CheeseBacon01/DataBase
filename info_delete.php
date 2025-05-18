<?php
include('db.php');

$id = $_GET['id'] ?? '';
if ($id) {
    $stmt = $mysqli->prepare("DELETE FROM teachers WHERE Prof_ID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
}
header("Location: index.php");
exit();
?>