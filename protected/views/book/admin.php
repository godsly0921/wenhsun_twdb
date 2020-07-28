<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookController */
/* @var $model Book */

$this->breadcrumbs=array(
	'Books'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Book', 'url'=>array('index')),
	array('label'=>'Create Book', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#book-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Books 管理</h1>
<?php
	foreach ($session_jsons as $jsons) {
		if ($jsons["power_controller"] == Yii::app()->controller->id . "/create"){
			echo "<a href='".Yii::app()->createUrl(Yii::app()->controller->id."/create")."' class='btn btn-default btn-right'>新增Books</a>";
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
			'id'=>'book-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'emptyText' => '目前沒有任何資料',
			'columns'=>array(
				'book_id',
		'single_id',
		'book_num',
		'main_category',
		'sub_category',
		'book_name',
		/*
		'author_id',
		'sub_author_id',
		'publish_place',
		'publish_organization',
		'publish_date',
		'book_version_id',
		'book_pages',
		'book_size',
		'series',
		'summary',
		'memo',
		'create_datetime',
		'update_datetime',
		'delete_datetime',
		'last_operator',
		*/
				array(
					'class'=>'CButtonColumn',
					'template'=>$button_column_template,
				),
			),
		)); ?>
	</div>
</div>