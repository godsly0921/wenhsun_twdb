<?php
/* @var $this BooksubcategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Book Sub Categories',
);

$this->menu=array(
	array('label'=>'Create BookSubCategory', 'url'=>array('create')),
	array('label'=>'Manage BookSubCategory', 'url'=>array('admin')),
);
?>

<h1>Book Sub Categories</h1>

<?php $this->widget('luckywave.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
