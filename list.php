<?php
session_start();
try{
    $pdo=new PDO("mysql:dbname=account;host=localhost;","root","", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}catch(PDOException $e){
    echo "<p style='color:red;'>エラーが発生したためアカウント一覧画面が閲覧できません。</p>";
    exit;
}
$users = [];

if ($_GET) {
    $where = [];
    $params = [];

    foreach (['family_name', 'last_name', 'family_name_kana', 'last_name_kana', 'mail'] as $field) {
        if (!empty($_GET[$field])) {
            $where[] = "$field LIKE ?";
            $params[] = "%" . $_GET[$field] . "%";
        }
    }

    if (isset($_GET['gender']) && $_GET['gender'] !== '') {
        $where[] = "gender = ?";
        $params[] = $_GET['gender'];
    }

    if (isset($_GET['authority']) && $_GET['authority'] !== '') {
        $where[] = "authority = ?";
        $params[] = $_GET['authority'];
    }

    $sql = "SELECT id, family_name, last_name, family_name_kana, last_name_kana, mail, gender, authority, delete_flag, registered_time, update_time FROM account";

    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset ="UTF-8">
        <title>アカウント一覧</title>
        <link rel="stylesheet" type="text/css" href="style2.css">
        <style>
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
            button { padding: 5px 10px; cursor: pointer; }
        </style>
        <script>
        function checkAccountStatus(deleteFlag, userId) {
            if (deleteFlag == 1) {
                alert("アカウント情報がありません。");
            } else {
                location.href = 'update.php?id=' + userId;
            }
        }
    </script>
    </head>
    <body>
        <h1>アカウント一覧画面</h1>
        <form method="GET" action="">
            <div class ="search-area">
    <label>名前（姓）：<input type="text" name="family_name" value="<?= htmlspecialchars($_GET['family_name'] ?? '') ?>"></label>
    <label>名前（名）：<input type="text" name="last_name" value="<?= htmlspecialchars($_GET['last_name'] ?? '') ?>"></label>
    <label>カナ（姓）：<input type="text" name="family_name_kana" value="<?= htmlspecialchars($_GET['family_name_kana'] ?? '') ?>"></label>
    <label>カナ（名）：<input type="text" name="last_name_kana" value="<?= htmlspecialchars($_GET['last_name_kana'] ?? '') ?>"></label>
    <label>メールアドレス：<input type="text" name="mail" value="<?= htmlspecialchars($_GET['mail'] ?? '') ?>"></label>
    <label>性別：
        <input type="radio" name="gender" value="0" <?= (isset($_GET['gender']) && $_GET['gender'] === '0') ? 'checked' : '' ?>>男
        <input type="radio" name="gender" value="1" <?= (isset($_GET['gender']) && $_GET['gender'] === '1') ? 'checked' : '' ?>>女
    </label>
    <label>アカウント権限：
        <select name="authority">
            <option value="" <?= (isset($_GET['authority']) && $_GET['authority'] === '') ? 'selected' : '' ?>></option>
            <option value="0" <?= (!isset($_GET['authority']) || $_GET['authority'] === '0') ? 'selected' : '' ?>>一般</option>
            <option value="1" <?= (isset($_GET['authority']) && $_GET['authority'] === '1') ? 'selected' : '' ?>>管理者</option>
        </select>
    </label>
    <button type="submit">検索</button>
    </div>
</form>
        <?php if(!empty($users)): ?>
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
                <td><button onclick="checkAccountStatus(<?= $user['delete_flag'] ?>, <?= $user['id'] ?>)">更新</button></td>
                <td><button onclick="location.href='delete.php?id=<?= $user['id'] ?>'">削除</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <script>
document.addEventListener("DOMContentLoaded", function () {
  const radios = document.querySelectorAll("input[name='gender']");
  let lastChecked = null;

  radios.forEach(radio => {
    radio.addEventListener("mousedown", function () {
      if (this === lastChecked) {
        this.checked = false;
        lastChecked = null;
      } else {
        lastChecked = this;
      }
    });
  });
});
</script>
    </body>

</html>
