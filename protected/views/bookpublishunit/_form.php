<?php
/* @var $this BookpublishunitController */
/* @var $model BookPublishUnit */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-publish-unit-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'class'=>'form-horizontal',
    )
)); ?>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-8">
			<p class="note"><span class="required">*</span>表示為必填欄位</p>
		</div>
	</div>
	<?php echo $form->errorSummary($model); ?>

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
		<?php echo $form->labelEx($model,'create_at', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'create_at', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'create_at'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'update_at', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'update_at', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'update_at'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'delete_at', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'delete_at', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'delete_at'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'last_updated_user', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'last_updated_user', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'last_updated_user'); ?>
	</div>

	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->