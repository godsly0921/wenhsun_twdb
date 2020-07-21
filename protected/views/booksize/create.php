<?php
/* @var $this BooksizeController */
/* @var $model BookSize */

$this->breadcrumbs=array(
	'Book Sizes'=>array('index'),
	'Create',
);
$this->menu=array(
	array('label'=>'List BookSize', 'url'=>array('index')),
	array('label'=>'Manage BookSize', 'url'=>array('admin')),
);
?>

<h1>新增 BookSize</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	</div>
</div>
