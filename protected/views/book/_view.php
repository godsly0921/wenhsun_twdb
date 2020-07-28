<?php
/* @var $this BookController */
/* @var $data Book */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->book_id), array('view', 'id'=>$data->book_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('single_id')); ?>:</b>
	<?php echo CHtml::encode($data->single_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_num')); ?>:</b>
	<?php echo CHtml::encode($data->book_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('main_category')); ?>:</b>
	<?php echo CHtml::encode($data->main_category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sub_category')); ?>:</b>
	<?php echo CHtml::encode($data->sub_category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_name')); ?>:</b>
	<?php echo CHtml::encode($data->book_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author_id')); ?>:</b>
	<?php echo CHtml::encode($data->author_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('sub_author_id')); ?>:</b>
	<?php echo CHtml::encode($data->sub_author_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publish_place')); ?>:</b>
	<?php echo CHtml::encode($data->publish_place); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publish_organization')); ?>:</b>
	<?php echo CHtml::encode($data->publish_organization); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publish_date')); ?>:</b>
	<?php echo CHtml::encode($data->publish_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_version_id')); ?>:</b>
	<?php echo CHtml::encode($data->book_version_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_pages')); ?>:</b>
	<?php echo CHtml::encode($data->book_pages); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_size')); ?>:</b>
	<?php echo CHtml::encode($data->book_size); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('series')); ?>:</b>
	<?php echo CHtml::encode($data->series); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('summary')); ?>:</b>
	<?php echo CHtml::encode($data->summary); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('memo')); ?>:</b>
	<?php echo CHtml::encode($data->memo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->create_datetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->update_datetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delete_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->delete_datetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_operator')); ?>:</b>
	<?php echo CHtml::encode($data->last_operator); ?>
	<br />

	*/ ?>

</div>