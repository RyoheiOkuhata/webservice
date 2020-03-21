<?php 

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログインページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$u_id = $_SESSION['user_id'];
$productData= getProductHome($p_id);
debug('phraseデータ：'.print_r($productData,true));


?>







<?php require('header.php');  
      require('head.php');
?>
<div class="container">
  <div class="container-detail">
  <div class="page-title">phraseの詳細</div>



<section class="">
<div class="detail">
  <div class="detail-phrase"><?php echo (sanitize($productData["phrase"])) ?></div> 
  <div class="detail-fav">



    <i class="fa fa-heart-o js-click-animation fav js-click-like<?php if(isLike($_SESSION['user_id'],$p_id )){ echo 'fa-heart-o '; }?>" aria-hidden="true "data-productid="<?php echo  $productData['id']; ?>"></i>

    <i class="fa fa-heart js-click-animation2 fav2 js-click-like<?php if(isLike($_SESSION['user_id'], $productData['id'])){ echo 'fa-heart '; }?>" aria-hidden="true" data-productid="<?php echo(sanitize($productData['id'])); ?>"></i>


  </div>
</div>
</section>
<section class="">
  <div class="main-detail">

  
   <div class="main-detail-post-img">

   <a href="post.php?p_id=<?php echo $p_id; ?>" class="panel">

   <img src="<?php echo showImg(sanitize($productData['img1'])); ?>"  class="js-switch-img-main">

     </a>
     </div>
    <div class="detail-post-sentence-wrap">
        <p>自分で作った文</p>
          <p><?php echo  $productData["comment"]; ?> </p>
        </div>
      </div>
      <div class="sub-detail">
   <img src="<?php echo showImg(sanitize($productData['img1'])); ?>" alt="<?php echo sanitize($productData['phrase']); ?>" class="js-switch-img-sub">
   <img src="<?php echo showImg(sanitize($productData['img2'])); ?>" alt="<?php echo sanitize($productData['phrase']); ?>" class="js-switch-img-sub">
   <img src="<?php echo showImg(sanitize($productData['img3'])); ?>" alt="<?php echo sanitize($productData['phrase']); ?>" class="js-switch-img-sub">
        </div>
        </section>

    </div>
    <a class="back_to_mypage" href="mypage.php<?php appendGetParam(array('p_id')); ?>">&lt; 戻る</a>

   </div>

        
   <?php require('footer.php'); ?>

         