<?php
/* @var $this BookpublishplaceController */
/* @var $model BookPublishPlace */

$this->breadcrumbs=array(
	'Book Publish Places'=>array('index'),
	$model->name=>array('view','id'=>$model->publish_place_id),
	'Update',
);
$this->menu=array(
	array('label'=>'List BookPublishPlace', 'url'=>array('index')),
	array('label'=>'Create BookPublishPlace', 'url'=>array('create')),
	array('label'=>'View BookPublishPlace', 'url'=>array('view', 'id'=>$model->publish_place_id)),
	array('label'=>'Manage BookPublishPlace', 'url'=>array('admin')),
);
?>

<h1>BookPublishPlace <?php echo $model->publish_place_id; ?> 更新</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	
</div>
</div>
