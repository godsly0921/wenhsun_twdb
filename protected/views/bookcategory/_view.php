<?php
/* @var $this BookcategoryController */
/* @var $data BookCategory */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('category_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->category_id), array('view', 'id'=>$data->category_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isroot')); ?>:</b>
	<?php echo CHtml::encode($data->isroot); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parents')); ?>:</b>
	<?php echo CHtml::encode($data->parents); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sort')); ?>:</b>
	<?php echo CHtml::encode($data->sort); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_at')); ?>:</b>
	<?php echo CHtml::encode($data->create_at); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('update_at')); ?>:</b>
	<?php echo CHtml::encode($data->update_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delete_at')); ?>:</b>
	<?php echo CHtml::encode($data->delete_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_updated_user')); ?>:</b>
	<?php echo CHtml::encode($data->last_updated_user); ?>
	<br />

	*/ ?>

</div>