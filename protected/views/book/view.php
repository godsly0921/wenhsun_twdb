<?php
/* @var $this BookController */
/* @var $model Book */

$this->breadcrumbs=array(
	'Books'=>array('index'),
	$model->book_id,
);

$this->menu=array(
	array('label'=>'List Book', 'url'=>array('index')),
	array('label'=>'Create Book', 'url'=>array('create')),
	array('label'=>'Update Book', 'url'=>array('update', 'id'=>$model->book_id)),
	array('label'=>'Delete Book', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->book_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Book', 'url'=>array('admin')),
);
?>

<h1>View Book #<?php echo $model->book_id; ?></h1>

<?php $this->widget('luckywave.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'book_id',
		'single_id',
		'book_num',
		'main_category',
		'sub_category',
		'book_name',
		'author_id',
		'sub_author_id',
		'publish_place',
		'publish_organization',
		'publish_date',
		'book_version_id',
		'book_pages',
		'book_size',
		'series',
		'summary',
		'memo',
		'create_datetime',
		'update_datetime',
		'delete_datetime',
		'last_operator',
	),
)); ?>
