<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['authority'] !== 1) {
    $_SESSION['top_error'] = 'アクセスする権限がありません。';
    header('Location: index.php');
    exit();
}

if (empty($_SESSION['update']) && empty($_SESSION['update_data'])) {
    $_SESSION['top_error'] = '不正なアクセスです。';
    header('Location: index.php');
    exit();
}
unset($_SESSION['update']);

$pdo = new PDO("mysql:dbname=account;host=localhost;", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

$stmt = $pdo->prepare("
    SELECT id, family_name, last_name, family_name_kana, last_name_kana, mail, password, gender, postal_code, prefecture, address_1, address_2, authority
    FROM account WHERE id = ?
");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$data = $_SESSION['update_data'] ?? $user;
unset($_SESSION['update_data']);

?>

<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset ="UTF-8">
        <title>アカウント更新画面</title>
    </head>
    <body>
        <h1>アカウント更新画面</h1>
        <form action ="update_confirm.php" method ="POST" id ="updateForm">
            <input type="hidden" name="id" value="<?= htmlspecialchars($data['id'] ?? $user['id']) ?>">
            <div>
                <label>名前（姓）</label>
                <input type="text" name="family_name" maxlength="10" pattern="[\u3040-\u309F\u4E00-\u9FAF]+" value="<?= htmlspecialchars($data["family_name"]) ?>">
                <span class="error" id="error_family_name"></span>
            </div>
            <br>
            
            <div>
                <label>名前（名）</label>
                <input type="text" name="last_name" maxlength="10" pattern="[\u3040-\u309F\u4E00-\u9FAF]+" value="<?= htmlspecialchars($data["last_name"]) ?>">
                <span class="error" id="error_last_name"></span>
            </div>
            <br>
            
            <div>
                <label>カナ（姓）</label>
                <input type="text" name="family_name_kana" maxlength="10" pattern="[\u30A0-\u30FF]+" value="<?= htmlspecialchars($data["family_name_kana"] ?? '') ?>">
                <span class="error" id="error_family_name_kana"></span>
            </div>
            <br>
            
            <div>
                <label>カナ（名）</label>
                <input type="text" name="last_name_kana" maxlength="10" pattern="[\u30A0-\u30FF]+" value="<?= htmlspecialchars($data["last_name_kana"] ?? '') ?>">
                <span class="error" id="error_last_name_kana"></span>
            </div>
            <br>
            
            <div>
                <label>メールアドレス</label>
                <input type="text" name="mail" maxlength="100" pattern="^[a-zA-Z0-9@.\-]+$" value="<?= htmlspecialchars($data["mail"] ?? '') ?>">
                <span class="error" id="error_mail"></span>
            </div>
            <br>
            
            <div>
                <label>パスワード（更新時のみ入力してください）</label>
                <input type="text" name="password" maxlength="10" pattern="[A-Za-z0-9]+" value="">
                <span class="error" id="error_password"></span>
            </div>
            <br>
            
            <div>
                <label>性別</label>
                <input type="radio" name="gender" value="0" <?= ($data["gender"] ?? "0") == "0" ? "checked" : "" ?>>男
                <input type="radio" name="gender" value="1" <?= ($data["gender"] ?? '') == "1" ? "checked" : "" ?>>女
                <span class="error" id="error_gender"></span>
            </div>
            <br>
            
            <div>
                <label>郵便番号</label>
                <input type="text" name="postal_code" maxlength="7" pattern="\d{7}" value="<?= htmlspecialchars($data["postal_code"]) ?>">
                <span class="error" id="error_postal_code"></span>
            </div>
            <br>
            
            <div>
                <?php
                $prefectures = [
                    "北海道", "青森県", "岩手県", "宮城県", "秋田県", "山形県", "福島県",
                    "茨城県", "栃木県", "群馬県", "埼玉県", "千葉県", "東京都", "神奈川県",
                    "新潟県", "富山県", "石川県", "福井県", "山梨県", "長野県", "岐阜県", "静岡県", "愛知県",
                    "三重県", "滋賀県", "京都府", "大阪府", "兵庫県", "奈良県", "和歌山県",
                    "鳥取県", "島根県", "岡山県", "広島県", "山口県", "徳島県", "香川県", "愛媛県", "高知県",
                    "福岡県", "佐賀県", "長崎県", "熊本県", "大分県", "宮崎県", "鹿児島県", "沖縄県"
                ];
                ?>
                <label>住所（都道府県）</label>
                <select name="prefecture">
                <option value="" <?= empty($data["prefecture"]) ? "selected" : "" ?>></option>
                    <?php
                    foreach ($prefectures as $prefecture) {
                        $selected = ($data["prefecture"] ?? '') === $prefecture ? "selected" : "";
                        echo "<option value='" . htmlspecialchars($prefecture) . "' $selected>" . htmlspecialchars($prefecture) . "</option>";
                    }
                    ?>
                </select>
                <span class="error" id="error_prefecture"></span>
            </div>
            <br>
            
            <div>
                <label>住所（市区町村）</label>
                <input type="text" name="address_1" maxlength="10" pattern="[\u3040-\u309F\u4E00-\u9FAF\u30A1-\u30FA\u3000\u30FC0-9]+" value="<?= htmlspecialchars($data["address_1"]) ?>">
                <span class="error" id="error_address_1"></span>
            </div>
            <br>
            
            <div>
                <label>住所（番地）</label>
                <input type="text" name="address_2" maxlength="100" pattern="[\u3040-\u309F\u4E00-\u9FAF\u30A1-\u30FA\u3000\u30FC0-9]+" value="<?= htmlspecialchars($data["address_2"]) ?>">
                <span class="error" id="error_address_2"></span>
            </div>
            <br>
            
            <div>
                <label>アカウント権限</label>
                <select name="authority">
                    <option value="0" <?= ($data["authority"]) == "0" ? "selected" : "" ?>>一般</option>
                    <option value="1" <?= ($data["authority"]) == "1" ? "selected" : "" ?>>管理者</option>
                </select>
            </div>
            <br>
            
            <button type="submit" name="submit">確認する</button>
        </form>
        <script>
            document.getElementById("updateForm").addEventListener("submit", function(event) {
                let errors = [];

                function validateField(fieldName, errorId, errorMessage) {
                    let fieldValue = document.forms["updateForm"][fieldName].value;
                    if (fieldValue === "") {
                        document.getElementById(errorId).innerHTML = `<span style='color: red;'>${errorMessage}</span>`;
                        errors.push(fieldName);
                    } else {
                        document.getElementById(errorId).innerHTML = "";
                    }
                }

                validateField("family_name", "error_family_name", "名前（姓）が未入力です。");
                validateField("last_name", "error_last_name", "名前（名）が未入力です。");
                validateField("family_name_kana", "error_family_name_kana", "カナ（姓）が未入力です。");
                validateField("last_name_kana", "error_last_name_kana", "カナ（名）が未入力です。");
                validateField("mail", "error_mail", "メールアドレスが未入力です。");
                validateField("postal_code", "error_postal_code", "郵便番号が未入力です。");
                validateField("prefecture", "error_prefecture", "都道府県を選択してください。");
                validateField("address_1", "error_address_1", "住所（市区町村）が未入力です。");
                validateField("address_2", "error_address_2", "住所（番地）が未入力です。");

                if (errors.length > 0) {
                    event.preventDefault();
                }
            });
        </script>
        
    </body>
</html>