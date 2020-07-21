<?php
/* @var $this BookmaincategoryController */
/* @var $model BookMainCategory */

$this->breadcrumbs=array(
	'Book Main Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookMainCategory', 'url'=>array('index')),
	array('label'=>'Create BookMainCategory', 'url'=>array('create')),
	array('label'=>'Update BookMainCategory', 'url'=>array('update', 'id'=>$model->main_category_id)),
	array('label'=>'Delete BookMainCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->main_category_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookMainCategory', 'url'=>array('admin')),
);
?>

<h1>View BookMainCategory #<?php echo $model->main_category_id; ?></h1>

<?php $this->widget('luckywave.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'main_category_id',
		'name',
		'status',
		'create_at',
		'update_at',
		'delete_at',
		'last_updated_user',
	),
)); ?>
