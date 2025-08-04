<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['authority'] !== 1) {
    $_SESSION['top_error'] = 'アクセスする権限がありません。';
    header('Location: index.php');
    exit();
}
$Reloaded = false;
if (isset($_SESSION['delete_complete'])) {
    if ($_SESSION['delete_complete'] === true) {
        $_SESSION['delete_complete'] = 'viewed';
    } elseif ($_SESSION['delete_complete'] === 'viewed') {
        $Reloaded = true;
    }
}

?>
<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset ="UTF-8">
        <title>アカウント削除画面</title>
        <style>
            body{
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1><?= $Reloaded ? "このアカウントはすでに削除されています" : "削除完了しました" ?></h1>
        <form action ="index.php" method ="get">
            <button type ="submit">TOPページへ戻る</button>
        </form>
        
    </body>
</html>