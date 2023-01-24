<?php 
session_start();

// 初期値：ゲストお問い合わせ画面
$pageFlag = 0;

if (!empty($_REQUEST['btn_guest_submit'])) {
    // btn_guest_submitが押されたらお問い合わせ
    $pageFlag = 2;
}

if (isset($_SESSION['users'])) {
    // セッションデータがあればユーザーお問い合わせ画面へ
    $pageFlag = 1;

    if (!empty($_REQUEST['btn_submit'])) {
        // btn_submitが押されたらお問い合わせ
        $pageFlag = 3;
    }
    
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせ | -？ろごろご？-</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- サイト引用：送信確認 -->
    <script>
        function check() {
            if (confirm('送信しますか？')) {
                return true;

            } else {
                return false;

            }

        }
    </script>
</head>

<body>
<header>
    <h1>-？ろごろご？-</h1>
    <nav id="globalNavi">
        <ul>
            <li><a href="home.php">HOME</a></li>
            <!-- <li><a href="note.php">なにか</a></li> -->
            <!-- <li><a href="idk.php">どれか</a></li> -->
            <li class="current"><a href="contact.php">お問い合わせ</a></li>
        </ul>
        <ul>
            <li><a href="new_reg.php">新規登録</a></li>
            <li><a href="account.php">アカウント</a></li>
        </ul>
    </nav>

</header>

<div id="wrapper">
    <div id="contact">
        <!-- ゲストお問い合わせ画面 -->
        <?php if ($pageFlag === 0) { ?>

            <h1>お問い合わせ</h1>
            <form action="" method="post" onsubmit="return check()">
                <table>
                    <tr>
                        <th>ユーザー名</th>
                        <td>ゲスト</td>
                    </tr>
                    <tr>
                        <th>メールアドレス</th>
                        <td><input type="email" name="email" autocomplete="on" placeholder="例）Logologo00@xxx.com" required></td>
                    </tr>
                    <tr>
                        <th>年齢（任意）</th>
                        <td>
                            <select name="age">
                                <option value="回答しない">回答しない</option>
                                <option value="10代">10~19歳</option>
                                <option value="20代">20~29歳</option>
                                <option value="30代">30~39歳</option>
                                <option value="40代">40~49歳</option>
                                <option value="50代">50~59歳</option>
                                <option value="60代">60~69歳</option>
                                <option value="70代">70~79歳</option>
                                <option value="80代">80~89歳</option>
                                <option value="90歳以上">90歳以上</option>
                                <option value="その他">その他</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>性別（任意）</th>
                        <td>
                            <input type="radio" name="sex" value="回答しない" checked> 回答しない&emsp;
                            <input type="radio" name="sex" value="男"> 男&emsp;
                            <input type="radio" name="sex" value="女"> 女&emsp;
                            <input type="radio" name="sex" value="その他"> その他&emsp;
                        </td>
                    </tr>
                    <tr>
                        <th>お問い合わせ内容</th>
                        <td>
                            <input type="radio" name="class" value="不具合" required> 不具合&emsp;
                            <input type="radio" name="class" value="ご意見" required> ご意見&emsp;
                            <input type="radio" name="class" value="その他" required> その他&emsp;
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><textarea name="contents" cols="50" rows="10" required style="font-size: 15px;"></textarea></td>
                    </tr>
                </table>
                <button name="btn_guest_submit" value="2">送信</button>
            </form>


        <!-- お問い合わせ画面 -->
        <?php } elseif ($pageFlag === 1) { ?>

            <h1>お問い合わせ</h1>
            <form action="" method="post" onsubmit="return check()">
                <?php
                echo '<input type="hidden" name="id" value="', $_SESSION['users']['id'], '">';
                ?>
                <table>
                    <tr>
                        <th>ユーザー名</th>
                        <?php
                        echo '<td>', $_SESSION['users']['name'], '</td>';
                        ?>
                    </tr>
                    <tr>
                        <th>メールアドレス</th>
                        <?php
                        echo '<td>', $_SESSION['users']['email'], '</td>';
                        ?>
                    </tr>
                    <tr>
                        <th>お問い合わせ内容</th>
                        <td>
                            <input type="radio" name="class" value="不具合" required> 不具合&emsp;
                            <input type="radio" name="class" value="ご意見" required> ご意見&emsp;
                            <input type="radio" name="class" value="その他" required> その他&emsp;
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><textarea name="contents" cols="50" rows="10" required style="font-size: 15px;"></textarea></td>
                    </tr>
                </table>
                <button name="btn_submit" value="3">送信</button>
            </form>


        <?php 
        // ゲストお問い合わせ
        } elseif ($pageFlag === 2) {
            if (isset($_REQUEST['contents'])) {
                echo '<p>送信しました。</p>';
                echo '<form action="" method="post"><button>戻る</button></form>';

                // 現在の日付と時刻を取得
                $dateTime = new DateTime('now', new DateTimeZone('JST'));
                $nowDateTime = $dateTime->format('Y/m/d H:i:s');

                // DBに内容登録
                $pdo = new PDO('mysql:host=localhost;dbname=kouka2249580re;charset=utf8', 'root', '');
                $sql = $pdo->prepare('INSERT INTO contact_guest VALUES(null, ?, ?, ?, ?, ?, ?)');
                $sql->execute([$_REQUEST['email'], $_REQUEST['age'], $_REQUEST['sex'], 
                $_REQUEST['class'], $_REQUEST['contents'], $nowDateTime]);

            } else {
                echo '<p>※送信できませんでした。お手数ですが再度入力してください。</p>';
                echo '<p><a href="contact.php">お問い合わせ画面へ</a></p>';
    
            }


        // お問い合わせ
        } elseif ($pageFlag === 3) {
            if (isset($_REQUEST['contents'])) {
                echo '<p>送信しました。</p>';
                echo '<form action="" method="post"><button>戻る</button></form>';

                // 現在の日付と時刻を取得
                $dateTime = new DateTime('now', new DateTimeZone('JST'));
                $nowDateTime = $dateTime->format('Y/m/d H:i:s');

                // DBに内容登録
                $pdo = new PDO('mysql:host=localhost;dbname=kouka2249580re;charset=utf8', 'root', '');
                $sql = $pdo->prepare('INSERT INTO contact VALUES(null, ?, ?, ?, ?)');
                $sql->execute([$_REQUEST['id'], $_REQUEST['class'], $_REQUEST['contents'], $nowDateTime]);

            } else {
                echo '<p>※送信できませんでした。お手数ですが再度入力してください。</p>';
                echo '<p><a href="contact.php">お問い合わせ画面へ</a></p>';

            }

        }
        ?>

    </div> 
</div>

<?php require 'h&f/footer1.php'; ?>
