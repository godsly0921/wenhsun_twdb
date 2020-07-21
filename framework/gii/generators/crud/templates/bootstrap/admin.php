<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
echo "<?php \$session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>";
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Manage',
);\n";
?>

$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#<?php echo $this->class2id($this->modelClass); ?>-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo $this->pluralize($this->class2name($this->modelClass)); ?> 管理</h1>
<?php echo "<?php\n";?>
	foreach ($session_jsons as $jsons) {
		if ($jsons["power_controller"] == Yii::app()->controller->id . "/create"){
			echo "<a href='".Yii::app()->createUrl(Yii::app()->controller->id."/create")."' class='btn btn-default btn-right'>新增<?php echo $this->pluralize($this->class2name($this->modelClass));?></a>";
		}
	}
<?php echo "\n?>"?>
<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-body">
		<p>
		您可以在以下搜尋框前加上搜尋條件 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
		or <b>=</b>) 加以鎖定搜尋的範圍
		</p>

		<?php echo "<?php echo CHtml::link('進階搜尋','#',array('class'=>'search-button')); ?>"; ?>

		<div class="search-form" style="display:none">
		<?php echo "<?php \$this->renderPartial('_search',array(
			'model'=>\$model,
		)); ?>\n"; ?>
		</div><!-- search-form -->

		<?php echo "<?php"; ?> 
		$button_column_template = "";
		foreach ($session_jsons as $jsons) {
			if (strtolower($jsons["power_controller"]) == '<?php echo Yii::app()->controller->id; ?>/view'){
				$button_column_template .= " {view}";
			}
			if (strtolower($jsons["power_controller"]) == '<?php echo Yii::app()->controller->id; ?>/update'){
				$button_column_template .= " {update}";
			}
			if (strtolower($jsons["power_controller"]) == '<?php echo Yii::app()->controller->id; ?>/delete'){
				$button_column_template .= " {delete}";
			}
		}
		$this->widget('luckywave.widgets.grid.CGridView', array(
			'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'emptyText' => '目前沒有任何資料',
			'columns'=>array(
		<?php
		$count=0;
		foreach($this->tableSchema->columns as $column)
		{
			if(++$count==7)
				echo "\t\t/*\n";
			echo "\t\t'".$column->name."',\n";
		}
		if($count>=7){
			echo "\t\t*/\n";
		}
		?>
				array(
					'class'=>'CButtonColumn',
					'template'=>$button_column_template,
				),
			),
		)); ?>
	</div>
</div>