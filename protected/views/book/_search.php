<?php
/* @var $this BookController */
/* @var $model Book */
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
		<?php echo $form->label($model,'book_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_id', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'single_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'single_id', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'book_num', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_num',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'category', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'category', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'book_name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_name',array('size'=>32,'maxlength'=>32,'class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'author_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'author_id', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'sub_author_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'sub_author_id',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'publish_place', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'publish_place', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'publish_organization', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'publish_organization', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'publish_year', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'publish_year', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'publish_month', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'publish_month', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'publish_day', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'publish_day', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'book_version', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_version',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'book_pages', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_pages', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'book_size', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_size',array('size'=>32,'maxlength'=>32,'class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'series', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'series', array('class'=>'form-control')); ?>
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

	<div class="form-group">
		<?php echo $form->label($model,'last_updated_user', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'last_updated_user', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton('搜尋', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->