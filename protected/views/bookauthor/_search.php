<?php
/* @var $this BookauthorController */
/* @var $model BookAuthor */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions'=>array(
		'class'=>'form-horizontal',
    )
)); ?>

	<div class="form-group">
		<?php echo $form->label($model,'author_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'author_id', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'birthday', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'birthday', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'gender', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'gender', array('class'=>'form-control')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->label($model,'status', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'status', array('class'=>'form-control')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->label($model,'summary', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'summary',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'memo', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'memo',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'create_at', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'create_at', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'update_at', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'update_at', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'delete_at', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'delete_at', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton('搜尋', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->