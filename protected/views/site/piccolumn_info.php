<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick-theme.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/justifiedGallery.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.justifiedGallery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/slick.js"></script>
<style type="text/css">
	#banner{
		padding-top: 62px;
	}
	.title_color{
		color:#db5523;
	}
	.piccolumn_text_color,.content{
		color: #7d7d7d;
	}
	.badge-read_more{
		background-color: #808080;
		color: #fff;
	}
	.read_more{
		bottom: 0;
		right: 0;
	}
	.hr{
		margin-top: 1rem;
	    margin-bottom: 1rem;
	    border: 0;
		border-top:1px solid #db5523;		
	}
</style>
<!-- 輪播圖 -- Start -->
<div id="banner" class="row">
  <?php if(count($banner_data)>0){?>
    <?php foreach ($banner_data as $key => $value) {?>
      <img src="<?= Yii::app()->request->baseUrl . $value['image']; ?>">
    <?php }?>
  <?php }?>
</div>
<!-- 輪播圖 -- End -->
<div class="container mt-5">
	<div class="col-lg-12">
		<?php $week = array("一","二","三","四","五","六","七");?>
		<div class="row my-4">
			<div class="col-lg-4 text-center">
				<img width="100%" src="<?php echo Yii::app()->createUrl('/'); ?><?=$piccolumn_data->pic?>">
			</div>
			<div class="col-lg-8">
				<div><h4 class="title_color"><?=$piccolumn_data->title?></h4></div>
				<div>
					<h5 class="pl-3 py-1 piccolumn_text_color">展覽期間：<?=$piccolumn_data->date_start?>(<?=$week[date_format(date_create($piccolumn_data->date_start),"N")-1];?>) ~ <?=$piccolumn_data->date_end?>(<?=$week[date_format(date_create($piccolumn_data->date_end),"N")-1];?>)</h5>
				</div>
				<div><h5 class="pl-3 py-1 piccolumn_text_color"><?= $piccolumn_data->time_desc?></h5></div>
				<div class="position-relative">
					<h5 class="pl-3 py-1 piccolumn_text_color">展覽地點：<?= str_replace(PHP_EOL, "<br>", $piccolumn_data->address)?></h5>
				</div>
			</div>
			<div class="col-lg-12 content mt-5 mb-3">
				<?=$piccolumn_data->content?>
			</div>
			<div class="col-lg-12 hr ml-3"> </div>
			<h4 class="title_color pl-3">圖片推薦</h4>
			<div class="col-lg-12 py-5" id="recommend_image">
		        <?php if(count($recommend_single_id_data)>0){?>
		          <?php foreach ($recommend_single_id_data as $key => $value) {?>
		            <div><img src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL.$value['single_id']?>.jpg"></div>
		          <?php }?>
		        <?php }?>
		    </div>
		</div>
		
	</div>
</div>

<script type="text/javascript">
	
	$(document).ready( function() {
		$('#banner').slick({
	        dots: true,
	        infinite: true,
	        arrows: false,
	        autoplay: true,
	        autoplaySpeed: 5000,
	        slidesToShow: 1,
	        slidesToScroll: 1
	    });
		$('#recommend_image').justifiedGallery({
	      rowHeight: 200,
	      maxRowHeight: 200,
	      margins : 25,
	      rel : 'gallery1',
	    });
	});
</script>