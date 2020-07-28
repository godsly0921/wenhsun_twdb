<?php
/* @var $this BookpublishplaceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Book Publish Places',
);

$this->menu=array(
	array('label'=>'Create BookPublishPlace', 'url'=>array('create')),
	array('label'=>'Manage BookPublishPlace', 'url'=>array('admin')),
);
?>

<h1>Book Publish Places</h1>

<?php $this->widget('luckywave.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
