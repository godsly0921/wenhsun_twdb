<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookcategoryController */
/* @var $model BookCategory */

$this->breadcrumbs=array(
	'Book Categories'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List BookCategory', 'url'=>array('index')),
	array('label'=>'Create BookCategory', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#book-category-grid').yiiGridView('update', {
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
		<?php 
		$view_power = $update_power = $delete_power = false;
		foreach ($session_jsons as $jsons) {
			if (strtolower($jsons["power_controller"]) == $this->getId() . '/view'){
				$view_power = true;
			}
			if (strtolower($jsons["power_controller"]) == $this->getId() . '/update'){
				$update_power = true;
			}
			if (strtolower($jsons["power_controller"]) == $this->getId() . '/delete'){
				$delete_power = true;
			}
		}
		?>
		<table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>文類編號</th>
                <th>文類名稱</th>
                <th>次文類名稱</th>
                <th>排序</th>
                <th>建立時間</th>
                <th>更新時間</th>
                <th>最後異動的人</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model as $key => $value):?>
                <tr class="gradeC" role="row">
                    <td><?= $value["category_id"] ?></td>
                    <td><?= $value["main_category"] ?></td>
                    <td><?= $value["sub_category"] ?></td>
                    <td class="sort"><?= $value["sort"] ?></td>
                    <td><?= $value["create_at"] ?></td>
                    <td><?= $value["update_at"] ?></td>
                    <td><?= ($value["status"]==0) ? "停用" : "啟用" ?></td>
                    <td><?= $value["last_updated_user"] ?></td>
                    <td>
                    	<?php if($view_power){?>
							<a class="oprate-right" href="<?php echo Yii::app()->createUrl('bookcategory/update') ?>/<?= $value["category_id"] ?>"><div class="row"><i class="fa fa-search fa-lg">檢視</i></div></a>
                    	<?php  }?>
                    	<?php if($update_power){?>
							<a class="update" title="Update" href="<?php echo Yii::app()->createUrl('bookcategory/update') ?>/<?= $value["category_id"] ?>"><div class="row"><i class="fa fa-pencil-square-o fa-lg">更新</i></div></a>
                    	<?php  }?>
                        <?php if($delete_power){?>
							<a class="delete" title="Delete" href="<?php echo Yii::app()->createUrl('bookcategory/delete') ?>/<?= $value["category_id"] ?>"><div class="row"><i class="fa fa-times fa-lg">刪除</i></div></a>
                    	<?php  }?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
	</div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何資料"
            }
        } );
    });
</script>