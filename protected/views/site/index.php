<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/justifiedGallery.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick-theme.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick.css">

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.justifiedGallery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/slick.js"></script>
<!-- 輪播圖 -- Start -->
<div id="banner" class="row">
  <?php if(count($banner_data)>0){?>
    <?php foreach ($banner_data as $key => $value) {?>
      <img src="<?= Yii::app()->request->baseUrl . $value['image']; ?>">
    <?php }?>
  <?php }?>
</div>
<!-- 輪播圖 -- End -->
<div class="container">
  <!-- Search Bar -- Start -->
  <form name="group_form" class="form-horizontal" id="keyword_search" action="<?php echo Yii::app()->createUrl('site/search');?>" method="post">
    <div class="col-lg-9 mx-auto input-group input-group-lg my-5">
      <input type="text" class="form-control" placeholder="推薦關鍵字：洛夫" aria-label="推薦關鍵字：洛夫" aria-describedby="basic-addon2" name="keyword" id="keyword" required>
      <input type="hidden" name="page" value="1" id="page">
      <div class="input-group-append">
        <button class="btn btn-outline-light customer_search_button" onclick="search();">搜尋</button>
      </div>
    </div>
  </form>
  <!-- Search Bar -- End -->
  <div class="text-center">
    <ul class="nav nav-tabs d-inline-flex" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active py-1" id="image_tab" data-toggle="tab" href="#adimage" role="tab" aria-controls="adimage" aria-selected="true"><i class="fa fa-th-large"></i> 圖文式</a>
      </li>
      <li class="nav-item">
        <a class="nav-link py-1" id="imagelist_tab" data-toggle="tab" href="#adimagelist" role="tab" aria-controls="adimagelist" aria-selected="false"><i class="fa fa-th-list"></i> 列表式</a>
      </li>
    </ul>
  </div>
  <div class="tab-content">
    <div class="tab-pane fade show active" id="adimage" role="tabpanel" aria-labelledby="image_tab">
      <div class="py-5" id="ad_image">
        <?php if(count($ad_data)>0){?>
          <?php foreach ($ad_data as $key => $value) {?>
            <div><img src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL.$value['single_id']?>.jpg"></div>
          <?php }?>
        <?php }?>
      </div>
    </div>
    <div class="tab-pane fade" id="adimagelist" role="tabpanel" aria-labelledby="imagelist_tab">
      <div class="py-5 col-lg-8 mx-auto">
        <?php foreach ($ad_data as $key => $value) {?>
          <div class="row col-lg-12 py-3">
            <div class="col-lg-4 text-right"><img src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL.$value['single_id']?>.jpg" width="80%"></div>
            <div class="col-lg-8 my-auto">
              <div class="col-lg-12">人物資訊:<?=$value['people_info']?></div>
              <div class="col-lg-12">事件名稱:<?=$value['object_name']?></div>
              <div class="col-lg-12">拍攝時間:<?=$value['filming_date']?></div>
              <div class="col-lg-12">拍攝地點:<?=$value['filming_location']?></div>
            </div>
          </div>
        <?php }?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function search(){
    var keyword = $("#keyword").val();
    var page = $("#page").val();
    if(keyword != '' && page >0){
      $('#keyword_search').attr('action',"<?php echo Yii::app()->createUrl('site/search');?>/" + keyword + "/" + page);
      $('#keyword_search').submit();
    }   
  }
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