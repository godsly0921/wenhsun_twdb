<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookpublishunitController */
/* @var $model BookPublishUnit */

$this->breadcrumbs=array(
	'Book Publish Units'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List BookPublishUnit', 'url'=>array('index')),
	array('label'=>'Create BookPublishUnit', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#book-publish-unit-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Book Publish Units 管理</h1>
<?php
	foreach ($session_jsons as $jsons) {
		if ($jsons["power_controller"] == Yii::app()->controller->id . "/create"){
			echo "<a href='".Yii::app()->createUrl(Yii::app()->controller->id."/create")."' class='btn btn-default btn-right'>新增Book Publish Units</a>";
		}
	}

?><div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-body">
		<p>
		您可以在以下搜尋框前加上搜尋條件 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
		or <b>=</b>) 加以鎖定搜尋的範圍
		</p>

		<?php echo CHtml::link('進階搜尋','#',array('class'=>'search-button')); ?>
		<div class="search-form" style="display:none">
		<?php $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
		</div><!-- search-form -->

		<?php 
		$button_column_template = "";
		foreach ($session_jsons as $jsons) {
			if (strtolower($jsons["power_controller"]) == 'crud/view'){
				$button_column_template .= " {view}";
			}
			if (strtolower($jsons["power_controller"]) == 'crud/update'){
				$button_column_template .= " {update}";
			}
			if (strtolower($jsons["power_controller"]) == 'crud/delete'){
				$button_column_template .= " {delete}";
			}
		}
		$this->widget('luckywave.widgets.grid.CGridView', array(
			'id'=>'book-publish-unit-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'emptyText' => '目前沒有任何資料',
			'columns'=>array(
				'publish_unit_id',
		'name',
		'status',
		'create_at',
		'update_at',
		'delete_at',
		/*
		'last_updated_user',
		*/
				array(
					'class'=>'CButtonColumn',
					'template'=>$button_column_template,
				),
			),
		)); ?>
	</div>
</div>