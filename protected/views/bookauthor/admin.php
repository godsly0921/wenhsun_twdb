<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookauthorController */
/* @var $model BookAuthor */

$this->breadcrumbs=array(
	'Book Authors'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List BookAuthor', 'url'=>array('index')),
	array('label'=>'Create BookAuthor', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#book-author-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php
	$create_permission = false;
	$create_html="";
	foreach ($session_jsons as $jsons) {
		if ($jsons["power_controller"] == $this->getId() . "/" . $this->getAction()->getId()){
			echo "<h1>".$jsons["power_name"]."</h1>";
		}
		if ($jsons["power_controller"] == Yii::app()->controller->id . "/create"){
			$create_permission = true;
			$create_html = "<a href='".Yii::app()->createUrl(Yii::app()->controller->id."/create")."' class='btn btn-default btn-right'>" . $jsons["power_name"] . "</a>";
		}
	}
	if($create_permission){
		echo $create_html;
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
			if (strtolower($jsons["power_controller"]) == $this->getId() . '/view'){
				$button_column_template .= " {view}";
			}
			if (strtolower($jsons["power_controller"]) == $this->getId() . '/update'){
				$button_column_template .= " {update}";
			}
			if (strtolower($jsons["power_controller"]) == $this->getId() . '/delete'){
				$button_column_template .= " {delete}";
			}
		}
		$this->widget('luckywave.widgets.grid.CGridView', array(
			'id'=>'book-author-grid',
			'dataProvider'=>$model->search(),
			// 'filter'=>$model,
			'emptyText' => '目前沒有任何資料',
			'columns'=>array(
				'author_id',
		'name',
		// 'birthday',
		'gender',
		'summary',
		'memo',
		/*
		'create_at',
		'update_at',
		'delete_at',
		'status',
		'last_updated_user',
		'original_name',
		'hometown',
		'birth_year',
		'birth_month',
		'bitrh_day',
		'arrive_time',
		'experience',
		'literary_style',
		'literary_achievement',
		'year_of_death',
		'year_of_month',
		'year_of_day',
		'pen_name',
		'literary_genre',
		'present_job',
		'brief_intro',
		*/
				array(
					'class'=>'CButtonColumn',
					'template'=>$button_column_template,
				),
			),
		)); ?>
	</div>
</div>
