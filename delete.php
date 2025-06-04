<?php
session_start();
try{
    $pdo=new PDO("mysql:dbname=account;host=localhost;","root","", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}catch(PDOException $e){
    echo "<p style='color:red;'>エラーが発生したためアカウント削除ができません。</p>";
    exit;
}
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

$stmt = $pdo->prepare("
    SELECT id, family_name, last_name, family_name_kana, last_name_kana, mail, password, gender, postal_code, prefecture, address_1, address_2, authority
    FROM account WHERE id = ?
");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset ="UTF-8">
        <title>アカウント削除画面</title>
    </head>
    <body>
        <h1>アカウント削除画面</h1>
        <p>名前（姓）　<?= htmlspecialchars($user['family_name'], ENT_QUOTES, 'UTF-8') ?></p>
    </body>


</html>