<?php 
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「退会ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================
// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'UPDATE users SET  delete_flg = 1 WHERE id = :us_id';

    // データ流し込み
    $data = array(':us_id' => $_SESSION['user_id']);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
  

    // クエリ実行成功の場合（最悪userテーブルのみ削除成功していれば良しとする）
    //商品登録などある場合はそれらもUPDATEするからSQLをその分作る。今回はUSERSのみ。
    if($stmt){
     //セッション削除
      session_destroy();
      debug('セッション変数の中身：'.print_r($_SESSION,true));
      debug('トップページへ遷移します。');
      header("Location:signup.php");
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>


<?php require('header.php'); ?>
<?php require('w.php'); ?>   


<div id="withdraw">
  <form method="post" class="withdraw-form" action="">
     <p> 退会しますか？</p> 
    <input type="submit" name="withdraw-submit" id="" value="退会">
  </form>
  <a href="mypage.php">マイページに戻る</a>
</<a>



