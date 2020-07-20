<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Create',
);\n";
?>
$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<h1>新增 <?php echo $this->modelClass; ?></h1>
<?php echo "<div class='panel panel-default' style='width=100%; overflow-y:scroll;'>\n"?>
    <?php echo "<div class='panel-body'>\n"?>
		<?php echo "<?php \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
	<?php echo "</div>\n"?>
<?php echo "</div>\n"?>