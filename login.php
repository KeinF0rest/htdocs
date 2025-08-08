<?php
session_start();

$_SESSION = [];
session_destroy();
session_start();

$error ='';

$pdo = new PDO("mysql:dbname=account;host=localhost;", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM account WHERE mail = :mail AND delete_flag = 0");
        $stmt->bindParam(':mail', $mail);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'mail' => $user['mail'],
                'authority' => $user['authority']
            ];
            header("Location: index.php");
            exit();
        } else {
            $error = "メールアドレスまたはパスワードが正しくありません。";
        }
    } catch (Exception $e) {
        $error = "エラーが発生したためログイン情報を取得できません。";
    }
}
?>

<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset ="UTF-8">
        <title>ログイン</title>
    </head>
    <body>
        <h2>ログイン画面</h2>
        <?php if ($error): ?>
            <p style="color:red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method ="POST" onsubmit="return validateForm();">
            <label>メールアドレス</label>
            <input type ="text" name ="mail" id ="mail" maxlength ="100"><br>
            <label>パスワード</label>
            <input type ="text" name ="password" id ="password" maxlength ="10"><br>
            <button type="submit">ログイン</button>
        </form>
        <script>
            function validateForm() {
                const mail = document.getElementById('mail').value.trim();
                const password = document.getElementById('password').value.trim();

                if (!mail || !password) {
                    alert("メールアドレスとパスワードを入力してください。");
                    return false;
                }
                return true;
            }
        </script>
    </body>
</html>