<?php
/* @var $this BookauthorController */
/* @var $model BookAuthor */

$this->breadcrumbs=array(
	'Book Authors'=>array('index'),
	$model->name=>array('view','id'=>$model->author_id),
	'Update',
);
$this->menu=array(
	array('label'=>'List BookAuthor', 'url'=>array('index')),
	array('label'=>'Create BookAuthor', 'url'=>array('create')),
	array('label'=>'View BookAuthor', 'url'=>array('view', 'id'=>$model->author_id)),
	array('label'=>'Manage BookAuthor', 'url'=>array('admin')),
);
?>

<h1>更新 BookAuthor <?php echo $model->author_id; ?> </h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	
</div>
</div>
