<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookauthorController */
/* @var $model BookAuthor */

$this->breadcrumbs=array(
	'Book Authors'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookAuthor', 'url'=>array('index')),
	array('label'=>'Create BookAuthor', 'url'=>array('create')),
	array('label'=>'Update BookAuthor', 'url'=>array('update', 'id'=>$model->author_id)),
	array('label'=>'Delete BookAuthor', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->author_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookAuthor', 'url'=>array('admin')),
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
		'author_id',
		'name',
		'birthday',
		array(  
			"name" => "gender",
			"value" => Common::getGenderText($model->gender),
		),
		array(  
			"name" => "status",
			"value" => Common::getStatusText($model->status),
		),
		'summary',
		'memo',
		'create_at',
		'update_at',
		// 'delete_at',
		array(
        	'name'=>'last_updated_user',
        	'value'=>$model->_Account->account_name,
        ),
	),
)); ?>
