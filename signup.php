<?php


//共通変数・関数ファイルを読込み
require('function.php');



debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「ユーザー登録ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//post送信されていた場合
if(!empty($_POST)){
  debug('postされました');

  //変数にpostされた情報を代入
  $email = $_POST['email'];
  debug($email);
  $pass = $_POST['pass'];
  debug($pass);
  $pass_re = $_POST['pass_re'];
  debug($pass_re);
  //バリデーションチェック

  //未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');

  if(empty($err_msg)){

    //emailの形式チェック
    validEmail($email, 'email');
    //emailの最大文字数チェック
    validMaxLen($email, 'email');
    //email重複チェック
    validEmailDup($email);
    //パスワードの半角英数字チェック
    validHalf($pass, 'pass');
    //パスワードの最大文字数チェック
    validMaxLen($pass, 'pass');
    //パスワードの最小文字数チェック
    validMinLen($pass, 'pass');
    if(empty($err_msg)){

      //パスワードとパスワード再入力が合っているかチェック
      validMatch($pass, $pass_re, 'pass_re');
      if(empty($err_msg)){
        debug('DB接続して登録します');
        //例外処理
        try {
          // DBへ接
          $dbh = dbConnect();
          // SQL文作成
          $sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';
          $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                        ':login_time' => date('Y-m-d H:i:s'),
                        ':create_date' => date('Y-m-d H:i:s'));
          // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);
          
          // クエリ成功の場合
          if($stmt){
            //ログイン有効期限（デフォルトを１時間とする）
            $sesLimit = 60*60;
            // 最終ログイン日時を現在日時に
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $sesLimit;
            // ユーザーIDを格納
            $_SESSION['user_id'] = $dbh->lastInsertId();

            debug('セッション変数の中身：'.print_r($_SESSION,true));

            header("Location:mypage.php"); //マイページへ
          }

        } catch (Exception $e) {
          error_log('エラー発生:' . $e->getMessage());
          $err_msg['common'] = MSG07;
        }

      }
    }
  }
}
?>
<?php require('header.php'); ?>
<?php require('head.php');?>


<div class="container">
      <p class="page-title">新規登録</p>
    <div class ="container-form">
      <form method="post" class="form-signup" action="">
      <p>email</p>
      <!-- エラー時のstyle -->
      <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
    <!-- エラーメッセージ -->
          <div class="area-msg">
            <?php if(!empty($err_msg['email'])) echo $err_msg['email']?>
          </div>
      <!-- post保持 -->
          <input type="text" class="post-text-signup" name="email"
          value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?> ">
        </label>


      <p>パスワード</p>
      <label class=" <?php if(!empty($err_msg['pass'])) echo 'err' ?>">

          <div class="area-msg">
            <?php if(!empty($err_msg['pass'])) echo $err_msg['pass']?>
          </div>
          <input type="password" class="post-text-signup" name="pass" 
          value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']?> ">
        </label>

        <p>パスワード再入力</p>
        <label class=" <?php if(!empty($err_msg['pass_re'])) echo 'err' ?>">
        <div class="area-msg"><?php if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'] ?> </div>
          <input type="password" class="post-text-signup" name="pass_re" 
          value="<?php if(!empty($_POST['pass_re'])) echo $_POST["pass_re"]?> ">
        </label>

    <input type="submit" value="新規登録" class='btn'>    
       </form>
      </div>
      </div>
     