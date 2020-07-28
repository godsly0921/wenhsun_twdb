<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BooksizeController */
/* @var $model BookSize */

$this->breadcrumbs=array(
	'Book Sizes'=>array('index'),
	$model->name=>array('view','id'=>$model->book_size_id),
	'Update',
);
$this->menu=array(
	array('label'=>'List BookSize', 'url'=>array('index')),
	array('label'=>'Create BookSize', 'url'=>array('create')),
	array('label'=>'View BookSize', 'url'=>array('view', 'id'=>$model->book_size_id)),
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

?><div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>	
</div>
</div>
