<?php
/* @var $this BookController */
/* @var $model Book */
/* @var $form CActiveForm */
?>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/bootstrap-datepicker.css" rel="stylesheet">
<style>
.media-body, .media-left, .media-right{
	vertical-align: middle;
}
.media-object,.data_thumbnail {
	max-width: 100px;
    max-height: 100px;
}
</style>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
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
?>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-8">
			<p class="note"><span class="required">*</span>表示為必填欄位</p>
		</div>
	</div>
	<?php echo $form->errorSummary($model); ?>
	<!-- <div class="form-group">
        <label for="adv_id" class="col-sm-2 control-label">書本圖片</label>
        <div class="col-sm-5">
            <?php #if ($model->book_image !== ""):; ?>
                <input class="form-control" name="book_image" type="text"
                       value="<?#= $model->book_image ?>" readonly="readonly">
                <input type="file" class="form-control-file" id="book_image_new" name="book_image_new" placeholder="書本圖片" value="" onchange="checkImage(this)">
                <img src="<?#=Yii::app()->createUrl('/') . "/" . $model->book_image ?>" width="200">
            <?php #else:; ?>
                <input type="file" class="form-control-file" id="book_image" name="book_image" placeholder="書本圖片" value="" required onchange="checkImage(this)">
            <?php #endif; ?>
        </div>
        <div class="col-sm-4"><span style="color:red;">圖片長寬需為 975 * 370</span></div>
    </div> -->
    <div class="form-group">
    	<label class="col-sm-3 control-label required" for="book_num">書本編號 
			<span class="required">*</span>
		</label>
	    <div class="col-sm-8">
			<div class="input-group">
				<span class="input-group-addon" id="book_num_perfix">B</span>
  				<input type="text" class="form-control" id="book_num" name="Book[book_num]" aria-describedby="book_num_perfix" placeholder="書本編號" value="<?=$model->book_num?>">
			</div>
		</div>
		<?php echo $form->error($model,'book_num'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="book_name">書名 
			<span class="required">*</span>
		</label>
		<div class="col-sm-8">
			<input type="text" id="book_name" name="Book[book_name]" required="required" class="form-control" value="<?=$model->book_name?>" placeholder="書名">
		</div>
		<?php echo $form->error($model,'book_name'); ?>
	</div>	
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="book_version">版本 
			<span class="required">*</span>
		</label>
		<div class="col-sm-8">
			<input type="text" id="book_version" name="Book[book_version]" required="required" class="form-control" value="<?=$model->book_version?>" placeholder="版本">
		</div>
		<?php echo $form->error($model,'book_version'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="book_pages">頁數 
			<span class="required">*</span>
		</label>
		<div class="col-sm-8">
			<input type="text" id="book_pages" name="Book[book_pages]" required="required" class="form-control" value="<?=$model->book_pages?>" placeholder="頁數">
		</div>
		<?php echo $form->error($model,'book_pages'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'status', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->dropDownList($model,'status',$status, array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'status'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="publish_year">出版日期(年) 
			<span class="required">*</span>
		</label>
		<div class="col-sm-8">
			<input type="text" id="publish_year" name="Book[publish_year]" required="required" data-date-format="yyyy" class="form-control datepicker" value="<?=$model->publish_year?>" placeholder="出版日期(年)">
		</div>
		<?php echo $form->error($model,'publish_year'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="publish_month">出版日期(月) 
			<span class="required"></span>
		</label>
		<div class="col-sm-8">
			<input type="text" id="publish_month" name="Book[publish_month]" data-date-format="mm" class="form-control datepicker" value="<?=$model->publish_month?>" placeholder="出版日期(月)">
		</div>
		<?php echo $form->error($model,'publish_month'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="publish_day">出版日期(日) 
			<span class="required"></span>
		</label>
		<div class="col-sm-8">
			<input type="text" id="publish_day" name="Book[publish_day]" data-date-format="dd" class="form-control datepicker" value="<?=$model->publish_day?>" placeholder="出版日期(日)">
		</div>
		<?php echo $form->error($model,'publish_day'); ?>
	</div>
	<div class="form-group">
        <label class="col-sm-3 control-label required" for="category">文類
			<span class="required">*</span>
        </label>
        <div class="col-sm-5">
        	<input type="hidden" id="category_id" name="Book[category]" value="<?=$model->category?>">
            <div id="tree"></div>
        </div>
    </div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="single_id">圖庫圖片 
			<span class="required">*</span>
		</label>
		<div class="col-sm-8">
			<select class="form-control selectpicker"  id="single_id" name="Book[single_id]" required="required" data-live-search="true">
                <option value="">請選擇</option>
                <?php foreach ($FK_data['singles'] as $value){
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
                    <option value="<?=$value['single_id']?>" data-tokens="<?=implode(",",$data_tokens)?>" data-content="<img class='data_thumbnail' src='<?=DOMAIN."image_storage/P/".$value['single_id']?>.jpg'></img> <?=$value['single_id']?>" <?=$model->single_id==$value['single_id']?'selected':''?> <?=$model->single_id==$value['single_id']?'selected':''?>><?=$value['single_id']?></option>
                <?php }?>
            </select>
		</div>
		<?php echo $form->error($model,'single_id'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="author_id">主作家 
			<span class="required">*</span>
		</label>
		<div class="col-sm-8">
			<select class="form-control selectpicker"  id="author_id" name="Book[author_id]" required="required" data-live-search="true">
                <option value="">請選擇</option>
                <?php foreach ($FK_data['book_author'] as $value){?>
                    <option value="<?=$value['author_id']?>" data-tokens="<?=$value['author_id'] . '-' . $value['name']?>" <?=$model->author_id==$value['author_id']?'selected':''?>><?=$value['author_id'] . '-' . $value['name']?></option>
                <?php }?>
            </select>
		</div>
		<?php echo $form->error($model,'author_id'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="author_id">次要作者(可多選) 
			<span class="required"></span>
		</label>
		<div class="col-sm-8">
			<select multiple class="form-control selectpicker" title="請選擇" id="sub_author_id" name="Book[sub_author_id][]" data-live-search="true">
                <?php foreach ($FK_data['book_author'] as $value){?>
                    <option value="<?=$value['author_id']?>" data-tokens="<?=$value['author_id'] . '-' . $value['name']?>" <?= in_array($value['author_id'],$model->sub_author_id)?'selected':''?>><?=$value['author_id'] . '-' . $value['name']?></option>
                <?php }?>
            </select>
		</div>
		<?php echo $form->error($model,'sub_author_id'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="author_id">出版地點
			<span class="required">*</span>
		</label>
		<div class="col-sm-8">
			<select class="form-control selectpicker" id="publish_place" name="Book[publish_place]" required="required" data-live-search="true">
				<option value="">請選擇</option>
                <?php foreach ($FK_data['book_publish_place'] as $value){?>
                    <option value="<?=$value['publish_place_id']?>" data-tokens="<?=$value['publish_place_id'] . '-' . $value['name']?>" <?=$model->publish_place==$value['publish_place_id']?'selected':''?>><?=$value['publish_place_id'] . '-' . $value['name']?></option>
                <?php }?>
            </select>
		</div>
		<?php echo $form->error($model,'publish_place'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="publish_organization">出版單位｜組織
			<span class="required">*</span>
		</label>
		<div class="col-sm-8">
			<select class="form-control selectpicker" id="publish_organization" name="Book[publish_organization]" required="required" data-live-search="true">
				<option value="">請選擇</option>
                <?php foreach ($FK_data['book_publish_unit'] as $value){?>
                    <option value="<?=$value['publish_unit_id']?>" data-tokens="<?=$value['publish_unit_id'] . '-' . $value['name']?>" <?=$model->publish_organization==$value['publish_unit_id']?'selected':''?>><?=$value['publish_unit_id'] . '-' . $value['name']?></option>
                <?php }?>
            </select>
		</div>
		<?php echo $form->error($model,'publish_organization'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="book_size">開本規格
			<span class="required">*</span>
		</label>
		<div class="col-sm-8">
			<select class="form-control selectpicker" id="book_size" name="Book[book_size]" required="required" data-live-search="true">
				<option value="">請選擇</option>
                <?php foreach ($FK_data['book_size'] as $value){?>
                    <option value="<?=$value['book_size_id']?>" data-tokens="<?=$value['book_size_id'] . '-' . $value['name']?>" <?=$model->book_size==$value['book_size_id']?'selected':''?>><?=$value['book_size_id'] . '-' . $value['name']?></option>
                <?php }?>
            </select>
		</div>
		<?php echo $form->error($model,'book_size'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="series">叢書名
			<span class="required">*</span>
		</label>
		<div class="col-sm-8">
			<select class="form-control selectpicker" id="series" name="Book[series]" required="required" data-live-search="true">
				<option value="">請選擇</option>
                <?php foreach ($FK_data['book_series'] as $value){?>
                    <option value="<?=$value['book_series_id']?>" data-tokens="<?=$value['book_series_id'] . '-' . $value['name']?>" <?=$model->series==$value['book_series_id']?'selected':''?>><?=$value['book_series_id'] . '-' . $value['name']?></option>
                <?php }?>
            </select>
		</div>
		<?php echo $form->error($model,'series'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="summary">簡介 
			<span class="required">*</span>
		</label>		
		<div class="col-sm-8">
			<textarea rows="6" cols="50" class="form-control" name="Book[summary]" required="required" id="summary" value="<?=$model->summary?>" placeholder="簡介"><?=$model->summary?></textarea>		
		</div>
		<?php echo $form->error($model,'summary'); ?>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label required" for="memo">備註 
			<span class="required"></span>
		</label>		
		<div class="col-sm-8">
			<textarea rows="6" cols="50" class="form-control" name="Book[memo]" id="memo" value="<?=$model->memo?>" placeholder="備註"><?=$model->memo?></textarea>		
		</div>
		<?php echo $form->error($model,'memo'); ?>
	</div>
	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-select/dist/js/bootstrap-select.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-datepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-treeview.js"></script>
<script type="text/javascript">
	$(function () {
		function getCheckedItems(treeview){
            var nodes = $('#tree').treeview('getChecked', treeview);
            var checkedNodes = [];
            for (var i = 0; i < nodes.length; i++) {
                node = nodes[i];
                checkedNodes.push(node.category_id);
            }
            $('#category_id').val(checkedNodes.join());
        }
		$('.selectpicker').selectpicker();
		$('.datepicker').datepicker();
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
	});
</script>