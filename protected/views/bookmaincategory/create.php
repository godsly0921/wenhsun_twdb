<?php
/* @var $this BookmaincategoryController */
/* @var $model BookMainCategory */

$this->breadcrumbs=array(
	'Book Main Categories'=>array('index'),
	'Create',
);
$this->menu=array(
	array('label'=>'List BookMainCategory', 'url'=>array('index')),
	array('label'=>'Manage BookMainCategory', 'url'=>array('admin')),
);
?>

<h1>新增 BookMainCategory</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	</div>
</div>
