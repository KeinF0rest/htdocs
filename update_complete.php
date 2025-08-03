<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['authority'] !== 1) {
    $_SESSION['top_error'] = 'アクセスする権限がありません。';
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset ="UTF-8">
        <title>アカウント更新完了画面</title>
        <style>
            body{
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1>更新完了しました</h1>
        <form action ="index.php" method ="get">
            <button type ="submit">TOPページへ戻る</button>
        </form>
        
    </body>
</html>