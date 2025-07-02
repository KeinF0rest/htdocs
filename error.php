<?php
session_start();
$error = $_SESSION['error'] ?? '不正なアクセスです。';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>エラー</title>
</head>
<body>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <button onclick="location.href='index.php'">トップに戻る</button>
</body>
</html>