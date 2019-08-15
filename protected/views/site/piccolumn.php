<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick-theme.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/slick.js"></script>
<style type="text/css">
	#banner{
		padding-top: 62px;
	}
	.title_color{
		color:#db5523;
	}
	.piccolumn_text_color{
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
	hr{
		border-top-color:#db5523;		
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
		<?php 
		$week = array("一","二","三","四","五","六","七");
		foreach ($piccolumn_date as $key => $value) {?>
			<div class="row my-4">
				<div class="col-lg-4 text-center">
					<img src="<?php echo Yii::app()->createUrl('/'); ?><?=$value['pic']?>" class="w-100">
				</div>
				<div class="col-lg-8">
					<div><h4 class="title_color"><?=$value['title']?></h4></div>
					<div>
						<h5 class="pl-3 py-1 piccolumn_text_color">展覽期間：<?=$value['date_start']?>(<?=$week[date_format(date_create($value['date_start']),"N")-1];?>) ~ <?=$value['date_end']?>(<?=$week[date_format(date_create($value['date_end']),"N")-1];?>)</h5>
					</div>
					<div><h5 class="pl-3 py-1 piccolumn_text_color"><?= $value['time_desc']?></h5></div>
					<div class="position-relative">
						<h5 class="pl-3 py-1 piccolumn_text_color">展覽地點：<?= str_replace(PHP_EOL, "<br>", $value['address'])?></h5>
						<a class="position-absolute read_more" href="<?php echo Yii::app()->createUrl('site/piccolumnInfo'); ?>/<?=$value['piccolumn_id']?>"><span class="badge badge-read_more py-2 px-3 mr-2">了解更多</span></a>
					</div>
				</div>
			</div>
			<hr/>
		<?php }?>
		
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

	});
</script>