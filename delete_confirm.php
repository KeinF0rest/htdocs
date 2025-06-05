<?php
session_start();
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
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
    </body>
</html>