<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick-theme.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/jquery.fancybox.min.css">
<style type="text/css">
	/*.container{
		
	}*/
	.backgroud-white{
		padding-top: 80px;
		background-color: white;
	}
	.container:after {
	  	background-color:white;
	  	position:absolute;
	  	content:"";
	  	left:0;
	  	right:0;
	  	height:0px;
	  	top:0px;
	}
	.info_color{
		color: #66370e;
	}

	.brown_text_underline{
		cursor: pointer;
		padding-bottom: 5px;
		border-bottom: 2px solid #66370e;
	}
	.thumbnail{
		max-width: 500px;
		max-height: 500px;
	}
	.size_color,#size_info{
		color: #d0604e;
		
	}
	.text_underline{
		border-color: #d0604e;
		border-width: 2px;
	}
	.badge-keyword{
		background-color: #e8e8e8;
		color: #5e5e5e;
	}
	/* 進階搜尋 checkout 客製 css -- start */
	input[type="radio"] {
		-webkit-appearance: checkbox; /* Chrome, Safari, Opera */
		-moz-appearance: checkbox;    /* Firefox */
		-ms-appearance: checkbox;     /* not currently supported */
	}
	.mt_70{
		margin-top: 70px !important;
	}
	.mt_45{
		margin-top: 45px !important;
	}
	/* The container */
    .tiffany_checkbox {
        display: inline-block;
        position: relative;
        /*margin: 5px auto;*/
        /*height: 100%;*/
        cursor: pointer;
        font-size: 18px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .tiffany_checkbox input {
        position: absolute;
        opacity: 0;
        top: 0;
        left: 0;
        vertical-align: middle;
        /*margin-left: -12px;*/
        cursor: pointer;
        height: 100%;
        width: 100%;
        z-index: 2;
    }

    /* Create a custom checkbox */
    .tiffany_checkbox label {
        background-color: transparent;
        border: 1px solid #a8a8a9;
        color: #727171;
        cursor: pointer;
        border-radius: 5px;
    }

    /* On mouse-over, add a grey background color */
    .tiffany_checkbox:hover input ~ label {
        background-color: #eee;
    }

    /* When the checkbox is checked, add a blue background */
    .tiffany_checkbox input:checked ~ label{
    	border-color: #d0604e;
        background-color: #d0604e;
        color: #fcfcfc;
    }
    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .tiffany_checkbox label>input:checked ~ .checkmark:after {
        display: block;
    }
    /* 進階搜尋 checkout 客製 css -- end */
    .tooltip-inner{
    	background-color: #f7f7f7;
    	color: #a7a6a6;
    	font-size: 12px;
    	max-width: 100%;
    }
    .tooltip.show{
    	opacity: 1;
    }
    .tooltip.bs-tooltip-auto[x-placement^=right] .arrow::before, .tooltip.bs-tooltip-right .arrow::before {
	    border-width: 5px 5px 5px 0;
	    border-right-color: #f7f7f7;
	}
	.hidden{
		display: none;
	}
	.dropdown .dropdown-toggle::after{
		color: #a8a8a9;
	}
	.btn-outline-secondary:not(:disabled):not(.disabled).active, .btn-outline-secondary:not(:disabled):not(.disabled):active, .show>.btn-outline-secondary.dropdown-toggle{
		background-color: transparent;
		color: #db5524;
	}
	.dropdown .btn-outline-secondary,.dropdown .btn-outline-secondary:hover{
		border-color: #d8d9d9;
		color: #db5524;
		background-color: transparent;
	}
	.dropdown-item{
		color: #a8a8a9;
	}
	#download_cost{
		color: #727171;
	}
	.btn-download{
		background-color: #21a069;
		color: white;
	}
	.btn-favorite{
		background-color: #e48a22;
		color: white;
	}
	header,footer{
		display: none;
	}
	.pc_des{
		display: none;
	}
	.mobile_des {
		display: flex;
	}
	.slick-prev:hover, .slick-prev:focus, .slick-next:hover, .slick-next:focus{
		background-color: #e8e8e8;
		border-radius: 50%;
	}

	.slick-prev,.slick-next{
		background-color: #eaebeb;
		border-radius: 50%;
	}
	.slick-prev::before {
	    content: "<";
	    color: #db5524;
	}
	.slick-next::before {
	    content: ">";
	    color: #db5524;
	    font-weight: bold;
	}

	@media (min-width: 992px){
		.mobile_des {
			display: none;
		}
		.pc_des{
			display: flex;
		}
	}
		
</style>
<div class="col-lg-12 backgroud-white px-5">
	<div class="row">
		<div class="col-lg-7 my-auto">
			<img class="thumbnail" src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL.$_GET['id']?>.jpg">
		</div>
		<div class="col-lg-5">
			<h4 class="size_color mt_70">尺寸選擇</h4>
			<hr class="text_underline">
			<?php foreach ($photograph_data['size'] as $key => $value) {?>
				<div class="d-inline-block">
					<div class="tiffany_checkbox mt_45">                   
	                    <input type="radio" class="size_type" name="size_type" value="<?=$value['size_type']?>" data-w_h="<?=$value['w_h']?>" data-print_w_h="<?=$value['print_w_h']?>" data-dpi="<?=$value['dpi']?>" data-ext="<?=$value['ext']?>"  data-sale_point="<?=$value['sale_point']?>" <?=$key==0?"checked":""?> onchange="size_type(this)">
		                <label class="d-inline-block py-0 px-4 mb-0 mr-2"><?=$value['size_type']?></label>	                
	                </div>
	            </div>
			<?php } ?>

            <div id="size_info" class="my-2 mt_45"><?=$photograph_data['size'][0]['w_h']?> px | <?=$photograph_data['size'][0]['print_w_h']?> cm | <?=$photograph_data['size'][0]['dpi']?> dpi | <?=$photograph_data['size'][0]['ext']?></div>
            <div class="row mt_45">
            	<div class="col-lg-12">
		            <!-- <span class="info_color brown_text_underline mr-5">圖像授權協議概要</span> -->
		            <span class="info_color brown_text_underline tip" data-placement="right" data-tip="size_type_info">尺寸指南</span>
	        	</div>
	            <!-- Tips content -->
				<div id="size_type_info" class="tip-content hidden">
					<p>小型 (S) 下載時間最短，適合數位用途。</p> 
					<p>中型 (M) 適合小型印刷品和數位用途。</p>
					<p>大型 (L) 適合大型印刷品和數位用途。</p> 
					<p>大型 (XL) 適合大型印刷品和牆面印刷。</p>
				</div>
				<!-- Tips content -->
	        </div>
	        <div class="my-4 mt_45">
	        	<span id="download_cost"> <span id="sale_point"><?=(int)$photograph_data['size'][0]['sale_point']?>個下載點數從您的</span>
	        	<div class="dropdown d-inline-block">
				  	<button class="btn btn-outline-secondary dropdown-toggle" type="button" id="download_method" data-toggle="dropdown" data-download_method="1" aria-haspopup="true" aria-expanded="false">
				    點數方案
				  	</button>
				  	<div class="dropdown-menu" aria-labelledby="download_method">
						<button class="dropdown-item" type="button" data-download_method="1">點數方案</button>
						<button class="dropdown-item" type="button"data-download_method="2">自由載</button>
					</div>
				</div>
	        </div>
	        <div class="mt_45 d-inline-block">
	        	<form id="download_image_form" action="<?= Yii::app()->createUrl('site/download_image');?>" method="post" target="my_iframe" class="d-inline-block">
	        		<input type="hidden" name="single_id" value="<?=$photograph_data['photograph_info']['single_id']?>">
	        		<input type="hidden" name="size_type" id="size_type" value="<?=$photograph_data['size'][0]['size_type']?>">
	        		<input type="hidden" name="download_method" id="download_image_method" value="1">
		        	<button type="button" onclick="download_image()" class="btn btn-download mr-2">我要下載 <i class="fa fa-download"></i></button>
		        </form>
		        <form action="<?= Yii::app()->createUrl('site/add_favorite');?>" method="post" target="my_iframe" class="d-inline-block">
	        		<input type="hidden" name="single_id" value="<?=$photograph_data['photograph_info']['single_id']?>">
		        	<button type="submit" class="btn btn-favorite mx-2"><i class="fa fa-star"></i> 加入收藏</button>
		        </form>
	        	
	        </div>
	        
		</div>
	</div>
	<div class="row pc_des">
		<div class="col-lg-7">
			<h5 class="info_color my-3 mt-5 mt_70">圖片庫關鍵字</h5>
			<div>
				<?php foreach ($photograph_data['photograph_info']['keyword'] as $key => $value) {?>
					<a href="<?= Yii::app()->createUrl('site/search');?>/<?=$value?>/1"><span class="badge badge-keyword py-2 px-3 mr-2"><?=$value?></span></a>
				<?php }?>
			</div>
			<h5 class="info_color my-3 mt-5 brown_text_underline mt_45">更多資訊<i class="fa fa-caret-down" aria-hidden="true"></i></h5>
			<p class="info_color">照片類型：<?=$photograph_data['photograph_info']['category_name']?></p>
			<p class="info_color">色彩：<?=$photograph_data['source']['color']?></p>
			<p class="info_color">原件尺寸：<?=$photograph_data['source']['w_h']?></p>
			<p class="info_color">檔案格式：<?=$photograph_data['source']['ext']?></p>
		</div>
		<div class="col-lg-5">
			<div class="my-4">
	        	<p class="info_color">人物資訊：<?=$photograph_data['photograph_info']['people_info']?></p>
	        	<p class="info_color">事件名稱：<?=$photograph_data['photograph_info']['object_name']?></p>
	        	<p class="info_color">拍攝時間：<?=$photograph_data['photograph_info']['filming_date']?></p>
	        	<p class="info_color">拍攝地點：<?=$photograph_data['photograph_info']['filming_location']?></p>
	        	<p class="info_color mt_45">內容描述：<?=$photograph_data['photograph_info']['description']?></p>
	        	<p class="info_color mt_45">入藏來源：<?=$photograph_data['photograph_info']['photo_source']?></p>
	        </div>
		</div>
	</div>
	<div class="row mobile_des">
		<div class="col-lg-5">
			<div class="my-4">
	        	<p class="info_color">人物資訊：<?=$photograph_data['photograph_info']['people_info']?></p>
	        	<p class="info_color">事件名稱：<?=$photograph_data['photograph_info']['object_name']?></p>
	        	<p class="info_color">拍攝時間：<?=$photograph_data['photograph_info']['filming_date']?></p>
	        	<p class="info_color">拍攝地點：<?=$photograph_data['photograph_info']['filming_location']?></p>
	        	<p class="info_color mt_45">內容描述：<?=$photograph_data['photograph_info']['description']?></p>
	        	<p class="info_color mt_45">入藏來源：<?=$photograph_data['photograph_info']['photo_source']?></p>
	        </div>
		</div>
		<div class="col-lg-7">
			<h5 class="info_color my-3 mt-5 mt_70">圖片庫關鍵字</h5>
			<div>
				<?php foreach ($photograph_data['photograph_info']['keyword'] as $key => $value) {?>
					<a href="<?= Yii::app()->createUrl('site/search');?>/<?=$value?>/1"><span class="badge badge-keyword py-2 px-3 mr-2"><?=$value?></span></a>
				<?php }?>
			</div>
			<h5 class="info_color my-3 mt-5 brown_text_underline mt_45">更多資訊<i class="fa fa-caret-down" aria-hidden="true"></i></h5>
			<p class="info_color">照片類型：<?=$photograph_data['photograph_info']['category_name']?></p>
			<p class="info_color">色彩：<?=$photograph_data['source']['color']?></p>
			<p class="info_color">原件尺寸：<?=$photograph_data['source']['w_h']?></p>
			<p class="info_color">檔案格式：<?=$photograph_data['source']['ext']?></p>
		</div>		
	</div>
	<div class="row pb-5">
		<div class="col-lg-12">
			<h5 class="info_color brown_text_underline my-3 mt-5 mt_45">類似的圖片</h5>
		</div>
		<div class="col-lg-6 mx-auto slider-for">
			<?php foreach ($same_category as $key => $value) {?>
				<img class="px-3" onclick="open_image_info(this,'<?=$value->single_id?>')" src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL.$value->single_id?>.jpg">
			<?php }?>
		</div>
		<div class="col-lg-10 mx-auto slider-nav"> 
			<?php foreach ($same_category as $key => $value) {?>
				<img class="px-3" onclick="open_image_info(this,'<?=$value->single_id?>')" src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL.$value->single_id?>.jpg">
			<?php }?>
			
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="not_enough_modal" tabindex="-1" role="dialog" aria-labelledby="not_enough_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="not_enough_modal_title">餘額不足</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        餘額不足，要現在增加點數或方案嗎?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">我再想想</button>
        <a href="<?= Yii::app()->createUrl('site/plan');?>" target="_parent"><button type="button" class="btn btn-danger">立即購買</button></a>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/slick.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.fancybox.min.js"></script>
<script type="text/javascript">
	function open_image_info(a,single_id){
		window.location = "<?= Yii::app()->createUrl('site/ImageInfo');?>/"+single_id;
	}
	function size_type(a){
		var w_h = $(a).attr('data-w_h');
		var print_w_h = $(a).attr('data-print_w_h');
		var dpi = $(a).attr('data-dpi');
		var ext = $(a).attr('data-ext');
		$('#size_type').val($(a).val());
		$("#size_info").text(w_h + " px | " + print_w_h + " cm | " + dpi + " dpi | " + ext );
		if($('#download_method').attr('data-download_method')==1){
			$('#sale_point').text(parseInt($(a).attr('data-sale_point'))+"個下載點數從您的");
		}else{
			$('#sale_point').text("1個下載額度從您的");
		}
		
	}
	function ajax_download_image(){
		$.ajax({  
	        url: "<?php echo Yii::app()->createUrl('site/download_image')?>",  
	        type: "post",  
	        dataType: "json",  
	        data: {
	        	single_id: "<?=$photograph_data['photograph_info']['single_id']?>",
	        	size_type: $('#size_type').val(),
	        	download_method: $('#download_image_method').val(),
	        }, 
	        success: function(data) {
	            if(!data.status){
	            	alert(data.error_msg);
	            }
	            if(data.status){
	            	window.open("<?php echo Yii::app()->createUrl('site/GetImage')?>?single_id=<?=$photograph_data['photograph_info']['single_id']?>"+"&size_type="+$('#size_type').val()+"&ext="+data.ext);
	            }
	        }  
	    });
	}
	function download_image(){
		if($('#download_method').attr('data-download_method')==1){
			if(<?=$member_point?> > parseInt($('input[name=size_type]:checked').attr('data-sale_point'))){
				ajax_download_image();
			}else{
				$('#not_enough_modal').modal('show');
			}
		}else if($('#download_method').attr('data-download_method')==2){
			if(<?=$member_plan?> > 0){
				ajax_download_image();
			}else{
				$('#not_enough_modal').modal('show');
			}
		}		
	}

	$(document).ready( function() {
		$('.dropdown-menu button').on('click', function(){    
		    $('#download_method').html($(this).html());
		    var download_method = $(this).data('download_method');
		    $('#download_method').attr('data-download_method',download_method);
		    $('#download_image_method').val(download_method);
		    if(download_method == 1){
		    	$('#sale_point').text(parseInt($('input[name=size_type]:checked').attr('data-sale_point'))+" 個下載點數從您的");
		    }else{
		    	$('#sale_point').text("1 個下載額度從您的");
		    }
		    
		})
		// Tooltips
		$('.tip').each(function () {
			$(this).tooltip({
				html: true,
				title: $('#' + $(this).data('tip')).html()
			});
		});

		$('.slider-for').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
		 	arrows: true,
			fade: true,
			asNavFor: '.slider-nav'
		});

		$('.slider-nav').slick({
			slidesToShow: 3,
			slidesToScroll: 1,
			asNavFor: '.slider-for',
			dots: false,
			arrows: false,
			centerMode: true,
			focusOnSelect: true
		});

	});
</script>