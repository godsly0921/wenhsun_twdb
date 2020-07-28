<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BooksizeController */
/* @var $model BookSize */

$this->breadcrumbs=array(
	'Book Sizes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookSize', 'url'=>array('index')),
	array('label'=>'Create BookSize', 'url'=>array('create')),
	array('label'=>'Update BookSize', 'url'=>array('update', 'id'=>$model->book_size_id)),
	array('label'=>'Delete BookSize', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->book_size_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookSize', 'url'=>array('admin')),
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
		'book_size_id',
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
