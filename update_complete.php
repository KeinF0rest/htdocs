<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['authority'] !== 1) {
    $_SESSION['top_error'] = 'アクセスする権限がありません。';
    header('Location: index.php');
    exit();
}
$Reloaded = false;
if (isset($_SESSION['update_complete'])) {
    if ($_SESSION['update_complete'] === true) {
        $_SESSION['update_complete'] = 'viewed'; // 初回表示後に切り替え
    } elseif ($_SESSION['update_complete'] === 'viewed') {
        $Reloaded = true;
    }
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