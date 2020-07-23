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
)); 
$gender = array(
	"F" => "小姐",
	"M" => "先生",
);
$status = array(
	// "-1" => "刪除",
	"1" => "啟用",
	"0" => "停用",
);
?>

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
	</div>
	<div class="form-group">
		<?php echo $form->label($model,'status', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->dropDownList($model,'status', $status, array('class'=>'form-control')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->label($model,'summary', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-8">
			<?php echo $form->textArea($model,'summary',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		</div>
	</div>
	<div class="form-group buttons">
		<div class="col-sm-offset-3 col-sm-8">
			<?php echo CHtml::submitButton('搜尋', array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->