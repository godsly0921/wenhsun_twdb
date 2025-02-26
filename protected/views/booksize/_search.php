<?php
/* @var $this BooksizeController */
/* @var $model BookSize */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
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
		<?php echo $form->label($model,'book_size_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'book_size_id', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'status', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->dropDownList($model,'status',$status, array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton('搜尋', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->