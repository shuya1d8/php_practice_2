<?php
session_start();

// 初期値：新規登録画面
$pageFlag = 0;

if (!empty($_REQUEST['btn_confirm'])) {
    // btn_confirmが押されたら確認画面へ
    $pageFlag = 1;
    
} elseif (!empty($_REQUEST['btn_submit'])) {
    // btn_submitが押されたら完了画面へ
    $pageFlag = 2;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録 | -？ろごろご？-</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- サイト引用：ボタンの活性・非活性の切り替え -->
    <script>
        function change() {
            var element;
            if (document.getElementById('agree').checked) {
                // チェックが入っていたら、ボタンのdisabledを外す
                element = document.getElementById('submit');
                element.disabled = false;
            } else {
                // チェックが外れていたら、ボタンにdisabledを付ける
                element = document.getElementById('submit');
                element.disabled = true;
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
            <li><a href="contact.php">お問い合わせ</a></li>
        </ul>
        <ul>
            <li class="current"><a href="new_reg.php">新規登録</a></li>
            <li><a href="account.php">アカウント</a></li>
        </ul>
    </nav>

</header>

<div id="wrapper">
    <div id="newReg">
        <!-- 新規登録画面 -->
        <?php if ($pageFlag === 0) { ?>

            <div id="newRegIn">
                <h1><span>新規登録</span>画面</h1>
                <form action="" method="post">
                    <table>
                        <tr>
                            <th><label for="name">ユ ー ザ ー 名</label></th>
                            <td>
                                <input type="text" name="name" id="name" autocomplete="on" placeholder="例）ろごろご" 
                                value="<?php if(!empty($_REQUEST['name'])){ echo $_REQUEST['name']; } ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="email">メールアドレス</label></th>
                            <td>
                                <input type="email" name="email" id="email" autocomplete="on" placeholder="例）Logologo00@xxx.com" 
                                value="<?php if(!empty($_REQUEST['email'])){ echo $_REQUEST['email']; } ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="login_id">ロ&nbsp;グ&nbsp;イ&nbsp;ン&nbsp;ID</label>
                                <br>
                                <small>※半角英数字のみ使用可能<br>※登録したら変更できません</small>
                            </th>
                            <td>
                                <input type="text" name="login_id" id="login_id" autocomplete="on" 
                                value="<?php if(!empty($_REQUEST['login_id'])){ echo $_REQUEST['login_id']; } ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="login_pass">パ ス ワ ー ド</label>
                                <br>
                                <small>※半角英数字、<br>大文字小文字数字を<br>各1文字以上</small>
                            </th>
                            <td>
                                <input type="password" name="login_pass" id="login_pass" 
                                value="<?php if(!empty($_REQUEST['login_pass'])){ echo $_REQUEST['login_pass']; } ?>" required>
                            </td>
                        </tr>
                    </table>
                    <button name="btn_confirm" value="1">確認画面へ</button>
                </form>

            </div>


        <!-- 新規登録確認画面 -->
        <?php } elseif ($pageFlag === 1) { ?>

            <div id="newRegCheck">
                <h1><span>新規登録</span>確認画面</h1>

                <?php
                // DBに接続
                $pdo = new PDO('mysql:host=localhost;dbname=kouka2249580re;charset=utf8', 'root', '');

                // ログインIDの重複確認
                // 重複検索
                $sql=$pdo->prepare('select * from users where login=?');
                $sql->execute([$_REQUEST['login_id']]);
                ?>
                
                <!-- 重複検索結果の取得 -->
                <?php if (empty($sql->fetchAll())) { ?>
                    <?php 
                    // 入力値が適切か判定
                    if (preg_match('/^[a-zA-Z0-9]+$/', $_REQUEST['login_id']) 
                    && preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]+$/', $_REQUEST['login_pass']) 
                    && filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {

                        // 入力値の英数字,スペースの前半角変換
                        // $name = mb_convert_kana($_REQUEST['name'], 'a, s');
                        $email = mb_convert_kana($_REQUEST['email'], 'a');

                        // 入力値の無害化
                        $name = htmlspecialchars($_REQUEST['name'], ENT_QUOTES, 'UTF-8');

                        $login_id = $_REQUEST['login_id'];
                        $login_pass = $_REQUEST['login_pass'];
                        $email = $_REQUEST['email'];


                        // チェック画面
                        echo '<form action="" method="post">
                            <input type="hidden" name="name" value="', $name, '">
                            <input type="hidden" name="email" value="', $email, '">
                            <input type="hidden" name="login_id" value="', $login_id, '">
                            <input type="hidden" name="login_pass" value="', $login_pass, '">

                            <table>
                                <tr>
                                    <th>ユーザー名</th>
                                    <td>', $name, '</td>  
                                </tr>
                                <tr>
                                    <th>メールアドレス</th>
                                    <td>', $email, '</td>
                                </tr>
                                <tr>
                                    <th>ログインID</th>
                                    <td>', $login_id, '</td>
                                </tr>
                                <tr>
                                    <th>パスワード</th>
                                    <td>', $login_pass, '</td>
                                </tr>
                            </table>

                            <div class="agree">
                                <input type="checkbox" name="agree" id="agree" onchange="change()">
                                <label for="agree">登録を確定しますか？</label>
                            </div>

                            <button>戻る</button>
                            <button name="btn_submit" value="2" id="submit" disabled>登録する</button>
                        </form>';

                    } else { 
                    ?>

                        <!-- 入力ミス -->
                        <div class="error">
                            <?php if (!preg_match('/^[a-zA-Z0-9]+$/', $_REQUEST['login_id']) 
                            && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]+$/', $_REQUEST['login_pass'])) { ?>
                                <p>※ログインIDが正しくありません。(半角英数字のみ)</p>
                                <br>
                                <p>※パスワードが正しくありません。(半角英数字、大文字小文字数字を各1文字以上)</p>
                                <br>
                                <form action="" method="post">
                                    <input type="hidden" name="name" value="<?php echo $_REQUEST['name']; ?>">
                                    <input type="hidden" name="email" value="<?php echo $_REQUEST['email']; ?>">
                                    <button>登録画面に戻る</button>
                                </form>
        
                            <?php } elseif (preg_match('/^[a-zA-Z0-9]+$/', $_REQUEST['login_id'])) { ?>
                                <p>※パスワードが正しくありません。(半角英数字、大文字小文字数字を各1文字以上)</p>
                                <br>
                                <form action="" method="post">
                                    <input type="hidden" name="name" value="<?php echo $_REQUEST['name']; ?>">
                                    <input type="hidden" name="email" value="<?php echo $_REQUEST['email']; ?>">
                                    <input type="hidden" name="login_id" value="<?php echo $_REQUEST['login_id']; ?>">
                                    <button>登録画面に戻る</button>
                                </form>
        
                            <?php } elseif (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]+$/', $_REQUEST['login_pass'])) { ?>
                                <p>※ログインIDが正しくありません。(半角英数字のみ)</p>
                                <br>
                                <form action="" method="post">
                                    <input type="hidden" name="name" value="<?php echo $_REQUEST['name']; ?>">
                                    <input type="hidden" name="email" value="<?php echo $_REQUEST['email']; ?>">
                                    <input type="hidden" name="login_pass" value="<?php echo $_REQUEST['login_pass']; ?>">
                                    <button>登録画面に戻る</button>
                                </form>
        
                            <?php } else { ?>
                                <p>※お手数をおかけしますがもう一度入力してください。</p>
                                <form action="" method="post">
                                    <button>登録画面に戻る</button>
                                </form>
        
                            <?php } ?>
        
                        </div>

                    <?php } ?>
                
                <?php } else { ?>
                    <div class="error">
                        <p>※このログインIDはすでに使用されています。</p>
                        <br>
                        <form action="" method="post">
                            <input type="hidden" name="name" value="<?php echo $_REQUEST['name']; ?>">
                            <input type="hidden" name="email" value="<?php echo $_REQUEST['email']; ?>">
                            <input type="hidden" name="login_pass" value="<?php echo $_REQUEST['login_pass']; ?>">
                            <button name="btn_return" value="0">登録画面に戻る</button>
                        </form>

                    </div>

                <?php } ?>
                
            </div>


        <!-- 新規登録 -->
        <?php } elseif ($pageFlag === 2) { ?>

            <div id="newRegOut">
                <?php
                $pdo = new PDO('mysql:host=localhost;dbname=kouka2249580re;charset=utf8', 'root', '');

                // 新規登録確定
                if (isset($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['login_id'], $_REQUEST['login_pass'], $_REQUEST['agree'])) {
                    // 現在の日付と時刻を取得
                    $dateTime = new DateTime('now', new DateTimeZone('JST'));
                    $nowDateTime = $dateTime->format('Y/m/d H:i:s');

                    // DBに追加
                    $sql = $pdo->prepare('insert into users values(null, ?, ?, ?, ?, null, null, ?)');
                    $sql->execute([
                        $_REQUEST['name'], $_REQUEST['email'], $_REQUEST['login_id'], 
                        $_REQUEST['login_pass'], $nowDateTime
                    ]);

                    // セッションデータに格納

                    unset($_SESSION['users']);

                    $pdo = new PDO('mysql:host=localhost;dbname=kouka2249580re;charset=utf8', 'root', '');
            
                    $sql = $pdo->prepare('select * from users where login=? and password=?');
                    $sql->execute([$_REQUEST['login_id'], $_REQUEST['login_pass']]);

                    foreach ($sql as $row) {
                        $_SESSION['users'] = [
                            'id'=>$row['id'], 'name'=>$row['name'], 'email'=>$row['email'], 
                            'login_id'=>$row['login'], 'login_pass'=>$row['password'], 
                            'birthday'=>$row['birthday'], 'sex'=>$row['sex']
                        ];
                    }

                    // ログイン判定
                    if (isset($_SESSION['users'])) {    
                        // セッション固定攻撃を防ぐためにログイン後にセッションIDの再発行を行うことが効果的
                        session_regenerate_id();

                        // ページ遷移
                        header('Location:account.php');
                        exit();

                    } else {
                        echo '通信に失敗しました。';
                        echo 'ログイン画面で再度ログインしてください。';
                        echo '<p><a href="account.php">ログイン画面へ</a></p>';
                    }
                    
                } else {
                ?>
                    <div class="error">
                        <p>登録できませんでした。お手数をお掛けしますが再度入力してください</p>
                        原因例：「登録を確定しますか？」にチェックをいれていない
                        <p><a href="new_reg.php">🔙 登録画面に戻る</a></p>
                    </div>

                <?php } ?>

            </div>

        <?php } ?>

    </div>
</div>

<?php require 'h&f/footer1.php'; ?>