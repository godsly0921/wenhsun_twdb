<?php
/* @var $this BookauthorController */
/* @var $model BookAuthor */

$this->breadcrumbs=array(
	'Book Authors'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookAuthor', 'url'=>array('index')),
	array('label'=>'Create BookAuthor', 'url'=>array('create')),
	array('label'=>'Update BookAuthor', 'url'=>array('update', 'id'=>$model->author_id)),
	array('label'=>'Delete BookAuthor', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->author_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookAuthor', 'url'=>array('admin')),
);
?>

<h1>檢視 BookAuthor #<?php echo $model->author_id; ?></h1>

<?php $this->widget('luckywave.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'author_id',
		'name',
		'birthday',
		'gender',
		'summary',
		'memo',
		'create_at',
		'update_at',
		'delete_at',
	),
)); ?>
