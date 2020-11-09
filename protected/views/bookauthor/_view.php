<?php
/* @var $this BookauthorController */
/* @var $data BookAuthor */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('author_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->author_id), array('view', 'id'=>$data->author_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gender')); ?>:</b>
	<?php echo CHtml::encode($data->gender); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('summary')); ?>:</b>
	<?php echo CHtml::encode($data->summary); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('memo')); ?>:</b>
	<?php echo CHtml::encode($data->memo); ?>
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_updated_user')); ?>:</b>
	<?php echo CHtml::encode($data->last_updated_user); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('original_name')); ?>:</b>
	<?php echo CHtml::encode($data->original_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hometown')); ?>:</b>
	<?php echo CHtml::encode($data->hometown); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('birth_year')); ?>:</b>
	<?php echo CHtml::encode($data->birth_year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('birth_month')); ?>:</b>
	<?php echo CHtml::encode($data->birth_month); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bitrh_day')); ?>:</b>
	<?php echo CHtml::encode($data->bitrh_day); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('arrive_time')); ?>:</b>
	<?php echo CHtml::encode($data->arrive_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('experience')); ?>:</b>
	<?php echo CHtml::encode($data->experience); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('literary_style')); ?>:</b>
	<?php echo CHtml::encode($data->literary_style); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('literary_achievement')); ?>:</b>
	<?php echo CHtml::encode($data->literary_achievement); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year_of_death')); ?>:</b>
	<?php echo CHtml::encode($data->year_of_death); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year_of_month')); ?>:</b>
	<?php echo CHtml::encode($data->year_of_month); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year_of_day')); ?>:</b>
	<?php echo CHtml::encode($data->year_of_day); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pen_name')); ?>:</b>
	<?php echo CHtml::encode($data->pen_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('literary_genre')); ?>:</b>
	<?php echo CHtml::encode($data->literary_genre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('present_job')); ?>:</b>
	<?php echo CHtml::encode($data->present_job); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('brief_intro')); ?>:</b>
	<?php echo CHtml::encode($data->brief_intro); ?>
	<br />

	*/ ?>

</div>