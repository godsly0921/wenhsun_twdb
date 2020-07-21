<?php
/* @var $this BookController */
/* @var $model Book */

$this->breadcrumbs=array(
	'Books'=>array('index'),
	'Create',
);
$this->menu=array(
	array('label'=>'List Book', 'url'=>array('index')),
	array('label'=>'Manage Book', 'url'=>array('admin')),
);
?>

<h1>新增 Book</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	</div>
</div>
