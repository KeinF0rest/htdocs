<?php
session_start();
try {
    $pdo = new PDO("mysql:dbname=account;host=localhost;", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    echo "<p style='color:red;'>エラーが発生したためアカウント更新できません。</p>";
    exit;
}

$_SESSION['update_data'] = $_POST;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    try {
        $id = intval($_POST['id'] ?? 0);
        
        $password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : null;

        $sql = "
            UPDATE account SET 
                family_name = ?, 
                last_name = ?,
                family_name_kana = ?,
                last_name_kana = ?,
                mail = ?, 
                gender = ?, 
                postal_code = ?, 
                prefecture = ?, 
                address_1 = ?, 
                address_2 = ?, 
                authority = ?
                " . ($password ? ", password = ?" : "") . "
            WHERE id = ?
        ";

        $stmt = $pdo->prepare($sql);
        $params = [
            $_POST["family_name"], $_POST["last_name"], $_POST["family_name_kana"], $_POST["last_name_kana"], $_POST["mail"],
            $_POST["gender"], $_POST["postal_code"], $_POST["prefecture"],
            $_POST["address_1"], $_POST["address_2"], $_POST["authority"],
        ];
        if ($password) $params[] = $password;
        $params[] = $id;

        $stmt->execute($params);

        header("Location: update_complete.php");
        exit;

    } catch (PDOException $e) {
        echo "<p style='color:red;'>エラーが発生したためアカウント更新できません。</p>";
    }
}
?>

<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset="UTF-8">
        <title>アカウント更新確認</title>
    </head>
    <body>
        <h1>アカウント更新確認画面</h1>
        <div class="">
            <p>名前（姓）　<?= htmlspecialchars($_POST['family_name']) ?>
            </p>
            
            <p>名前（名） <?= htmlspecialchars($_POST['last_name']) ?></p>
            
            <p>カナ（姓） <?= htmlspecialchars($_POST['family_name_kana']) ?></p>
            
            <p>カナ（名） <?= htmlspecialchars($_POST['last_name_kana']) ?></p>
            
            <p>メールアドレス <?= htmlspecialchars($_POST['mail']) ?></p>
            
            <p>パスワード 
                <?php 
                    if(empty($_POST['password'])) {
                        echo "パスワードは変更されませんでした。";
                    } else {
                        echo str_repeat('●', strlen($_POST['password']));
                    }
                ?>
    
            </p>
            
            <p>性別 <?= $_POST['gender']=="0" ? "男" : "女" ?></p>
            
            <p>郵便番号 <?= htmlspecialchars($_POST['postal_code']) ?></p>
            
            <p>住所（都道府県） <?= htmlspecialchars($_POST['prefecture']) ?></p>
            
            <p>住所（市区町村） <?= htmlspecialchars($_POST['address_1']) ?></p>
            
            <p>住所（番地） <?= htmlspecialchars($_POST['address_2']) ?></p>
            
            <p>アカウント権限 <?= $_POST['authority']=="0" ? "一般" : "管理者" ?></p>
            
            <form method="POST" name ="update">
                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                <?php foreach ($_POST as $key => $value) { ?>
                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                <?php } ?>
                <button type="submit" name="update">更新する</button>
            </form>
            
            <form action="update.php" method="GET">
                <button type="submit">前に戻る</button>
            </form>
        </div>
    </body>
    
</html>