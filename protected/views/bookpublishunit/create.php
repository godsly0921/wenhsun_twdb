<?php
/* @var $this BookpublishunitController */
/* @var $model BookPublishUnit */

$this->breadcrumbs=array(
	'Book Publish Units'=>array('index'),
	'Create',
);
$this->menu=array(
	array('label'=>'List BookPublishUnit', 'url'=>array('index')),
	array('label'=>'Manage BookPublishUnit', 'url'=>array('admin')),
);
?>

<h1>新增 BookPublishUnit</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	</div>
</div>
