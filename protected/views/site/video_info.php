<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick-theme.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/slick.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/jquery.fancybox.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.10.2/alt/video-js-cdn.css" rel="stylesheet">
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
		max-width: 100%;
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
			<video id="video" class="video-js vjs-default-skin thumbnail" controls preload="auto" crossorigin="true">
                <source src="<?=Yii::app()->createUrl('/')?>/image_storage/video/m3u8/<?=$data['m3u8_url']?>" type="application/x-mpegURL">
            </video>
			<form action="<?= Yii::app()->createUrl('site/add_favorite');?>" method="post" target="my_iframe" class="d-inline-block mt-5">
        		<input type="hidden" name="single_id" value="<?=$data['video_id']?>">
        		<input type="hidden" name="search_type" value="3">
        		<button type="button" onclick="add_favorite()" class="btn btn-favorite mx-2">加入收藏 <i class="fa fa-star"></i></button>
	        </form>
		</div>
		<div class="col-lg-5">
			<h4 class="size_color mt_70">簡介：</h4>
			<hr class="text_underline">
			<p class="info_color mt_45">簡介：<?=$data['description']?></p>
		</div>
	</div>
	<div class="row pc_des">
		<div class="col-lg-7">
			<h5 class="info_color my-3 mt-3 mt_45">基本資訊<i class="fa fa-caret-down" aria-hidden="true"></i></h5>
			<p class="info_color">影片名稱：<?=$data['name']?></p>
			<p class="info_color">影片格式：<?=$data['extension']?></p>
			<p class="info_color">影片長度：<?=$data['length']?></p>
			<p class="info_color">檔案大小：<?=$data['file_size']?>(KB)</p>
			<p class="info_color">分類：<?=$data['category_name']?></p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.10.2/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/3.0.2/videojs-contrib-hls.js"></script>
<script type="text/javascript">
    var myPlayer = videojs('video');
	function open_image_info(a,single_id){
		window.location = "<?= Yii::app()->createUrl('site/ImageInfo');?>/"+single_id;
	}

	function add_favorite(){
		<?php if (Yii::app() -> user -> isGuest){
			Yii::app()->user->returnUrl = Yii::app()->request->urlReferrer;
		?>
			localStorage.setItem("page",window.parent.$("#page").val());
			localStorage.setItem("single_id","<?=$_GET['id']?>");
			localStorage.setItem("search_type","<?=$_GET['search_type']?>");
			localStorage.setItem("add_favorite","true");
			parent.location.href="<?=Yii::app()->createUrl('site/login')?>";
        <?php }else{?>
        	$.ajax({  
		        url: "<?php echo Yii::app()->createUrl('site/add_favorite')?>",  
		        type: "post",  
		        dataType: "json",  
		        data: {
		        	single_id: "<?=$_GET['id']?>",
		        	search_type:"3"
		        }, 
		        success: function(data) {
		            if(!data.status){
		            	$.fancybox.open('<div class="alert alert-danger"><h4>加入失敗，請在試一次</h4></p></div>');
		            }
		            if(data.status){
		            	$.fancybox.open('<div class="alert alert-success"><h4>已加入我的收藏</h4></p></div>');
		            }
		        }  
		    });
        <?php }?>
	}
	$(document).ready( function() {
		if (localStorage.getItem("add_favorite") != null) {
	    	add_favorite();
	    	localStorage.removeItem("add_favorite");
	    }
	});
</script>