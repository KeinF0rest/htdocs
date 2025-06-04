<?php
session_start();
try{
    $pdo=new PDO("mysql:dbname=account;host=localhost;","root","", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}catch(PDOException $e){
    echo "<p style='color:red;'>エラーが発生したためアカウント一覧画面が閲覧できません。</p>";
    exit;
}
$stmt = $pdo->query(
"SELECT id, family_name, last_name, family_name_kana, last_name_kana, mail, gender, authority, delete_flag, registered_time, update_time
FROM account ORDER BY id DESC
");
$users = $stmt -> fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset ="UTF-8">
        <title>アカウント一覧</title>
        <style>
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
            button { padding: 5px 10px; cursor: pointer; }
        </style>
    </head>
    <body>
        <h1>アカウント一覧画面</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名前（姓）</th>
                    <th>名前（名）</th>
                    <th>カナ（姓）</th>
                    <th>カナ（名）</th>
                    <th>メールアドレス</th>
                    <th>性別</th>
                    <th>アカウント権限</th>
                    <th>削除フラグ</th>
                    <th>登録日時</th>
                    <th>更新日時</th>
                    <th>更新</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['family_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['last_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['family_name_kana'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['last_name_kana'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['mail'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $user['gender'] == "0" ? "男" : "女" ?></td>
                    <td><?= $user['authority'] == "0" ? "一般" : "管理者" ?></td>
                    <td><?= $user['delete_flag'] == "0" ? "有効" : "無効" ?></td>
                    <td><?= date("Y-m-d", strtotime($user['registered_time'])) ?></td>
                <td><?= date("Y-m-d", strtotime($user['update_time'])) ?></td>
                <td><button onclick="location.href='update.php?id=<?= $user['id'] ?>'">更新</button></td>
                <td><button onclick="location.href='delete.php?id=<?= $user['id'] ?>'">削除</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>

</html>
