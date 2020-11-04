<?php
/* @var $this BookauthorController */
/* @var $model BookAuthor */
/* @var $form CActiveForm */
?>

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
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'birthday', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'birthday', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'birthday'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'gender', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'gender',array('size'=>1,'maxlength'=>1,'class'=>'form-control')); ?>
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
		<?php echo $form->labelEx($model,'status', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'status', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'last_updated_user', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'last_updated_user', array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'last_updated_user'); ?>
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
			<?php echo $form->textField($model,'birth_year',array('size'=>4,'maxlength'=>4,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'birth_year'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'birth_month', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'birth_month',array('size'=>2,'maxlength'=>2,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'birth_month'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'bitrh_day', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'bitrh_day',array('size'=>2,'maxlength'=>2,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'bitrh_day'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'arrive_time', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'arrive_time',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
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
			<?php echo $form->textField($model,'year_of_death',array('size'=>4,'maxlength'=>4,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'year_of_death'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'year_of_month', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'year_of_month',array('size'=>2,'maxlength'=>2,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'year_of_month'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'year_of_day', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'year_of_day',array('size'=>4,'maxlength'=>4,'class'=>'form-control')); ?>
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
			<?php echo $form->textField($model,'literary_genre',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
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

	<div class="form-group">
		<?php echo $form->labelEx($model,'brief_intro', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'brief_intro',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'brief_intro'); ?>
	</div>

	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->