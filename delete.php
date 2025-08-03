<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['authority'] !== 1) {
    $_SESSION['error'] = 'このページへアクセスする権限がありません。';
    header('Location: error.php');
    exit();
}

try{
    $pdo=new PDO("mysql:dbname=account;host=localhost;","root","", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}catch(PDOException $e){
    echo "<p style='color:red;'>エラーが発生したためアカウント削除ができません。</p>";
    exit;
}
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id) {
    $_SESSION['error'] = '無効なIDが指定されています。';
    header('Location: error.php');
    exit();
}

$stmt = $pdo->prepare("SELECT delete_flag FROM account WHERE id = ?");
$stmt->execute([$id]);
$deleteFlag = $stmt->fetchColumn();

if ($deleteFlag === false) {
    $_SESSION['error'] = '指定されたアカウントは存在しません。';
    header('Location: error.php');
    exit();
}

if ($deleteFlag == 1) {
    $_SESSION['error'] = 'このアカウントはすでに削除済みです。';
    header('Location: error.php');
    exit();
}

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
        <p>名前（名）　<?= htmlspecialchars($user['last_name'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>カナ（姓）　<?= htmlspecialchars($user['family_name_kana'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>カナ（名）　<?= htmlspecialchars($user['last_name_kana'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>メールアドレス　<?= htmlspecialchars($user['mail'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>安全上パスワードは伏せます</p>
        <p>性別　<?= $user['gender'] == "0" ? "男" : "女" ?></p>
        <p>郵便番号　<?= htmlspecialchars($user['postal_code'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>住所（都道府県）　<?= htmlspecialchars($user['prefecture'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>住所（市区町村）　<?= htmlspecialchars($user['address_1'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>住所（番地）　<?= htmlspecialchars($user['address_2'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>アカウント権限　<?= $user['authority'] == "0" ? "一般" : "管理者" ?></p>
    </body>
    <form action="delete_confirm.php" method="get">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <button type="submit">確認する</button>
    </form>
</html>