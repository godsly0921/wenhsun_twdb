<?php
/* @var $this BookmaincategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Book Main Categories',
);

$this->menu=array(
	array('label'=>'Create BookMainCategory', 'url'=>array('create')),
	array('label'=>'Manage BookMainCategory', 'url'=>array('admin')),
);
?>

<h1>Book Main Categories</h1>

<?php $this->widget('luckywave.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
