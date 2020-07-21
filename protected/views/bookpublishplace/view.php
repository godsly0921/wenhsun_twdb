<?php
/* @var $this BookpublishplaceController */
/* @var $model BookPublishPlace */

$this->breadcrumbs=array(
	'Book Publish Places'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookPublishPlace', 'url'=>array('index')),
	array('label'=>'Create BookPublishPlace', 'url'=>array('create')),
	array('label'=>'Update BookPublishPlace', 'url'=>array('update', 'id'=>$model->publish_place_id)),
	array('label'=>'Delete BookPublishPlace', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->publish_place_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookPublishPlace', 'url'=>array('admin')),
);
?>

<h1>View BookPublishPlace #<?php echo $model->publish_place_id; ?></h1>

<?php $this->widget('luckywave.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'publish_place_id',
		'name',
		'status',
		'create_at',
		'update_at',
		'delete_at',
		'last_updated_user',
	),
)); ?>
