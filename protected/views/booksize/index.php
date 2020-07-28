<?php
/* @var $this BooksizeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Book Sizes',
);

$this->menu=array(
	array('label'=>'Create BookSize', 'url'=>array('create')),
	array('label'=>'Manage BookSize', 'url'=>array('admin')),
);
?>

<h1>Book Sizes</h1>

<?php $this->widget('luckywave.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
