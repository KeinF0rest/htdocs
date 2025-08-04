<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['authority'] !== 1) {
    $_SESSION['top_error'] = 'アクセスする権限がありません。';
    header('Location: index.php');
    exit();
}

try {
    $pdo = new PDO("mysql:dbname=account;host=localhost;", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    echo "<p style='color:red;'>エラーが発生したためアカウント削除できません。</p>";
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : null);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("UPDATE account SET delete_flag = 1 WHERE id = ?");
    $result = $stmt->execute([$id]);
    if ($result) {
        $_SESSION['delete_complete'] = true;
        header("Location: delete_complete.php");
        exit;
    } else {
        echo "<p style='color:red;'>エラーが発生したためアカウント削除できません。</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>アカウント削除確認画面</title>
    </head>
    <body>
        <h1>本当に削除してよろしいですか？</h1>
        <form action ="delete.php" method="get">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="submit">前に戻る</button>
        </form>
        <form action="delete_confirm.php" method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="submit">削除する</button>
        </form>
    </body>
</html>