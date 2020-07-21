<?php
/* @var $this BookpublishplaceController */
/* @var $model BookPublishPlace */

$this->breadcrumbs=array(
	'Book Publish Places'=>array('index'),
	'Create',
);
$this->menu=array(
	array('label'=>'List BookPublishPlace', 'url'=>array('index')),
	array('label'=>'Manage BookPublishPlace', 'url'=>array('admin')),
);
?>

<h1>新增 BookPublishPlace</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	</div>
</div>
