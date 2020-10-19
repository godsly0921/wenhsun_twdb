<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
<style type="text/css">
	#keyword:focus{
	    z-index: 0;
	  }

</style>
<!-- Search Bar -- Start -->
<form name="group_form" class="col-lg-12 form-horizontal" id="keyword_search" action="<?php echo Yii::app()->createUrl('site/search');?>" method="post">
    <div class="mx-auto input-group input-group-lg my-2">
      	<input type="text" class="form-control" placeholder="推薦關鍵字：洛夫" aria-label="推薦關鍵字：洛夫" aria-describedby="basic-addon2" name="keyword" id="keyword" value="<?=$_GET['keyword']?>" required>
      	<select class="form-control w-auto advanced_filter" id="search_type" name="search_type" style="flex: none !important">
	        <option value="1" <?=$_GET['search_type']=='1'?"selected":""?>>圖片</option>
	        <option value="2" <?=$_GET['search_type']=='2'?"selected":""?>>書籍</option>
	        <option value="3" <?=$_GET['search_type']=='3'?"selected":""?>>影片</option>
	    </select>
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
    		<?php if($_GET['search_type'] == '1'){?>
    			<div id="search_type_1">
		    		<div class="row my-3 mb-5">
		    			<div class="col-lg-1">依時代</div>
		    			<div class="col-lg-11"><input id="filming_date" type="text"></div>
		    		</div>
		    		<?php if($distinct_object_name){?>
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
			    	<?php }?>
			    	<?php if($category_data){?>
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
		    		<?php }?>
	    		</div>
    		<?php }?>
    		<?php if($_GET['search_type'] == '2'){?>
    			<div id="search_type_2">
    				<?php if(!empty($FK_data['book_author'])){?>
	    				<div class="row my-3 mb-5">
			    			<div class="col-lg-2 my-auto">作者</div>
			    			<div class="col-lg-10">
			    				<select class="form-control selectpicker"  id="author_id" name="author_id" required="required" data-live-search="true">
					                <option value="">請選擇</option>
					                <?php foreach ($FK_data['book_author'] as $value){?>
					                    <option value="<?=$value['author_id']?>" data-tokens="<?=$value['author_id'] . '-' . $value['name']?>" <?=isset($_GET["author_id"]) && $_GET["author_id"]==$value['author_id']?'selected':''?>><?=$value['author_id'] . '-' . $value['name']?></option>
					                <?php }?>
					            </select>
			    			</div>
			    		</div>
		    		<?php }?>
		    		<?php if(!empty($FK_data['book_publish_unit'])){?>
	    				<div class="row my-3 mb-5">
			    			<div class="col-lg-2 my-auto">出版單位</div>
			    			<div class="col-lg-10">
			    				<select class="form-control selectpicker"  id="publish_unit_id" name="publish_unit_id" required="required" data-live-search="true">
					                <option value="">請選擇</option>
					                <?php foreach ($FK_data['book_publish_unit'] as $value){?>
					                    <option value="<?=$value['publish_unit_id']?>" data-tokens="<?=$value['publish_unit_id'] . '-' . $value['name']?>" <?=isset($_GET["publish_unit_id"]) && $_GET["publish_unit_id"]==$value['publish_unit_id']?'selected':''?>><?=$value['publish_unit_id'] . '-' . $value['name']?></option>
					                <?php }?>
					            </select>
			    			</div>
			    		</div>
		    		<?php }?>
		    		<?php if(!empty($FK_data['book_category'])){?>
	    				<div class="row my-3 mb-5">
			    			<div class="col-lg-2 my-auto">作品分類</div>
			    			<div class="col-lg-10">
			    				<input type="hidden" id="category_id" name="category_id" value="<?=isset($_GET["category_id"])?$_GET["category_id"]:''?>">
            					<div id="tree"></div>
			    			</div>
			    		</div>
		    		<?php }?>
    			</div>
    		<?php }?>
    	</div>
    </div>
</form>
<!-- Search Bar -- End -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-select/dist/js/bootstrap-select.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-treeview.js"></script>
<script>
function getCheckedItems(treeview){
    var nodes = $('#tree').treeview('getChecked', treeview);
    var checkedNodes = [];
    for (var i = 0; i < nodes.length; i++) {
        node = nodes[i];
        checkedNodes.push(node.category_id);
    }
    $('#category_id').val(checkedNodes.join());
}
function search(){
    var keyword = $("#keyword").val();
    var page = 1;
    var search_type = $("#search_type").val();
    if(keyword != '' && page >0){
      $('#keyword_search').attr('action',"<?php echo Yii::app()->createUrl('site/search');?>/" + keyword + "/" + page + "/" + search_type);
      $('#keyword_search').submit();
    }   
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
$(function () {
	$('.selectpicker').selectpicker();
	if("<?=(isset($_GET['filming_date']))?>" || "<?=(isset($_GET['object_name']))?>" || "<?=(isset($_GET['category_id']))?>"){
		adv_show_hide();
	}
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
	
	<?php if(!empty($FK_data['book_category'])){?>
		$('#tree').treeview({
	        data: '<?=$FK_data['book_category']?>',
	        showCheckbox: true, //是否顯示覆選框
	        highlightSelected: true, //是否高亮選中
	        multiSelect: true, //多選
	        checkboxFirst: true,
	        onNodeChecked: function(event, data) {
	            if (typeof data['nodes'] != "undefined") {
	                var children = data['nodes'];
	                for (var i=0; i<children.length; i++) {
	                    $('#tree').treeview('checkNode', [children[i].nodeId, { silent: true } ]);
	                }
	            }
	            getCheckedItems(data);
	        },
	        onNodeUnchecked: function(event, data) {
	            if (typeof data['nodes'] != "undefined") {
	                var children = data['nodes'];
	                for (var i=0; i<children.length; i++) {
	                    $('#tree').treeview('uncheckNode', [children[i].nodeId, { silent: true } ]);
	                }
	            }
	            // getParentItems(data);
	            getCheckedItems(data);        
	        },
	    });
	<?php }?>
})
</script>