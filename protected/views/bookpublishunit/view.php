<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookpublishunitController */
/* @var $model BookPublishUnit */

$this->breadcrumbs=array(
	'Book Publish Units'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookPublishUnit', 'url'=>array('index')),
	array('label'=>'Create BookPublishUnit', 'url'=>array('create')),
	array('label'=>'Update BookPublishUnit', 'url'=>array('update', 'id'=>$model->publish_unit_id)),
	array('label'=>'Delete BookPublishUnit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->publish_unit_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookPublishUnit', 'url'=>array('admin')),
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
		'publish_unit_id',
		'name',
		array(  
			"name" => "status",
			"value" => Common::getStatusText($model->status),
		),
		'create_at',
		'update_at',
		array(
        	'name'=>'last_updated_user',
        	'value'=>$model->_Account->account_name,
        ),
	),
)); ?>
