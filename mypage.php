
<p id="js-show-msg" style="display:none;" class=""></p>

<?php 

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('mypage');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

//user情報取得
$u_id = $_SESSION['user_id'];


reset();

getProduct($u_id,$p_id);

//カテゴリー情報を取得
$dbCategoryData = getCategory();


//検索、ページネーション用
//カテゴリー検索用
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
//キーワード用検索用
$seach= (!empty($_GET['s_id'])) ? $_GET['s_id'] : '';
debug('GETの中身：'.print_r($_GET ,true));



//ページネーション用
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;




// 表示件数を定義する
$listSpan = 9;
// 現在の表示レコード先頭を算出。
//$sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
$currentMinNum = (($currentPageNum-1)*$listSpan); //1ページ目なら(1-1)*20 = 0 、 

// DBからpostsデータを取得
$dbPostData = getPostList($currentMinNum,$category, $seach);
debug('phraseデータ：'.print_r($dbPostData ,true));


?>





<?php require('header.php');  
      require('w.php');
?>
     <!-- container -->
<div class="container">
  <p class="page-title">phrase一覧</p>
    <div class="main-mypage">
       <!-- search-result -->
      <section class="search-rlt">
        <div class="search-left"> 
        <span class="total-num"><?php echo sanitize($dbPostData['total']); ?></span>件の商品が見つかりました
         </div>
        <div class="search-right">
              <span class="num"><?php echo (!empty($dbPostData ['data'])) ? $currentMinNum+1 : 0; ?></span> - 
              <span class="num"><?php echo $currentMinNum+count($dbPostData ['data']); ?></span>件 / 
              <span class="num"><?php echo sanitize($dbPostData['total']); ?></span>件中
            </div>
          </section>
      <!-- /search-result -->
    <!-- post一覧 -->
          <section class="post">
            <div class="post-flex">
         <?php
            foreach($dbPostData['data'] as $key => $val):
          ?>
              <div class="post-item">
                <div class="post-img ">
                <a href="home.php?p_id=<?php echo $val['id'].'&p='.$currentPageNum; ?>" class="panel">

                <img src="<?php echo showImg(sanitize($val['img1'])); ?>" alt="<?php echo sanitize($val['phrase']); ?>">
                </a>
                <?php echo $val['phrase']; ?> 
               </div>
                </div>
                <?php
            endforeach;
          ?>

       <div class="pagination">
          <ul class="pagination-list">
            <?php
              $pageColNum = 5;
              $totalPageNum = $dbPostData['total_page'];
              //総ページ数＝総レコード数 ÷ ページ毎の表示件数。今回は９。少数切上げ。

              // 現在のページが、総ページ数と同じかつ総ページ数が表示項目数以上なら、左にリンク４個出す。つまり５の時。
              if( $currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
                $minPageNum = $currentPageNum - 4;
                $maxPageNum = $currentPageNum;

              // 現在のページが、総ページ数の１ページ前なら、左にリンク３個、右に１個出す。4の時
              }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum >= $pageColNum){
                $minPageNum = $currentPageNum - 3;
                $maxPageNum = $currentPageNum + 1;
              // 現ページが2の場合は左にリンク１個、右にリンク３個だす。
              }elseif( $currentPageNum == 2 && $totalPageNum >= $pageColNum){
                $minPageNum = $currentPageNum - 1;
                $maxPageNum = $currentPageNum + 3;
              // 現ページが1の場合は左に何も出さない。右に５個出す。
              }elseif( $currentPageNum == 1 && $totalPageNum >= $pageColNum){
                $minPageNum = $currentPageNum;
                $maxPageNum = 5;

              // 総ページ数が表示項目数（今回は５）より少ない場合は、総ページ数をループのMax、ループのMinを１に設定
              }elseif($totalPageNum < $pageColNum){
                $minPageNum = 1;
                $maxPageNum = $totalPageNum;
              // それ以外は左に２個出す。
              }else{
                $minPageNum = $currentPageNum - 2;
                $maxPageNum = $currentPageNum + 2;
              }
            ?>

            <?php if($currentPageNum != 1):// 1じゃないとき表示する。 ?>
              <li class="list-item"><a href="?p=1">&lt;</a></li>
            <?php endif; ?>

            <!-- 上の処理をループする -->
            <?php
              for($i = $minPageNum; $i <= $maxPageNum; $i++):
            ?>
              <li class="list-item <?php if($currentPageNum == $i ) echo 'active'; //今のpageとループの回数が同じならcssで色変える。?>"><a href="?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php
              endfor;
            ?>
           <!-- ループ終わり -->

            <?php if($currentPageNum != $maxPageNum):// 最後じゃないとき表示する ?>
              <li class="list-item"><a href="?p=<?php echo $maxPageNum; ?>">&gt;</a></li>
            <?php endif; ?>
          </ul>
        </div>
        
      </section>

               </div> 
            </section>
              <!-- /post一覧 -->
               <!-- pagenation -->
               



        <section class="sidebar">      
        <form name="" method="get" class="">
        <h1 class="sidebar-title" >キーワードで検索する</h1>
          <input type="text" class="sidebar-btn btn" placeholder="" name="s_id" value="">
           <h1 class="sidebar-title" >シチュエーションで検索する</h1>

           <div class="sidebar-selectbox">
           <span class="icn_select"></span>
              <select name="c_id" id="">
                <option value="0" >選択してください</option>
                <?php
                  foreach($dbCategoryData as $key => $val){
                ?>
    
                  <option value="<?php echo $val['id'] ?>" <?php if(getFormData('c_id',true) == $val['id'] ){ echo 'selected'; } ?> >


                    <?php echo $val['name']; ?>
                  </option>
                <?php
                  }
                ?>
                </option>
                </select>
              </div>


        <input type="submit" value="検索" class="sidebar-submit btn">
        </form>
        <a href="fav.php">お気に入り</a>
        <a href="post.php">新しいphraseを登録</a>
        <a href="without.php">退会</a>
        <a href="logout.php">ログアウト</a>
      </section>
      <?php require('footer.php');   ?>
