<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('phrase登録ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//mypageのリンクからgetを取得
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
// DBからphraseデータを取得
$dbFormData = (!empty($p_id)) ? getProduct($_SESSION['user_id'], $p_id) : '';
// 新規登録画面か編集画面か判別用フラグ
$edit_flg = (empty($dbFormData)) ? false : true;
// DBからカテゴリデータを取得
$dbCategoryData = getCategory();
debug('GETのID：'.print_r($p_id,true));
debug('phraseデータ：'.print_r($dbFormData,true));
debug('カテゴリデータ：'.print_r($dbCategoryData,true));

// パラメータ改ざんチェック
// GETパラメータはあるが、改ざんされている（URLをいじくった）場合、
//正しい商品データが取れないのでマイページへ遷移させる。
if(!empty($p_id) && empty($dbFormData)){
  debug('GETパラメータの商品IDが違います。マイページへ遷移します。');
  header("Location:mypage.php"); //マイページへ
}


//================================
// 
//================================
if(!empty($_POST)){
debug('POST送信があります。');
debug('POST情報：'.print_r($_POST,true));
debug('FILE情報：'.print_r($_FILES,true));



//パスを格納
 $phrase = $_POST['phrase'];
 $category = (!empty($_POST['category_id'])) ? $_POST['category_id'] : '';
 $comment = $_POST['comment'];
 
  $img1 = ( !empty($_FILES['img1']['name']) ) ? uploadImg($_FILES['img1'],'img1') : '';
  // 編集の時画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
  $img1 = ( empty($img1) && !empty($dbFormData['img1']) ) ? $dbFormData['img1'] : $img1;
  $img2 = ( !empty($_FILES['img2']['name']) ) ? uploadImg($_FILES['img2'],'img2') : '';
  $img2 = ( empty($img2) && !empty($dbFormData['img2']) ) ? $dbFormData['img2'] : $img2;
  $img3 = ( !empty($_FILES['img3']['name']) ) ? uploadImg($_FILES['img3'],'img3') : '';
  $img3 = ( empty($img3) && !empty($dbFormData['img3']) ) ? $dbFormData['img3'] : $img3;
  

  //未入力チェック
  validRequired($phrase, 'phrase');
  validRequired($comment, 'comment');
  //最大文字数チェック
  validMaxLen($phrase, 'phrase');
  validMaxLen($comment, 'comment');
  //半角英数字チェック
  validHalf($phrase, 'phrase');
  if(empty($err_msg)){
    debug('バリデーションOKです');
      //例外処理
      try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        // 編集画面の場合はUPDATE文、新規登録画面の場合はINSERT文を生成
        if (!$edit_flg) {
            debug('DB新規登録です。');
            $sql = 'insert into phrase_register (phrase,category_id,comment, img1, img2, img3, user_id, create_date ) values (:phrase, :category_id, :comment,:img1, :img2, :img3, :u_id, :date)';
            $data = array(':phrase' => $phrase , ':category_id' => $category, ':comment' => $comment, ':img1' => $img1, ':img2' => $img2, ':img3' => $img3, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
        }else{
          debug('DB編集です。');
          $sql = 'UPDATE phrase_register SET phrase = :phrase, category_id = :category_id, comment = :comment, img1 = :img1, img2 = :img2, img3 = :img3 WHERE user_id = :u_id AND id = :p_id';
          $data = array(':phrase' => $phrase , ':category_id' => $category, ':comment' => $comment, ':img1' => $img1, ':img2' => $img2, ':img3' => $img3, ':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
        }
        debug('SQL：'.$sql);
        debug('流し込みデータ：'.print_r($data,true));
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // クエリ成功の場合
        if($stmt){
          $_SESSION['msg_success'] = SUC04;
          debug('マイページへ遷移します。');
          header("Location:mypage.php"); //マイページへ
        }
      } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = MSG07;
      }
    }
  }

?>;


<?php require('header.php'); ?>
<?php require('head.php');?>
<div class="container">
      <p class="page-title"><?php echo (!$edit_flg) ? 'phraseを登録する' : 'phraseを編集する'; ?></p>
    <div class ="container-form">
    
      <form method="post" class="post-form" action="" enctype="multipart/form-data" >
      <p>phrase</p>
      <label class="<?php if(!empty($err_msg['phrase'])) echo 'err'; ?>">
          <div class="area-msg">
            <?php 
             if(!empty($err_msg['phrase'])) echo $err_msg['phrase'];
             ?>
          </div>
          <div class="area-msg"></div>
          <input type="text" class="post-text-signup" name="phrase" value="<?php echo getFormData('phrase'); ?>">
        </label>
        <p>シチュエーション</p>
        <label class="<?php if(!empty($err_msg['category'])) echo 'err'; ?>">
        <div class="area-msg">
            <?php 
             if(!empty($err_msg['category'])) echo $err_msg['category'];
             ?>
          </div>
            <div class="post-selectbox">
              <select name="category_id" id="">
                <option value="0">選択してください</option>
                <?php
                  foreach($dbCategoryData as $key => $val){
                ?>
                  <option value="<?php echo $val['id'] ?>" >
                    <?php echo $val['name']; ?>
                  </option>
                <?php
                  }
                ?>
                </option>
                </select>
              </div>
            </label>

        <p>自分で文を作る</p>
        <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
          <div class="area-msg">
            <?php 
             if(!empty($err_msg['comment'])) echo $err_msg['comment'];
             ?></div>
          <input type="text" class="post-text-signup" name="comment" value="<?php echo getFormData('comment'); ?>">
        </label>

        <div class="drop">
        <div class="area-msg">
                  <?php 
                  if(!empty($err_msg['img1'])) echo $err_msg['img1'];
                  ?>
                </div>
                <p style="overflow: hidden;">画像1 ドロップ&ドラッグ </p> 
              <div class="imgDrop-container">
                <label class="area-drop ">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                  <input type="file" name="img1" class="input-file">
                  <img src="<?php echo getFormData('img1'); ?>" alt="" class="prev-img-post" style="<?php if(empty(getFormData('img1'))) echo 'display:none;' ?>">
                </label>
                </div>
                <div class="area-msg">
                  <?php 
                  if(!empty($err_msg['img2'])) echo $err_msg['img2'];
                  ?>
                </div>
                <p style="overflow: hidden;">画像2 ドロップ&ドラッグ</p> 
              <div class="imgDrop-container">
                <label class="area-drop ">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                  <input type="file" name="img2" class="input-file">
                  <img src="<?php echo getFormData('img2'); ?>" alt="" class="prev-img-post" style="<?php if(empty(getFormData('img2'))) echo 'display:none;' ?>">
                </label>
              </div>
              <?php 
                  if(!empty($err_msg['img3'])) echo $err_msg['img3'];
                  ?>
              <p style="overflow: hidden;">画像3 ドロップ&ドラッグ</p> 
              <div class="imgDrop-container">
                <label class="area-drop ">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                  <input type="file" name="img3" class="input-file">
                  <img src="<?php echo getFormData('img3'); ?>" alt="" class="prev-img-post" style="<?php if(empty(getFormData('img3'))) echo 'display:none;' ?>">
        
                </label>
                <div class="area-msg"></div>    
              </div> 

              </div>

              <div class="submit">
              <input type="submit" value="<?php echo (!$edit_flg) ? '登録する' : '更新する'; ?>" class='ログイン'> 
              </div>
       </form>
              </div>
            </div>

            <?php require('footer.php'); ?>