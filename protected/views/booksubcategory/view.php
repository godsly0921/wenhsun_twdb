<?php
/* @var $this BooksubcategoryController */
/* @var $model BookSubCategory */

$this->breadcrumbs=array(
	'Book Sub Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookSubCategory', 'url'=>array('index')),
	array('label'=>'Create BookSubCategory', 'url'=>array('create')),
	array('label'=>'Update BookSubCategory', 'url'=>array('update', 'id'=>$model->sub_category_id)),
	array('label'=>'Delete BookSubCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->sub_category_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookSubCategory', 'url'=>array('admin')),
);
?>

<h1>View BookSubCategory #<?php echo $model->sub_category_id; ?></h1>

<?php $this->widget('luckywave.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'sub_category_id',
		'name',
		'isroot',
		'parents',
		'status',
		'create_at',
		'update_at',
		'delete_at',
		'last_updated_user',
	),
)); ?>
