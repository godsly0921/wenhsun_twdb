<?php
/* @var $this BookController */
/* @var $model Book */
/* @var $form CActiveForm */
?>

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
)); ?>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-8">
			<p class="note"><span class="required">*</span>表示為必填欄位</p>
		</div>
	</div>
	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'single_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'single_id', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'single_id'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'book_num', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_num',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'book_num'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'main_category', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'main_category', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'main_category'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'sub_category', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'sub_category', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'sub_category'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'book_name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_name',array('size'=>32,'maxlength'=>32,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'book_name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'author_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'author_id', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'author_id'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'sub_author_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'sub_author_id', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'sub_author_id'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'publish_place', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'publish_place', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'publish_place'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'publish_organization', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'publish_organization', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'publish_organization'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'publish_date', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'publish_date',array('size'=>4,'maxlength'=>4,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'publish_date'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'book_version_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_version_id', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'book_version_id'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'book_pages', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_pages', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'book_pages'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'book_size', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_size',array('size'=>32,'maxlength'=>32,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'book_size'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'series', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'series', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'series'); ?>
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
		<?php echo $form->labelEx($model,'create_datetime', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'create_datetime', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'create_datetime'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'update_datetime', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'update_datetime', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'update_datetime'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'delete_datetime', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'delete_datetime', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'delete_datetime'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'last_operator', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'last_operator', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'last_operator'); ?>
	</div>

	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->