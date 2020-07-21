<?php
/* @var $this BookauthorController */
/* @var $model BookAuthor */

$this->breadcrumbs=array(
	'Book Authors'=>array('index'),
	'Create',
);
$this->menu=array(
	array('label'=>'List BookAuthor', 'url'=>array('index')),
	array('label'=>'Manage BookAuthor', 'url'=>array('admin')),
);
?>

<h1>新增 BookAuthor</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	</div>
</div>
