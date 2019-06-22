<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/justifiedGallery.min.css">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/bootstrap-slider.css" rel="stylesheet">

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.justifiedGallery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.twbsPagination.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-slider.js"></script>
<style type="text/css">
	.container{
		padding-top: 80px;
	}
	.advanced_filter{
		color: #d0604e;
	}
	.input-selector-btn-frame{
		cursor: pointer;
	}

	.slider-tick{
		height: 0;
	}

	.slider-selection,.slider-track-low,.slider-track-high{
		border-radius: 0;
		background-image: -webkit-linear-gradient(top, #d0604e 0%, #d0604e 100%);
	    background-image: -o-linear-gradient(top, #d0604e 0%, #d0604e 100%);
	    background-image: linear-grad;
		background-image: linear-gradient(to bottom, #d0604e 0%, #d0604e 100%);
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
</style>
<div class="container">
	<!-- Search Bar -- Start -->
	 <form name="group_form" class="col-lg-12 form-horizontal" id="keyword_search" action="<?php echo Yii::app()->createUrl('site/search');?>" method="post">
	    <div class="mx-auto input-group input-group-lg my-2">
	      	<input type="text" class="form-control" placeholder="推薦關鍵字：洛夫" aria-label="推薦關鍵字：洛夫" aria-describedby="basic-addon2" name="keyword" id="keyword" required>
	      	<input type="hidden" name="page" value="1" id="page">
	      	<div class="input-group-append">
	        	<button class="btn btn-outline-light customer_search_button" onclick="search();">搜尋</button>
	      	</div>
	    </div>
	    <div class="advanced_filter" style="">
	    	<div class="input-selector-btn-frame">進階搜尋
	    		<i class="fa fa-caret-down" aria-hidden="true"></i>
	    	</div>
	    	<div id="advanced_filter">
	    		<div class="row"><div class="col-lg-2">依時代</div><div class="col-lg-10"><input id="filming_date" type="text"></div></div>
	    		<div class="row"><div class="col-lg-2">依作品</div><div class="col-lg-10"></div></div>
	    		<div class="row"><div class="col-lg-2">依類別</div><div class="col-lg-10"></div></div>
	    	</div>
	    </div>
	</form>
	<!-- Search Bar -- End -->
	<input type="hidden" id="page" value="<?=isset($_GET['page'])?$_GET['page']:1?>">
	<div class="col-lg-12" id="image_result"></div>		
</div>
<div class="col-lg-12" id="page_selection"></div>
<script type="text/javascript">
	function create_image(value){
		$html = '<div><img src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL?>'+value.single_id+'.jpg"><div>';
        $('#image_result').append($html);
	}

  	$(document).ready( function() {
  		$("#filming_date").slider({
		  // the id of the slider element
			id: "",
		  // minimum value
			min: 1950,
		  // maximum value
			max: 1970,
		  // increment step
			step: 10,
		  // the number of digits shown after the decimal.
			precision: 0,
		  // 'horizontal' or 'vertical'
			orientation: 'horizontal',
		  // initial value
			value: 1950,
		  // enable range slider
			range: false,
			ticks: ['1950', '1960', '1970'],
		    ticks_labels: ['1950', '1960', '1970'],
		    // ticks_positions: ['0%','50%','100%'],

		    ticks_snap_bounds: 30
		  
		});
	  	$('#image_result').justifiedGallery({
	    	rowHeight: 200,
	      	maxRowHeight: 200,
	      	margins : 25,
	      	rel : 'gallery1',
	    });
	    $('#page_selection').twbsPagination({
	        totalPages: <?=$total_result?>,
	        visiblePages: 10,
	        first: '第一頁',
	        prev: '上一頁',
	        next: '下一頁',
	        last: '最後一頁',
	        startPage: "<?=isset($_GET['page'])?$_GET['page']:1?>",
	        onPageClick: function (event, page) {
	            $('#page').val(page);
	            $.ajax({  
	                url: "<?php echo Yii::app()->createUrl('site/findphoto')?>",  
	                type: "GET",  
	                dataType: "json",  
	                data: {
	                	page: page,
	                	keyword: "<?=$_GET['keyword']?>"
	                }, 
	                success: function(data) { 
	                    $('#image_result').html('');
	                    $('#image_result').justifiedGallery('destroy');

	                    $.each(data, function(index, value){
	                        create_image(value)
	                    });
	                    $('#image_result').justifiedGallery('norewind');
	                }  
	            });
	        }
	    });

  	});
</script>