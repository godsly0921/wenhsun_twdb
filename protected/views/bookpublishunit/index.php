<?php
/* @var $this BookpublishunitController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Book Publish Units',
);

$this->menu=array(
	array('label'=>'Create BookPublishUnit', 'url'=>array('create')),
	array('label'=>'Manage BookPublishUnit', 'url'=>array('admin')),
);
?>

<h1>Book Publish Units</h1>

<?php $this->widget('luckywave.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
