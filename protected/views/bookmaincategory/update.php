<?php
/* @var $this BookmaincategoryController */
/* @var $model BookMainCategory */

$this->breadcrumbs=array(
	'Book Main Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->main_category_id),
	'Update',
);
$this->menu=array(
	array('label'=>'List BookMainCategory', 'url'=>array('index')),
	array('label'=>'Create BookMainCategory', 'url'=>array('create')),
	array('label'=>'View BookMainCategory', 'url'=>array('view', 'id'=>$model->main_category_id)),
	array('label'=>'Manage BookMainCategory', 'url'=>array('admin')),
);
?>

<h1>BookMainCategory <?php echo $model->main_category_id; ?> 更新</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	
</div>
</div>
