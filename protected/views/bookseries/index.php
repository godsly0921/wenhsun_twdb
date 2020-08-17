<?php
/* @var $this BookseriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Book Series',
);

$this->menu=array(
	array('label'=>'Create BookSeries', 'url'=>array('create')),
	array('label'=>'Manage BookSeries', 'url'=>array('admin')),
);
?>

<h1>Book Series</h1>

<?php $this->widget('luckywave.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
