<?php
/* @var $this BookauthorController */
/* @var $model BookAuthor */
/* @var $form CActiveForm */
?>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/bootstrap-datepicker.css" rel="stylesheet">
<style>
.media-body, .media-left, .media-right{
	vertical-align: middle;
}
.media-object,.data_thumbnail {
	max-width: 100px;
    /* max-height: 100px; */
}
.bootstrap-select{
	height: auto;
}
</style>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-author-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'class'=>'form-horizontal',
    )
)); 
$status = array(
	// "-1" => "刪除",
	"1" => "啟用",
	"0" => "停用",
);
$gender = array(
	// "-1" => "刪除",
	"F" => "女",
	"M" => "男",
);
?>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-8">
			<p class="note"><span class="required">*</span>表示為必填欄位</p>
		</div>
	</div>
	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'single_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<select class="form-control single_id" name="BookAuthor[single_id]" required="required">
                <option value="">請選擇</option>
                <?php foreach ($single as $key => $value){
                	$data_tokens = array();
                	array_push($data_tokens, $value['single_id']);
                	if(!empty($value['keyword'])) array_push($data_tokens, $value['keyword']);
                	if(!empty($value['people_info'])) array_push($data_tokens, $value['people_info']);
                	if(!empty($value['event_name'])) array_push($data_tokens, $value['event_name']);
                	if(!empty($value['filming_location'])) array_push($data_tokens, $value['filming_location']);
                	if(!empty($value['filming_date'])) array_push($data_tokens, $value['filming_date']);
                	if(!empty($value['filming_name'])) array_push($data_tokens, $value['filming_name']);
                	if(!empty($value['object_name'])) array_push($data_tokens, $value['object_name']);
                	if(!empty($value['people_info'])) array_push($data_tokens, $value['people_info']);
                	if(!empty($value['description'])) array_push($data_tokens, $value['description']);
                	if(!empty($value['photo_source'])) array_push($data_tokens, $value['photo_source']);
                	if(!empty($value['filming_date_text'])) array_push($data_tokens, $value['filming_date_text']);
                ?>
                    <option value="<?=$value['single_id']?>" data-tokens="<?=implode(",",$data_tokens)?>" data-content="<img class='data_thumbnail lazyload' width='100' height='auto' data-src='<?=DOMAIN."image_storage/P/".$value['single_id']?>.jpg' data-original='<?=DOMAIN."image_storage/P/".$value['single_id']?>.jpg'></img> <?=$value['single_id']?>" <?=$model->single_id==$value['single_id']?'selected':''?> <?=$model->single_id==$value['single_id']?'selected':''?>><?=$value['single_id']?></option>
                <?php }?>
            </select>
		</div>
		<?php echo $form->error($model,'single_id'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'gender', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->dropDownList($model,'gender',$gender, array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'gender'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'summary', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'summary',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'summary'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'memo', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'memo',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'memo'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'status', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->dropDownList($model,'status',$status, array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'original_name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'original_name',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'original_name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'hometown', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'hometown',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'hometown'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'birth_year', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<input type="text" id="birth_year" size="4", maxlength="4" name="BookAuthor[birth_year]" required="required" data-date-format="yyyy" class="form-control datepicker" value="<?=$model->birth_year?>" placeholder="出生年">
		</div>
		<?php echo $form->error($model,'birth_year'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'birth_month', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<input type="text" id="birth_month" size="2", maxlength="2" name="BookAuthor[birth_month]" data-date-format="mm" class="form-control datepicker" value="<?=$model->birth_month?>" placeholder="出生月">
		</div>
		<?php echo $form->error($model,'birth_month'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'birth_day', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<input type="text" id="birth_day" size="2", maxlength="2" name="BookAuthor[birth_day]" data-date-format="dd" class="form-control datepicker" value="<?=$model->birth_day?>" placeholder="出生日">
		</div>
		<?php echo $form->error($model,'birth_day'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'arrive_time', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'arrive_time',array('size'=>50,'maxlength'=>50,'class'=>'form-control datepicker','data-date-format'=>'yyyy-mm')); ?>
		</div>
		<?php echo $form->error($model,'arrive_time'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'experience', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'experience',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'experience'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'literary_style', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'literary_style',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'literary_style'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'literary_achievement', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'literary_achievement',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'literary_achievement'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'year_of_death', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'year_of_death',array('size'=>4,'maxlength'=>4,'class'=>'form-control datepicker','data-date-format'=>'yyyy')); ?>
		</div>
		<?php echo $form->error($model,'year_of_death'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'year_of_month', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'year_of_month',array('size'=>2,'maxlength'=>2,'class'=>'form-control datepicker','data-date-format'=>'mm')); ?>
		</div>
		<?php echo $form->error($model,'year_of_month'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'year_of_day', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'year_of_day',array('size'=>4,'maxlength'=>4,'class'=>'form-control datepicker','data-date-format'=>'dd')); ?>
		</div>
		<?php echo $form->error($model,'year_of_day'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'pen_name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'pen_name',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'pen_name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'literary_genre', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<input type="hidden" id="literary_genre" name="BookAuthor[literary_genre]" value="<?=$model->literary_genre?>">
            <div id="tree"></div>
		</div>
		<?php echo $form->error($model,'literary_genre'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'present_job', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'present_job',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'present_job'); ?>
	</div>
	<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
		<div class="panel-heading">
			<h3 class="panel-title">作者年表</h3>
		</div>
        <div class="panel-body" id="book_author_event">
        	<?php
        		if($model_author_event->isNewRecord){
	    			$i = 1;
	    		}else{
	    			$i = count($model_author_event)+1;
	    		}
	    	?>
	    	<div class="row text-center">
	    		<button type="button" class="btn btn-primary btn-lg" onclick="addGallery('<?=$i?>',this)">增加</button>
	    	</div>
        	<?php	
	        	if($model_author_event->isNewRecord){
	        		$this->renderPartial('pages/model_author_event', array("model_author_event"=>$model_author_event, "single" => $single, "i" => 0, "form" => $form ));
	        	}else{
	        		foreach ($model_author_event as $key => $value) {
	        			$this->renderPartial('pages/model_author_event', array("model_author_event"=>$value, "single" => $single, "i" => $key, "form" => $form ));
	        		}
	        	}
		    ?>
        </div>
    </div>
	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-datepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-treeview.js"></script>
<script type="text/javascript">
	function delGallery(my) {
        $(my).parent("div").parent("div").parent("div").remove();
    }
	function addGallery(add_no,my) {
		if($('#title_'+(add_no-1)).val() == '' || $('#description_'+(add_no-1)).val() == '' || $('#image_link_'+(add_no-1)).val() == '' || $('#year_'+(add_no-1)).val() == '' ){
			alert("事件標題、事件說明、圖庫圖片與年表-年皆為必填欄位");
			return;
		}
        var html = '<div class="panel panel-default" style="width=100%; overflow-y:scroll;">\
			<div class="panel-body">\
				<div class="form-group text-center">\
					<button type="button" class="btn btn-danger btn-lg" onclick="delGallery(this)">取消</button>\
				</div>\
				<div class="form-group">\
					<label class="col-sm-3 control-label" for="BookAuthorEvent_title">事件標題</label>\
					<div class="col-sm-8">\
						<input type="text" id="title" size="200" ,="" maxlength="200" name="BookAuthorEvent[title][]" class="form-control" value="">\
					</div>\
				</div>\
				<div class="form-group">\
					<label class="col-sm-3 control-label" for="BookAuthorEvent_description">事件說明</label>\
					<div class="col-sm-8">\
						<textarea rows="6" cols="50" class="form-control" name="BookAuthorEvent[description][]"></textarea>\
					</div>\
				</div>\
				<div class="form-group">\
					<label class="col-sm-3 control-label" for="BookAuthorEvent_image_link">圖庫圖片</label>\
					<div class="col-sm-8">\
						<select class="form-control image_link" name="BookAuthorEvent[image_link][]">\
			                <option value="">請選擇</option>';
			                <?php foreach ($single as $key => $value){
			                	$data_tokens = array();
			                	array_push($data_tokens, $value['single_id']);
			                	if(!empty($value['keyword'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['keyword'])),ENT_QUOTES);
			                	if(!empty($value['people_info'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['people_info'])),ENT_QUOTES);
			                	if(!empty($value['event_name'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['event_name'])),ENT_QUOTES);
			                	if(!empty($value['filming_location'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['filming_location'])),ENT_QUOTES);
			                	if(!empty($value['filming_date'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['filming_date'])),ENT_QUOTES);
			                	if(!empty($value['filming_name'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['filming_name'])),ENT_QUOTES);
			                	if(!empty($value['object_name'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['object_name'])),ENT_QUOTES);
			                	if(!empty($value['people_info'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['people_info'])),ENT_QUOTES);
			                	if(!empty($value['description'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['description'])),ENT_QUOTES);
			                	if(!empty($value['photo_source'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['photo_source'])),ENT_QUOTES);
			                	if(!empty($value['filming_date_text'])) array_push($data_tokens, htmlspecialchars(str_replace(array("'", "\"", "\n", "\r\n", "\r", "\t"), "",$value['filming_date_text'])),ENT_QUOTES);
			                ?>
			                html += '<option value="<?=$value['single_id']?>" data-tokens="<?=implode(",",$data_tokens)?>" data-content="<img class=\"data_thumbnail lazyload\" width=\"100\" height=\"auto\" data-src=\"<?=DOMAIN."image_storage/P/".$value['single_id']?>.jpg\" data-original=\"<?=DOMAIN."image_storage/P/".$value['single_id']?>.jpg\"></img> <?=$value['single_id']?>"><?=$value['single_id']?></option>';
			                <?php }?>
		html += '		</select>\
					</div>\
				</div>\
				<div class="form-group">\
					<label class="col-sm-3 control-label" for="BookAuthorEvent_year">年</label>\
					<div class="col-sm-8">\
						<input type="text" id="year" size="4" ,="" maxlength="4" name="BookAuthorEvent[year][]" data-date-format="yyyy" class="form-control datepicker" value="" placeholder="年表-年">\
					</div>\
				</div>\
				<div class="form-group">\
					<label class="col-sm-3 control-label" for="BookAuthorEvent_month">月</label>\
					<div class="col-sm-8">\
						<input type="text" id="month" size="2" ,="" maxlength="2" name="BookAuthorEvent[month][]" data-date-format="mm" class="form-control datepicker" value="" placeholder="年表-月">\
					</div>\
				</div>\
				<div class="form-group">\
					<label class="col-sm-3 control-label" for="BookAuthorEvent_day">日</label>\
					<div class="col-sm-8">\
						<input type="text" id="day" size="2" ,="" maxlength="2" name="BookAuthorEvent[day][]" data-date-format="dd" class="form-control datepicker" value="" placeholder="年表-日">\
					</div>\
				</div>\
			</div>\
		</div>';
        // $("#"+add_id).append(html);
        $(my).parent("div").append(html);
        <?php $i++;?>
    }
	$( document ).ready(function() {

		$('.single_id').selectpicker({
			size: 10,
			virtualScroll:false
		});
		$(".single_id").on("shown.bs.select",function(e, clickedIndex, newValue, oldValue) {
			if (e.currentTarget.firstChild.childNodes.length>0) {
				$("img.lazyload").lazyload({container: e.currentTarget.children[2].firstChild,skip_invisible : false});
			}
		});
		$(".single_id").on("change.bs.select",function(e, clickedIndex, isSelected, previousValue) {
			$(".filter-option-inner-inner img.lazyload").attr("src",$(".filter-option-inner-inner img.lazyload").data('original'));
		});

		$('.image_link').selectpicker({
			size: 10,
			virtualScroll:false
		});
		$(".image_link").on("shown.bs.select",function(e) {
			if (e.currentTarget.firstChild.childNodes.length>0) {
				$("img.lazyload").lazyload({container: e.currentTarget.children[2].firstChild,skip_invisible : false});
			}
		});
		$(".image_link").on("change.bs.select",function(e, clickedIndex, isSelected, previousValue) {
			$(".filter-option-inner-inner img.lazyload").attr("src",$(".filter-option-inner-inner img.lazyload").data('original'));
		});
		function getCheckedItems(treeview){
            var nodes = $('#tree').treeview('getChecked', treeview);
            var checkedNodes = [];
            for (var i = 0; i < nodes.length; i++) {
                node = nodes[i];
                checkedNodes.push(node.category_id);
            }
            $('#category_id').val(checkedNodes.join());
        }
        

		$('.datepicker').datepicker();
		$('#tree').treeview({
            data: '<?=$book_category?>',
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
	})
</script>