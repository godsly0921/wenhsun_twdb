<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/justifiedGallery.min.css">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/bootstrap-slider.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/jquery.fancybox.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.fancybox.min.js"></script>
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
	#advanced_filter{
		display: none;
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
	<!-- Search Bar -- Start -->
	 <form name="group_form" class="col-lg-12 form-horizontal" id="keyword_search" action="<?php echo Yii::app()->createUrl('site/search');?>" method="post">
	    <div class="mx-auto input-group input-group-lg my-2">
	      	<input type="text" class="form-control" placeholder="推薦關鍵字：洛夫" aria-label="推薦關鍵字：洛夫" aria-describedby="basic-addon2" name="keyword" id="keyword" value="<?=$_GET['keyword']?>" required>
	      	<input type="hidden" name="page" value="<?=$_GET['page']?>" id="page">
	      	<div class="input-group-append">
	        	<button class="btn btn-outline-light customer_search_button" onclick="search();">搜尋</button>
	      	</div>
	    </div>
	    <div class="advanced_filter" style="">
	    	<div class="input-selector-btn-frame" onclick="adv_show_hide()">進階搜尋
	    		<i class="fa fa-caret-down" aria-hidden="true"></i>
	    	</div>
	    	<div id="advanced_filter">
	    		<div class="row my-3 mb-5">
	    			<div class="col-lg-1">依時代</div>
	    			<div class="col-lg-11"><input id="filming_date" type="text"></div>
	    		</div>
	    		<div class="row my-4">
	    			<div class="col-lg-1">依作品</div>
		    		<div class="col-lg-11">
		    			<?php foreach ($distinct_object_name as $key => $value) {?>
		    				<div class="d-inline-block">
			    				<div class="tiffany_checkbox">
		                            <input type="checkbox" class="object_name" name="object_name" value="<?=$value['distinct_object_name']?>" <?=isset($_GET["object_name"]) && in_array($value['distinct_object_name'],explode(",",$_GET["object_name"]))?"checked":""?> onchange="adv_checkbox(this)">
		                            <span class="checkmark"></span>		                            
		                        </div>
		                        <div class="d-inline-block mx-2"><?=$value['distinct_object_name']?></div>
	                        </div>
		    			<?php }?>		    			
		    		</div>
		    	</div>
	    		<div class="row my-4">
	    			<div class="col-lg-1">依類別</div>
	    			<div class="col-lg-11">
	    				<?php foreach ($category_data as $key => $value) {?>
		    				<div class="d-inline-block">
			    				<div class="tiffany_checkbox">
		                            <input type="checkbox" class="category_id" name="category_id" value="<?=$value['category_id']?>" <?=isset($_GET["category_id"]) && in_array($value['category_id'],explode(",",$_GET["category_id"]))?"checked":""?> onchange="adv_checkbox(this)">
		                            <span class="checkmark"></span>		                            
		                        </div>
		                        <div class="d-inline-block mx-2"><?=$value['child_name']?></div>
	                        </div>
		    			<?php }?>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	</form>
	<!-- Search Bar -- End -->
	<input type="hidden" id="page" value="<?=isset($_GET['page'])?$_GET['page']:1?>">
	<div class="col-lg-12" id="image_result"></div>		
</div>
<div class="row my-5" id="page_selection"></div>
<div class="to_top"><span class="tri_up"></span><span class="tri_up_underline"></span></div>
<script type="text/javascript">
	function search(){
	    var keyword = $("#keyword").val();
	    var page = $("#page").val();
	    if(keyword != '' && page >0){
	      $('#keyword_search').attr('action',"<?php echo Yii::app()->createUrl('site/search');?>/" + keyword + "/" + page);
	      $('#keyword_search').submit();
	    }   
	}
	function open_image_info(a,single_id){
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
	function adv_checkbox(a){
		var query_name = $(a).attr('name');
		var class_name = $(a).attr('class');
		var checkedValue = new Array();
		$('.' + class_name + ':checked[name="'+query_name+'"]').each(function(i) { checkedValue[i] = this.value; });
		if(location.href.indexOf('?') < 0){//辨別網址是否帶get參數
			$('#keyword_search').attr('action', location.href + "?" + query_name + "=" + checkedValue);
		}else{
			var query = window.location.search.substring(1);
			var query_string = parse_query_string(query,query_name,checkedValue);
			query_string = Object.keys(query_string).map(function(key) {
			  return [key + "=" + query_string[key]];
			});
	    	$('#keyword_search').attr('action',location.href.split("?")[0]+"?"+query_string.join("&"));
		}
	    
  		$('#keyword_search').submit();
	}

	function adv_show_hide(){
		if($('#advanced_filter').css('display') === 'block'){
			$('#advanced_filter').fadeOut('fast');
		}else{
			$('#advanced_filter').fadeIn();
		}
	}

	function create_image(value){
		$html = '<div onclick="open_image_info(this,\''+value.single_id+'\')"><img src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL?>'+value.single_id+'.jpg"><div>';
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
    function parse_query_string(query,query_name,query_value) {
    	var vars = query.split("&");
		var query_string = [];
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            var key = decodeURIComponent(pair[0]);
            var value = decodeURIComponent(pair[1]);
            query_string[key] = decodeURIComponent(value);
        }
        if(query_name in query_string){
        	query_string[query_name] = decodeURIComponent(query_value);
        }else{
        	query_string[query_name] = decodeURIComponent(query_value);
        }
        return query_string;
    }
  	$(document).ready( function() {
  		$( ".to_top" ).click(function() {
			// document.body.scrollTop = 0; // For Safari
  	// 		document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  			$("html, body").animate({ scrollTop: 0 }, "slow");
		});
  		$("#filming_date").slider({
		  // the id of the slider element
			id: "",
		  // minimum value
			min: <?=$filming_date_range['filming_date_range'][0]?>,
		  // maximum value
			max: <?=$filming_date_range['filming_date_range'][count($filming_date_range['filming_date_range'])-1]?>,
		  // increment step
			step: 10,
		  // the number of digits shown after the decimal.
			precision: 0,
		  // 'horizontal' or 'vertical'
			orientation: 'horizontal',
		  // initial value
			value: [<?=isset($_GET['filming_date'])?explode('-',$_GET['filming_date'])[0]:$filming_date_range['filming_date_range'][0]?>,<?=isset($_GET['filming_date'])?explode('-',$_GET['filming_date'])[1]:$filming_date_range['filming_date_range'][count($filming_date_range['filming_date_range'])-1]?>],
		  // enable range slider
			range: true,
			ticks: <?=json_encode($filming_date_range['filming_date_range'])?>,
		    ticks_labels: <?=json_encode($filming_date_range['filming_date_range'])?>,
		    ticks_positions: <?=json_encode($filming_date_range['ticks_positions'])?>,     
		    //ticks_snap_bounds: 30
		  
		});

	  	$('#filming_date').slider().change(function(event, ui) {
	  		var filming_date_range = event.value.newValue[0] + "-" + event.value.newValue[1];
			if(location.href.indexOf('?') < 0){//辨別網址是否帶get參數
				$('#keyword_search').attr('action',location.href+"?filming_date="+filming_date_range);
			}else{
				var query = window.location.search.substring(1);
				var query_string = parse_query_string(query, "filming_date", filming_date_range);
                query_string = Object.keys(query_string).map(function(key) {
					return [key + "=" + query_string[key]];
				});
		    	$('#keyword_search').attr('action',location.href.split("?")[0]+"?"+query_string.join("&"));
			}
		    
      		$('#keyword_search').submit();
		});

	    $('#page_selection').twbsPagination({
	        totalPages: <?=$total_result != 0 ?$total_result:1?>,
	        visiblePages: 10,
	        first: '第一頁',
	        prev: '上一頁',
	        next: '下一頁',
	        last: '最後一頁',
	        startPage: <?=isset($_GET['page'])?$_GET['page']:1?>,
	        onPageClick: function (event, page) {
	            $('#page').val(page);
	            $.ajax({  
	                url: "<?php echo Yii::app()->createUrl('site/findphoto')?>",  
	                type: "GET",  
	                dataType: "json",  
	                data: {
	                	page: page,
	                	keyword: "<?=$_GET['keyword']?>",
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

  	});
</script>