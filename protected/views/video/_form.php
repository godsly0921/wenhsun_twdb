<?php
/* @var $this VideoController */
/* @var $model Video */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'video-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'class'=>'form-horizontal',
		"enctype"=>"multipart/form-data"
    )
)); ?>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-8">
			<p class="note"><span class="required">*</span>表示為必填欄位</p>
		</div>
	</div>
	<?php echo $form->errorSummary($model); ?>
	<div class="form-group">
        <label for="adv_id" class="col-sm-3 control-label">影片</label>
        <div class="col-sm-8">
            <?php if (!empty($model->m3u8_url)):; ?>
                <input class="form-control" name="m3u8_url" type="text"
                       value="<?= $model->m3u8_url ?>" readonly="readonly">
                <input type="file" class="form-control-file" id="m3u8_url_new" name="m3u8_url_new" placeholder="影片" value="" accept=".mp4,.MP4">
                <video width="200" controls>
					<source src="<?= $model->m3u8_url ?>" type="video/mp4">
				</video>
            <?php else:; ?>
                <input type="file" class="form-control-file" id="m3u8_url" name="m3u8_url" placeholder="影片" value="" required accept=".mp4,.MP4">
            <?php endif; ?>
        </div>
    </div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'status', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'status', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'description', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'description'); ?>
	</div>
	<div class="form-group">
        <label class="col-sm-3 control-label required" for="category">文類
			<span class="required">*</span>
        </label>
        <div class="col-sm-5">
        	<input type="hidden" id="category_id" name="Video[category]" value="<?=$model->category?>">
            <div id="tree"></div>
        </div>
    </div>

	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
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
        $('#tree').treeview({
            data: '<?=$category_data?>',
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