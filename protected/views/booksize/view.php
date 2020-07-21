<?php
/* @var $this BooksizeController */
/* @var $model BookSize */

$this->breadcrumbs=array(
	'Book Sizes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookSize', 'url'=>array('index')),
	array('label'=>'Create BookSize', 'url'=>array('create')),
	array('label'=>'Update BookSize', 'url'=>array('update', 'id'=>$model->book_size_id)),
	array('label'=>'Delete BookSize', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->book_size_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookSize', 'url'=>array('admin')),
);
?>

<h1>View BookSize #<?php echo $model->book_size_id; ?></h1>

<?php $this->widget('luckywave.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'book_size_id',
		'name',
		'status',
		'create_at',
		'update_at',
		'delete_at',
		'last_updated_user',
	),
)); ?>
