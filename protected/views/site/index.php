<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/justifiedGallery.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick-theme.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/layout.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.justifiedGallery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/slick.js"></script>
<div id="banner" class="row">
  <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/banner/banner_1.jpg">
  <!-- <img src="<?= Yii::app()->request->baseUrl; ?>/assets/image/banner/banner_2.jpg">
  <img src="<?= Yii::app()->request->baseUrl; ?>/assets/image/banner/banner_3.jpg">
  <img src="<?= Yii::app()->request->baseUrl; ?>/assets/image/banner/banner_4.jpg">
  <img src="<?= Yii::app()->request->baseUrl; ?>/assets/image/banner/banner_5.jpg"> -->
</div>
<div class="container">
  <form name="group_form" class="form-horizontal" action="#" method="get">
    <div class="col-lg-9 mx-auto input-group input-group-lg my-5">
      <input type="text" class="form-control" placeholder="推薦關鍵字：洛夫" aria-label="推薦關鍵字：洛夫" aria-describedby="basic-addon2">
      <div class="input-group-append">
        <button class="btn btn-outline-light customer_search_button" type="submit">搜尋</button>
      </div>
    </div>
  </form>
  <div class="py-5" id="ad_image">
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/1.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/2.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/3.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/4.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/5.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/6.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/7.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/8.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/9.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/10.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/11.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/12.jpg"></div>
      <div><img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/AD/13.jpg"></div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready( function() {
    $('#ad_image').justifiedGallery({
      rowHeight: 200,
      maxRowHeight: 200,
      margins : 25,
      rel : 'gallery1',
    });

    $('#banner').slick({
        dots: true,
        infinite: true,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 5000,
        slidesToShow: 1,
        slidesToScroll: 1
    });
  });
</script>