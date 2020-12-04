<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookauthorController */
/* @var $model BookAuthor */

$this->breadcrumbs=array(
	'Book Authors'=>array('index'),
	'Create',
);
$this->menu=array(
	array('label'=>'List BookAuthor', 'url'=>array('index')),
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

?><div class='panel panel-default' style='width=100%; overflow-y:scroll;'>
    <div class='panel-body'>
		<?php $this->renderPartial('_form', array('isNewRecord'=>true, 'model'=>$model, 'model_author_event'=>$model_author_event, 'single'=>$single, 'book_category'=>$book_category,'total_model_author_event' => $total_model_author_event)); ?>	</div>
</div>
