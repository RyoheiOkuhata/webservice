<?php 
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログインページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');


$u_id = $_SESSION['user_id'];

// DBから商品データを取得（img,name,price）foreachで回す
$productData = getMyProducts($u_id);
debug('取得した商品データ：'.print_r($productData,true));

$dbCategoryData = getCategory();
debug('取得したカテゴリー：'.print_r($productData,true));


$likeData = getMyLike($u_id);
debug('取得したお気に入りデータ：'.print_r($likeData,true));





?>


<?php require('header.php');  
      require('w.php');
?>
     <!-- container -->
<div class="container">
  <p class="page-title">お気に入り</p>
    <div class="main-mypage">

  
    <!-- post一覧 -->
       
    <section class="post">
    <div class="post-flex">
          <?php
             if(!empty($likeData)):
              foreach($likeData as $key => $val):
            ?>
    
          <div class="post-item">
                <div class="post-img ">
                <a href="home.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>" class="panel">

                <img src="<?php echo showImg(sanitize($val['img1'])); ?>" alt="<?php echo sanitize($val['phrase']); ?>">
                </a>
                <?php echo $val['phrase']; ?> 
               </div>
          
                </div>
                <?php endforeach;endif;?>

                </div> 
            </section>

              <!-- /post一覧 -->

          </section>
        </div><!-- /container -->



        <section class="sidebar">      
        <form name="" method="get" class="">
        <h1 class="sidebar-title" >キーワードで検索する</h1>
          <input type="text" class="sidebar-btn btn" placeholder="" name="s_id" value="">
           <h1 class="sidebar-title" >シチュエーションで検索する</h1>


           <div class="sidebar-selectbox">
           <span class="icn_select"></span>
              <select name="category_id" id="">
                <option value="0" >選択してください</option>
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


        <input type="submit" value="検索" class="sidebar-submit btn">
        </form>
        <a href="fav.php">お気に入り</a>
        <a href="post.php">新しいphraseを登録</a>
        <a href="prof.php">プロフィール編集</a>
        <a href="without.php">退会</a>
        <a href="logout.php">ログアウト</a>
      </section>
      <?php require('footer.php');   ?>




       
      <?php require('footer.php');   ?>
