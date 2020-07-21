<?php
/* @var $this BookpublishunitController */
/* @var $model BookPublishUnit */

$this->breadcrumbs=array(
	'Book Publish Units'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookPublishUnit', 'url'=>array('index')),
	array('label'=>'Create BookPublishUnit', 'url'=>array('create')),
	array('label'=>'Update BookPublishUnit', 'url'=>array('update', 'id'=>$model->publish_unit_id)),
	array('label'=>'Delete BookPublishUnit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->publish_unit_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookPublishUnit', 'url'=>array('admin')),
);
?>

<h1>View BookPublishUnit #<?php echo $model->publish_unit_id; ?></h1>

<?php $this->widget('luckywave.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'publish_unit_id',
		'name',
		'status',
		'create_at',
		'update_at',
		'delete_at',
		'last_updated_user',
	),
)); ?>
