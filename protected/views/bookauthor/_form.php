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
)); 
$gender = array(
	"F" => "小姐",
	"M" => "先生",
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
		<?php echo $form->labelEx($model,'birthday', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php
				$this->widget('luckywave.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'birthday',
			        'value'=>$model->birthday,
					//additional javascript options for the date picker plugin
					'options'=>array(
						'dateFormat'=>'yy-mm-dd',
						'showAnim'=>'fold',
			            'debug'=>true,
						'datepickerOptions'=>array('changeMonth'=>true, 'changeYear'=>true),
					),
					'htmlOptions'=>array('class'=>'form-control'),
				)); 
			?>
		</div>
		<?php echo $form->error($model,'birthday'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'gender', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->dropDownList($model,'gender', $gender, array('class'=>'form-control')); ?>
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

	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->