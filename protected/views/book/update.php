<?php
/* @var $this BookController */
/* @var $model Book */

$this->breadcrumbs=array(
	'Books'=>array('index'),
	$model->book_id=>array('view','id'=>$model->book_id),
	'Update',
);
$this->menu=array(
	array('label'=>'List Book', 'url'=>array('index')),
	array('label'=>'Create Book', 'url'=>array('create')),
	array('label'=>'View Book', 'url'=>array('view', 'id'=>$model->book_id)),
	array('label'=>'Manage Book', 'url'=>array('admin')),
);
?>

<h1>Book <?php echo $model->book_id; ?> 更新</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	
</div>
</div>
