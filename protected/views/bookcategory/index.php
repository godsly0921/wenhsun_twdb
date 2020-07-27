<?php
/* @var $this BookcategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Book Categories',
);

$this->menu=array(
	array('label'=>'Create BookCategory', 'url'=>array('create')),
	array('label'=>'Manage BookCategory', 'url'=>array('admin')),
);
?>

<h1>Book Categories</h1>

<?php $this->widget('luckywave.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
