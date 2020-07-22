<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookpublishplaceController */
/* @var $model BookPublishPlace */

$this->breadcrumbs=array(
	'Book Publish Places'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookPublishPlace', 'url'=>array('index')),
	array('label'=>'Create BookPublishPlace', 'url'=>array('create')),
	array('label'=>'Update BookPublishPlace', 'url'=>array('update', 'id'=>$model->publish_place_id)),
	array('label'=>'Delete BookPublishPlace', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->publish_place_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookPublishPlace', 'url'=>array('admin')),
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
		'publish_place_id',
		'name',
		'status',
		'create_at',
		'update_at',
		'delete_at',
		'last_updated_user',
	),
)); ?>
