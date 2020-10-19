<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/justifiedGallery.min.css">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/bootstrap-slider.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/jquery.fancybox.min.css">
<style type="text/css">
	#keyword:focus{
	    z-index: 0;
	  }
	.container{
		padding-top: 80px;
	}
	.advanced_filter{
		color: #d0604e;
	}
	.input-selector-btn-frame{
		cursor: pointer;
	}

	#search_type:focus{
		border: none;
		box-shadow: none;
	}

	#advanced_filter{
		display: block;
	}
	/* 進階搜尋 slider bar 客製 css -- start */
	.slider-tick{
		height: 0;
	}

	.slider-selection,.slider-track-low,.slider-track-high{
		border-radius: 0;
		background-image: -webkit-linear-gradient(top, #fff 0%, #fff 100%);
	    background-image: -o-linear-gradient(top, #fff 0%, #fff 100%);
	    background-image: linear-grad;
		background-image: linear-gradient(to bottom, #fff 0%, #fff 100%);
	}

	.slider-selection.tick-slider-selection{
		background-image: -webkit-linear-gradient(top, #d0604e 0%, #d0604e 100%);
	    background-image: -o-linear-gradient(top, #d0604e 0%, #d0604e 100%);
	    background-image: linear-grad;
		background-image: linear-gradient(to bottom, #d0604e 0%, #d0604e 100%);
		border-radius: 0;
	}
	/* Or display content like unicode characters or fontawesome icons */
	.slider-handle.round {
		width:0;
	    border-width:8px;
	    border-style:solid;
	    border-radius: 0;
	    border-color: #d0604e transparent transparent transparent; /* transparent 设置边框颜色透明 */
	    background-image: none;
	    background-color: transparent;
	    box-shadow: none;
	}
	.slider.slider-horizontal{
		width: 100%;
	}
	/* 進階搜尋 slider bar 客製 css -- end */

	/* 進階搜尋 checkout 客製 css -- start */
	/* The container */
    .tiffany_checkbox {
        display: inline-block;
        position: relative;
        margin: 5px auto;
        height: 20px;
        cursor: pointer;
        font-size: 18px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .tiffany_checkbox input {
        position: relative;
        opacity: 0;
        vertical-align: middle;
        /*margin-left: -12px;*/
        cursor: pointer;
        height: 20px;
        width: 20px;
        z-index: 2;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        height: 20px;
        width: 20px;
        left: 0;
        top: 0;
        /*margin-left: -12px;*/
        background-color: transparent;
        border: 1px solid #d0604e;
        cursor: pointer;
    }

    /* On mouse-over, add a grey background color */
    .tiffany_checkbox:hover input ~ .checkmark {
        background-color: #eee;
    }

    /* When the checkbox is checked, add a blue background */
    .tiffany_checkbox input:checked ~ .checkmark {
        background-color: #d0604e;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .tiffany_checkbox input:checked ~ .checkmark:after {
        display: block;
    }
    /* 進階搜尋 checkout 客製 css -- end */

    /* 頁碼 客製 css -- start */
    #page_selection .pagination{
    	margin-left: auto;
    	margin-right: auto;
    }

    .page-item{
    	border-color: transparent;
    	margin-right: 5px;
    }
    .page-link:hover{
    	color: #d0604e;
    }
    .page-link,.page-item.disabled .page-link{
    	border-radius: 5px;
    	border-color: transparent;
    	color: #d0604e;
    }
    .page-item.active .page-link{
    	background-color: #d0604e;
    	border-color: transparent;
    }
    /* 頁碼 客製 css -- end */
    .fancybox-slide--iframe .fancybox-content {
	    width  : 90%;
	    height : 90%;
	    max-width  : 90%;
	    max-height : 90%;
	}
	.tri_up{
		width: 0;
		height:0;
		border-left:10px solid transparent;
		border-right: 10px solid transparent;
		border-bottom: 15px solid white;
	}
	.tri_up_underline{
		width:20px;
		background-color: white;
		height: 2px;
	}
	.to_top{
		background-color: #d0604e;
	    width: 40px;
	    height: 40px;
	    display: inline-grid;
	    padding: 10px;
	    border-radius: 5px;
	    position: fixed;
	    bottom: 120px;
	    right: 20px;
	    cursor: pointer;
	    z-index: 9;
	}
</style>
<div class="container">
	<?php
    $this->renderPartial('pages/search_bar', array("distinct_object_name"=>$distinct_object_name, "category_data" => $category_data, "filming_date_range" => $filming_date_range,'FK_data'=>$FK_data));
    ?>
	<input type="hidden" id="page" value="<?=isset($_GET['page'])?$_GET['page']:1?>">
	<div class="col-lg-12" id="image_result"></div>		
</div>
<div class="row my-5" id="page_selection"></div>
<div class="to_top"><span class="tri_up"></span><span class="tri_up_underline"></span></div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.fancybox.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.justifiedGallery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.twbsPagination.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-slider.js"></script>
<script type="text/javascript">
	
	// function closeIFrame(){
	//     $('#youriframeid').remove();
	// }
	function open_image_info(single_id){
		$.fancybox.open({
	        type: 'iframe',
	        src: '<?= Yii::app()->createUrl('site/ImageInfo');?>/'+single_id,
	        toolbar  : false,
			smallBtn : true,
			iframe : {
				preload : true,
				css : {
					width : '90%',
					height: '90%'
				}
			}
	    });
	}

	function create_image(value){
		$html = '<div onclick="open_image_info(\''+value.single_id+'\')" style="cursor:pointer;"><img src="<?= Yii::app()->createUrl('/'). "/" .PHOTOGRAPH_STORAGE_URL?>'+value.single_id+'.jpg"><div>';
        $('#image_result').append($html);
	}

	function rejustifiedGallery_init(){
        $('#image_result').justifiedGallery({
	    	rowHeight: 200,
	      	maxRowHeight: 200,
	      	margins : 25,
	      	// refreshTime: 1000,
	      	rel : 'gallery1',
	    });
    }
    
  	$(document).ready( function() {
  		
  		$( ".to_top" ).click(function() {
			// document.body.scrollTop = 0; // For Safari
  	// 		document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  			$("html, body").animate({ scrollTop: 0 }, "slow");
		});
  		
	  	var init_page = 1;
	  	if (localStorage.getItem("page") != null) {
	  		init_page = localStorage.getItem("page");
	  		localStorage.removeItem("page");
	  	}else{
	  		init_page = <?=isset($_GET['page'])?$_GET['page']:1?>;
	  	}
	    $('#page_selection').twbsPagination({
	        totalPages: <?=$total_result != 0 ?$total_result:1?>,
	        visiblePages: 10,
	        first: '第一頁',
	        prev: '上一頁',
	        next: '下一頁',
	        last: '最後一頁',
	        startPage: parseInt(init_page),
	        onPageClick: function (event, page) {
	            $('#page').val(page);
	            $.ajax({  
	                url: "<?php echo Yii::app()->createUrl('site/findphoto')?>",  
	                type: "GET",  
	                dataType: "json",  
	                data: {
	                	page: page,
	                	keyword: "<?=$_GET['keyword']?>",
	                	search_type: "<?=$_GET['search_type']?>",
	                	category_id: "<?=isset($_GET['category_id'])?$_GET['category_id']:''?>",
	                	filming_date: "<?=isset($_GET['filming_date'])?$_GET['filming_date']:''?>",
	                	object_name: "<?=isset($_GET['object_name'])?$_GET['object_name']:''?>",
	                }, 
	                success: function(data) { 
	                    $('#image_result').html('');
	                    $.each(data, function(index, value){
	                        create_image(value)
	                    });
	                    rejustifiedGallery_init();
	                }  
	            });
	        }
	    });
	    if (localStorage.getItem("single_id") != null) {
	    	open_image_info(localStorage.getItem("single_id"));
	    	localStorage.removeItem("single_id");
	    }
  	});
</script>