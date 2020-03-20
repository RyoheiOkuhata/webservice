$(function(){
/* -------------------------------
画像切替
---------------------------------*/
  var $switchImgSubs = $('.js-switch-img-sub'),
      $switchImgMain = $('.js-switch-img-main');
  $switchImgSubs.on('click',function(){
    $switchImgMain.attr('src',$(this).attr('src'));
});
/* -------------------------------
  お気に入りのハートマーク
---------------------------------*/
    $('.js-click-animation').on('click', function () {
      var $this = $(this);
      //何回も使うときは変数に入れる。キャッシュという
      //$()はjqで使えるオブジェクトに変換している
      //何回もかくと毎回オブジェクトが呼ばれてしまう
      $this.toggleClass('fa-heart-o');
      $this.toggleClass('fa-heart');
      //付け替えてfav1がfav2になるということ。
      $this.toggleClass('is-active');
      $('.js-click-animation2').toggleClass('is-active');
      //アニメーションの処理
    });  
/* -------------------------------
Ajax お気に入り登録・削除
---------------------------------*/
      var $like,
      likeProductId;
  $like = $('.js-click-like') || null; //nullというのはnull値という値で、「変数の中身は空ですよ」と明示するためにつかう値
  likeProductId = $like.data('productid') || null;
  console.log(likeProductId);
  // 数値の0はfalseと判定されてしまう。
  //product_idが0の場合もありえるので、0もtrueとする場合にはundefinedとnullを判定する

  if(likeProductId !== undefined && likeProductId !== null){
    $like.on('click',function(){
      var $this = $(this);//キャッシュ,
      $.ajax({
        type: "POST",
        url: "ajax-fav.php",
        dataType: 'json',
        data: { productId : likeProductId}
                
      }).done(function(){
        // クラス属性をtoggleでつけ外しする
        $this.toggleClass('active');

      }).fail(function( msg ) {
        console.log('Ajax Error');
      });
    });
  };

/* -------------------------------
ライブプレブュー
---------------------------------*/
    var $dropArea = $('.area-drop');
    var $fileInput = $('.input-file');
    $dropArea.on('dragover', function(e){
      //ドラッグ乗せたとき。
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', '3px #ccc dashed');
      console.log($dropArea);
    });
    $dropArea.on('dragleave', function(e){
      //ドラッグはなしたとき
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', 'none');
    });

    $fileInput.on('change', function(e){
      //inputの中身変更されたとき。
      $dropArea.css('border', 'none');
      var file = this.files[0],
          // files配列にファイルが入っている
          //<input type=”file”>で選択されたファイルパスをJSのfiles[0]で取得できる
          $img = $(this).siblings('.prev-img-post'), 
          // 3. jQueryのsiblingsメソッドで兄弟のdomを取得
          //domの時は変数に$をつける
          fileReader = new FileReader();   
          //ファイルを読み込むFileReaderオブジェクト
          console.log(file);
          console.log($img);
          console.log(fileReader);

      // 5.読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event) {
        // eventに帰ってきたデータを取得、imgに設定
        //cssでhideにしてるからshowしてやる
        console.log(event);
        $img.attr('src', event.target.result).show();
      };
      fileReader.readAsDataURL(file);
    // 6. 画像自体をdataURLに変換。文字列にして入れる
    //通常パスを指定するため、リクエストしなくてもすむが、大容量の画像はできないことや、文字列にすると、その画像自体の容量がでかくなってしまう
    });
  







});



