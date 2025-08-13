<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['authority'] !== 1) {
    $_SESSION['top_error'] = 'アクセスする権限がありません。';
    header('Location: index.php');
    exit();
}

if (!isset($_SESSION['update_complete'])|| !in_array($_SESSION['update_complete'], ['ready', 'viewed'])){
    $_SESSION['top_error'] = '不正なアクセスです。';
    header('Location: index.php');
    exit();
}
$Reloaded = false;
if ($_SESSION['update_complete'] === 'ready') {
    $_SESSION['update_complete'] = 'viewed';
} elseif ($_SESSION['update_complete'] === 'viewed') {
    $Reloaded = true;
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
        <h1><?= $Reloaded ? "このアカウントはすでに更新されています" : "更新完了しました" ?></h1>
        <form action ="index.php" method ="get">
            <button type ="submit">TOPページへ戻る</button>
        </form>
        
    </body>
</html>