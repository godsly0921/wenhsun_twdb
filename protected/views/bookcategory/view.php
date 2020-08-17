<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookcategoryController */
/* @var $model BookCategory */

$this->breadcrumbs=array(
	'Book Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List BookCategory', 'url'=>array('index')),
	array('label'=>'Create BookCategory', 'url'=>array('create')),
	array('label'=>'Update BookCategory', 'url'=>array('update', 'id'=>$model->category_id)),
	array('label'=>'Delete BookCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->category_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BookCategory', 'url'=>array('admin')),
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
		'category_id',
		'name',
		array(  
			"name" => "isroot",
			"value" => $model->isroot == 1 ? '是':'否',
		),
		'parents',
		'sort',
		array(  
			"name" => "status",
			"value" => Common::getStatusText($model->status),
		),
		array(  
			"name" => "type",
			"value" => Common::getBookCategoryTypeText($model->type),
		),
		'create_at',
		'update_at',
		'delete_at',
		array(
        	'name'=>'last_updated_user',
        	'value'=>$model->_Account->account_name,
        ),
	),
)); ?>
