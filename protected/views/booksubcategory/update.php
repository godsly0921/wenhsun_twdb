<?php
/* @var $this BooksubcategoryController */
/* @var $model BookSubCategory */

$this->breadcrumbs=array(
	'Book Sub Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->sub_category_id),
	'Update',
);
$this->menu=array(
	array('label'=>'List BookSubCategory', 'url'=>array('index')),
	array('label'=>'Create BookSubCategory', 'url'=>array('create')),
	array('label'=>'View BookSubCategory', 'url'=>array('view', 'id'=>$model->sub_category_id)),
	array('label'=>'Manage BookSubCategory', 'url'=>array('admin')),
);
?>

<h1>BookSubCategory <?php echo $model->sub_category_id; ?> 更新</h1>
<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	
</div>
</div>
