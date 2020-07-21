<?php
/* @var $this BookpublishunitController */
/* @var $model BookPublishUnit */

$this->breadcrumbs=array(
	'Book Publish Units'=>array('index'),
	$model->name=>array('view','id'=>$model->publish_unit_id),
	'Update',
);
$this->menu=array(
	array('label'=>'List BookPublishUnit', 'url'=>array('index')),
	array('label'=>'Create BookPublishUnit', 'url'=>array('create')),
	array('label'=>'View BookPublishUnit', 'url'=>array('view', 'id'=>$model->publish_unit_id)),
	array('label'=>'Manage BookPublishUnit', 'url'=>array('admin')),
);
?>

<h1>BookPublishUnit <?php echo $model->publish_unit_id; ?> 更新</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	
</div>
</div>
