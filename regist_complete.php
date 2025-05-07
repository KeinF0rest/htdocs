<?php
mb_internal_encoding("utf8");
session_start();

try{
$pdo=new PDO("mysql:dbname=account;host=localhost;","root","", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO account (family_name, last_name, family_name_kana, last_name_kana, mail, password, gender, postal_code, prefecture, address_1, address_2, authority) 
            VALUES (:family_name, :last_name, :family_name_kana, :last_name_kana, :mail, :password, :gender, :postal_code, :prefecture, :address_1, :address_2, :authority)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":family_name", $_POST['family_name']);
    $stmt->bindParam(":last_name", $_POST['last_name']);
    $stmt->bindParam(":family_name_kana", $_POST['family_name_kana']);
    $stmt->bindParam(":last_name_kana", $_POST['last_name_kana']);
    $stmt->bindParam(":mail", $_POST['mail']);
    $stmt->bindParam(":password", $passwordHash);
    $stmt->bindParam(":gender", $_POST['gender']);
    $stmt->bindParam(":postal_code", $_POST['postal_code']);
    $stmt->bindParam(":prefecture", $_POST['prefecture']);
    $stmt->bindParam(":address_1", $_POST['address_1']);
    $stmt->bindParam(":address_2", $_POST['address_2']);
    $stmt->bindParam(":authority", $_POST['authority']);

    $stmt->execute();
    echo "<h1>登録完了しました</h1>";
    echo '<form action="index.html">';
            echo '<input type="submit" value="TOPページへ戻る">';
    echo '</form>';
} catch(PDOException $e){
    die("データベース接続エラー: " . $e->getMessage());
    echo "<p style='color:red;'>エラーが発生したためアカウント登録できません。</p>";
}
?>