<?php
/* @var $this BookseriesController */
/* @var $data BookSeries */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_series_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->book_series_id), array('view', 'id'=>$data->book_series_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_at')); ?>:</b>
	<?php echo CHtml::encode($data->create_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_at')); ?>:</b>
	<?php echo CHtml::encode($data->update_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delete_at')); ?>:</b>
	<?php echo CHtml::encode($data->delete_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_updated_user')); ?>:</b>
	<?php echo CHtml::encode($data->last_updated_user); ?>
	<br />


</div>