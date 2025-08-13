<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
unset($_SESSION['update_complete']);
$authority = $_SESSION['user']['authority'] ?? null;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>D.I.BLOG</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bxslider@4.2.17/dist/jquery.bxslider.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bxslider@4.2.17/dist/jquery.bxslider.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('.abc').bxSlider({
                mode: "fade",
                auto: true,
                speed: 2000,
            });
        });
    </script>
    
</head>
<body>
    <?php
if (isset($_SESSION['top_error'])) {
    echo "<div style='color:red; margin:10px; text-align:center;'>"
        . htmlspecialchars($_SESSION['top_error'], ENT_QUOTES, 'UTF-8')
        . "</div>";
    unset($_SESSION['top_error']);
}
?>
    <img src="diblog_logo.jpg" class="log">
    <header>
        <ul>
            <li>トップ</li>
            <li>プロフィール</li>
            <li>D.I.Blogについて</li>
            <li>登録フォーム</li>
            <li>問い合わせ</li>
            <li>その他</li>
            
            <?php if ($authority == '1'): ?>
            <li><a href="regist.php">アカウント登録</a></li>
            <li><a href="list.php">アカウント一覧</a></li>
            <?php endif; ?>
        </ul>      
    </header>
    <main>
        <div class="main-container">
            <div class="left">
                <h1>プログラミングに役立つ書籍</h1>
                <h3>2017年1月15日</h3>
        
                <div class="abc">
                    <div><img src="jQuery_image1.jpg"></div>
                    <div><img src="jQuery_image2.jpg"></div>
                    <div><img src="jQuery_image3.jpg"></div>
                    <div><img src="jQuery_image4.jpg"></div>
                    <div><img src="jQuery_image5.jpg"></div>
                </div>
         
                <h3>D.I.BlogはD.I.Worksが提供する演習課題です。</h3>
                <h3>記事中身</h3>
            
                <div class="gray_box1">         
                    <div class="box_pic1">
                        <img src="pic1.jpg">
                        <p>ドメイン取得方法</p>
                    </div>
                    <div class="box_pic1">
                        <img src="pic2.jpg">
                        <p>快適な学習環境</p>
                    </div>
                    <div class="box_pic1">
                        <img src="pic3.jpg">
                        <p>Linuxの基礎</p>
                    </div>
                    <div class="box_pic1">
                        <img src="pic4.jpg">
                        <p>マーケティング入門</p>
                    </div>
                </div>
                <div class="gray_box2">
                    <div class="box_pic2">
                        <img src="pic5.jpg">
                        <p>アクティブラーニング</p>
                    </div>
                    <div class="box_pic2">
                        <img src="pic6.jpg">
                        <p>CSSの効率的な方法</p>
                    </div>
                    <div class="box_pic2">
                        <img src="pic7.jpg">
                        <p>リータブルコードとは</p>
                    </div>
                    <div class="box_pic2">
                        <img src="pic8.jpg">
                        <p>HTMLの可能性</p>
                    </div>
                </div>
            </div>
            <div class="right">
                <h3>人気の記事</h3>
                <ul>
                    <li>PHPオススメ本</li>
                    <li>PHP　MｙAdminの使い方</li>
                    <li>いま人気のエディタTops</li>
                    <li>HTMLの基礎</li>
                </ul>
                <h3>オススメリンク</h3>
                <ul>
                    <li>ディーアイワークス株式会社</li>
                    <li>XAMPPのダウンロード</li>
                    <li>Eclipseのダウンロード</li>
                    <li>Braketsのダウンロード</li>
                </ul>
                <h3>カテゴリ</h3>
                <ul>
                    <li>HTML</li>
                    <li>PHP</li>
                    <li>MySQL</li>
                    <li>JavaScript</li>
                </ul>
            </div>
        </div>
    </main>
    <div class="footer">Copyright D.I.works|D.I.blog is hte one which provides A to Z about programming</div>   
</body>
</html>