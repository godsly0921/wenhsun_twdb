<?php
/* @var $this BooksizeController */
/* @var $model BookSize */

$this->breadcrumbs=array(
	'Book Sizes'=>array('index'),
	$model->name=>array('view','id'=>$model->book_size_id),
	'Update',
);
$this->menu=array(
	array('label'=>'List BookSize', 'url'=>array('index')),
	array('label'=>'Create BookSize', 'url'=>array('create')),
	array('label'=>'View BookSize', 'url'=>array('view', 'id'=>$model->book_size_id)),
	array('label'=>'Manage BookSize', 'url'=>array('admin')),
);
?>

<h1>BookSize <?php echo $model->book_size_id; ?> 更新</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	
</div>
</div>
