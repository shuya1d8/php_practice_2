<?php
// セッション開始
session_start();

// 初期値：ログイン画面
$pageFlag = 0;
if (isset($_SESSION['users'])) {
    // セッションデータがあればプロフィール画面へ
    $pageFlag = 1;

    if (!empty($_REQUEST['btn_change'])) {
        // btn_changeが押されたら変更画面へ
        $pageFlag = 2;

    } elseif (!empty($_REQUEST['btn_submit'])) {
        // btn_submitが押されたらプロフィール変更
        $pageFlag = 3;     

    } elseif (!empty($_REQUEST['btn_logout'])) {
        // btn_logoutが押されたらログアウト
        $pageFlag = 4;     
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アカウント | -？ろごろご？-</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- サイト引用：送信確認 -->
    <script>
        function check() {
            if (confirm('ログアウトしますか？')) {
                return true;

            } else {
                return false;

            }

        }
    </script>
    <!-- 月日に0をつける -->
    <script>
        var addZero = function(value) {
            if (value < 10) {
                value = '0' + value;
            }

            return value;
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
            <li><a href="new_reg.php">新規登録</a></li>
            <li class="current"><a href="account.php">アカウント</a></li>
        </ul>
    </nav>

</header>

<div id="wrapper">
    <div id="account">
        <!-- ログイン画面 -->
        <?php if ($pageFlag === 0) { ?>
            
            <div id="login">
                <h1><span>ログイン</span>画面</h1>

                <form action="" method="post">
                    <table>
                        <tr>
                            <th><label for="login_id">ログインID</label></th>
                            <td><input type="text" name="login_id" id="login_id"></td>
                        </tr>
                        <tr>
                            <th><label for="login_pass">パスワード</label></th>
                            <td><input type="password" name="login_pass" id="login_pass"></td>
                        </tr>
                    </table>

                    <!-- ログイン結果 -->
                    <?php
                    $loginFlag = false;
                    if (isset($_REQUEST['login_id'], $_REQUEST['login_pass'])) {
                        $loginFlag = true;
                        
                    }
                    
                    if ($loginFlag) {                    
                        // セッションデータを削除
                        unset($_SESSION['users']);
                        
                        // DBとPHPを接続
                        $pdo = new PDO('mysql:host=localhost;dbname=kouka2249580re;charset=utf8', 'root', '');
                        
                        // SQL文の用意&実行
                        $sql = $pdo->prepare('select * from users where login=? and password=?');
                        $sql->execute([$_REQUEST['login_id'], $_REQUEST['login_pass']]);
                        
                        // DBから登録情報取得
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
                            echo '<p>※ログインIDまたはパスワードが違います。</p>';
                        }
                        
                    }
                    ?>  
                    
                    <button>ログイン</button>
                </form>

                <div>
                    <a href="new_reg.php">新規登録の方はこちら</a>
                </div>

            </div>


        <!-- プロフィール画面 -->
        <?php } elseif ($pageFlag === 1) { ?>
            
            <div id="profile">
                <h1>プロフィール</h1>
                <table>
                    <tr>
                        <th>ユーザー名</th>
                        <td><?php echo $_SESSION['users']['name']; ?></td>
                    </tr>
                    <tr>
                        <th>ログインID</th>
                        <td><?php echo $_SESSION['users']['login_id']; ?></td>
                    </tr>
                    <tr>
                        <th>生年月日</th>
                        <td>
                            <?php 
                            if (empty($_SESSION['users']['birthday'])) { 
                                echo '未設定'; 
                            } else { 
                                echo $_SESSION['users']['birthday']; 
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>性別</th>
                        <td>
                            <?php 
                            if (empty($_SESSION['users']['sex'])) { echo '未設定'; } 
                            else { echo $_SESSION['users']['sex']; }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>メールアドレス</th>
                        <td><?php echo $_SESSION['users']['email']; ?></td>
                    </tr>
                </table>

                <div id="profChange">
                    <form action="" method="post">
                        <button name="btn_change" value="2">変更する</button>
                    </form>
                </div>

                <div id="logoutCheck">
                    <form action="" method="post" onsubmit="return check()">
                        <button name="btn_logout" value="4">ログアウト</button>
                    </form>
                </div>
            
            </div>

         
        <!-- 変更画面 -->
        <?php } elseif ($pageFlag === 2) { ?>

            <div id="profileChange">
                <h1>プロフィール</h1>

                <form action="" method="post">
                    <table>
                        <tr>
                            <th><label for="name">ユーザー名</label></th>
                            <td><input type="text" name="name" id="name" value="<?php echo $_SESSION['users']['name'] ?>" required></td>
                        </tr>
                        <tr>
                            <th>ログインID</th>
                            <td><?php echo $_SESSION['users']['login_id'] ?></td>
                        </tr>                        
                        <tr>
                            <th>
                                <label for="birthday">生年月日</label>
                                <br>
                                <?php if (empty($_SESSION['users']['birthday'])) { echo '<small>※一度登録すると変更できません。</small>';} ?>
                            </th>
                            <td>
                                <?php if (empty($_SESSION['users']['birthday'])) { ?>
                                    <input type="date" name="birthday">

                                    <!-- <select name="year" id="birthday">
                                        <option>未設定</option>
                                        <?php
                                        for ($i=2022; $i>=1920; $i--) {
                                            echo '<option value="', $i, '">', $i, '</option>';
                                        }
                                        ?>
                                    </select>
                                    年
                                    <select name="month">
                                        <option>未設定</option>
                                        <?php
                                        for ($i=1; $i<=12; $i++) {
                                            echo '<option value="', $i, '">', $i, '</option>';
                                        }
                                        ?>
                                    </select>
                                    月
                                    <select name="day">
                                        <option>未設定</option>
                                        <?php
                                        for ($i=1; $i<=31; $i++) {
                                            echo '<option value="', $i, '">', $i, '</option>';
                                        }
                                        ?>
                                    </select>
                                    日 -->
                                <?php 
                                } else {
                                    echo '<input type="hidden" name="birthday" value="', $_SESSION['users']['birthday'], '">';
                                    echo $_SESSION['users']['birthday'];
                                    // echo '<br><small>年月日が連続で表示されます。(例)2020年01月01日→20200101</small>';

                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="sex">性別</label></th>
                            <td>
                                <select name="sex" id="sex">
                                    <option value="未選択" <?php if ($_SESSION['users']['sex'] === '未選択') {echo 'selected';} ?>>未選択</option>
                                    <option value="男" <?php if ($_SESSION['users']['sex'] === '男') {echo 'selected';} ?>>男</option>
                                    <option value="女" <?php if ($_SESSION['users']['sex'] === '女') {echo 'selected';} ?>>女</option>
                                    <option value="その他" <?php if ($_SESSION['users']['sex'] === 'その他') {echo 'selected';} ?>>その他</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>メールアドレス・パスワード</th>
                            <td>いつか変更できます。</td>
                        </tr>
                    </table>

                    <button>戻る</button>
                    <button name="btn_submit" value="3">確定する</button>

                </form>

            </div>
        

        <!-- プロフィール変更 -->
        <?php } elseif ($pageFlag === 3) { ?>

            <div>
                <?php
                // 入力値の英数字,スペースの前半角変換
                // $name = mb_convert_kana($_REQUEST['name'], 'a, s');

                // 入力値の無害化
                $name = htmlspecialchars($_REQUEST['name'], ENT_QUOTES, 'UTF-8');


                $pdo = new PDO('mysql:host=localhost;dbname=kouka2249580re;charset=utf8', 'root', '');
                    
                if (isset($_SESSION['users'])) {
                    
                    // id取得
                    $id = $_SESSION['users']['id'];

                    // $year = $_REQUEST['year'];
                    // $month = $_REQUEST['month'];
                    // $day = $_REQUEST['day'];

                    // $month = addZero($month);
                    // $day = addZero($day);

                    // echo $year.$month.$day;
                    // $birthday = $year.$month.$day;

                    // 登録情報の更新作業
                    $sql = $pdo->prepare('update users set name=?, birthday=?, sex=? where id=?');
                    $sql->execute([$name, $_REQUEST['birthday'], $_REQUEST['sex'], $id]);

                    // セッションデータの更新
                    $_SESSION['users'] = [
                        'id'=>$id, 'name'=>$_REQUEST['name'], 'email'=>$_SESSION['users']['email'], 
                        'login_id'=>$_SESSION['users']['login_id'], 'login_pass'=>$_SESSION['users']['login_pass'],
                        'birthday'=>$_REQUEST['birthday'], 'sex'=>$_REQUEST['sex']
                    ];

                    // ログイン画面に遷移
                    header('Location:account.php');
                    exit();
                    
                } else {
                    echo '<p>有効期限が過ぎました。再度ログインしてください。</p>';
                    echo '<a href="account.php">ログイン画面へ</a>';
                    
                }
                
                ?>

            </div>


        <!-- ログアウト -->
        <?php } elseif ($pageFlag === 4) { ?>

            <div id="logout">
                <?php 
                    // セッションデータ削除
                    unset($_SESSION['users']);

                    // ログイン画面に遷移
                    header('Location:account.php');
                    exit();
                ?>

            </div>

        <?php } ?>

    </div>
</div>

<?php require 'h&f/footer1.php'; ?>
