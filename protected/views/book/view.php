<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookController */
/* @var $model Book */

$this->breadcrumbs=array(
	'Books'=>array('index'),
	$model->book_id,
);

$this->menu=array(
	array('label'=>'List Book', 'url'=>array('index')),
	array('label'=>'Create Book', 'url'=>array('create')),
	array('label'=>'Update Book', 'url'=>array('update', 'id'=>$model->book_id)),
	array('label'=>'Delete Book', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->book_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Book', 'url'=>array('admin')),
);
?>
<?php
	foreach ($session_jsons as $jsons) {
		if ($jsons["power_controller"] == $this->getId() . "/" . $this->getAction()->getId()){
			echo "<h1>".$jsons["power_name"]."</h1>";
			echo "<a href='".Yii::app()->createUrl(Yii::app()->controller->id."/admin")."' class='btn btn-default btn-right'>返回管理頁</a>";
		}
	}

?>
<?php $this->widget('luckywave.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'book_id',
		'single_id',
		'book_num',
		'category',
		'book_name',
		'author_id',
		'sub_author_id',
		'publish_place',
		'publish_organization',
		'publish_year',
		'publish_month',
		'publish_day',
		'book_version',
		'book_pages',
		'book_size',
		'series',
		'summary',
		'memo',
		'create_at',
		'update_at',
		'delete_at',
		'last_updated_user',
	),
)); ?>
