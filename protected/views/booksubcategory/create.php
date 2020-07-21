<?php
/* @var $this BooksubcategoryController */
/* @var $model BookSubCategory */

$this->breadcrumbs=array(
	'Book Sub Categories'=>array('index'),
	'Create',
);
$this->menu=array(
	array('label'=>'List BookSubCategory', 'url'=>array('index')),
	array('label'=>'Manage BookSubCategory', 'url'=>array('admin')),
);
?>

<h1>新增 BookSubCategory</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	</div>
</div>
