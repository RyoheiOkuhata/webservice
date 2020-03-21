
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
// DBからカテゴリー情報を取得
$dbCategoryData = getCategory();
$dbPostData = getMyProducts($u_id);
//

?>





<?php require('header.php');  
      require('w.php');
?>
     <!-- container -->
<div class="container">
  <p class="page-title">自分の投稿たち。編集もできるよ</p>
    <div class="main-mypage">

          <section class="post">
            <div class="post-flex">
         <?php
            foreach($dbPostData  as $key => $val):
          ?>
              <div class="post-item">
                <div class="post-img ">
                <a href="post.php?p_id=<?php echo $val['id']; ?>" class="panel">
                <img src="<?php echo showImg(sanitize($val['img1'])); ?>" alt="<?php echo sanitize($val['phrase']); ?>">
                </a>
                <?php echo $val['phrase']; ?> 
                <?php echo $val['comment']; ?> 
               </div>
                </div>
                <?php
            endforeach;
          ?>



       
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
           <a href="my_product.php">みんなの投稿たち</a>
        <a href="fav.php">お気に入り</a>
        <a href="post.php">新しいphraseを登録</a>
        <a href="without.php">退会</a>
        <a href="logout.php">ログアウト</a>
      </section>
      <?php require('footer.php');   ?>
