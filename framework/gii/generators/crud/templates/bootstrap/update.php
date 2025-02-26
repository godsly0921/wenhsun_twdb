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
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	'Update',
);\n";
?>
$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>'View <?php echo $this->modelClass; ?>', 'url'=>array('view', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>
<?php echo "<?php\n";?>
	foreach ($session_jsons as $jsons) {
		if ($jsons["power_controller"] == $this->getId() . "/" . $this->getAction()->getId()){
			echo "<h1>".$jsons["power_name"]."</h1>";
			echo "<a href='".Yii::app()->createUrl(Yii::app()->controller->id."/admin")."' class='btn btn-default btn-right'>返回管理頁</a>";
		}
	}
<?php echo "\n?>"?>
<?php echo "<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>\n"?>
    <?php echo "<div class='panel-body'>\n"?>
		<?php echo "<?php \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
	<?php echo "\n</div>\n"?>
<?php echo "</div>\n"?>