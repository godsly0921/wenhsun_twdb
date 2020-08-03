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
                <th>書影<br/>圖片</th>
                <th>影像<br/>編號</th>
                <th>文類<br/>名稱</th>
                <th>書名</th>
                <th>作家</th>
                <th>次要<br/>作者</th>
                <th>出版<br/>地點</th>
                <th>出版<br/>單位</th>
                <th>出版<br/>時間</th>
                <th>版本</th>
                <th>頁數</th>
                <th>開本</th>
                <th>叢書<br/>名稱</th>
                <th>簡介</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model as $key => $value):?>
                <tr class="gradeC" role="row">
                    <td><img src='<?=DOMAIN."image_storage/P/".$value['single_id']?>.jpg' style='max-width: 100px;max-height: 100px;'></td>
                    <td><?= $value["book_num"] ?></td>
                    <td><?php 
                        $category = explode(',',$value['category']);
                        $category_name = array();
                        foreach ($category as $category_key => $category_value) {
                            if(isset($category_data[$category_value])){
                                array_push($category_name,$category_data[$category_value]);
                            }
                        }
                        echo implode('，',$category_name);
                        ?>
                    </td>
                    <td><?= $value["book_name"] ?></td>
                    <td><?= $value["author_name"] ?></td>
                    <td><?= $this->findSubAuthorName($value["sub_author_id"]) ?></td>
                    <td><?= $value["publish_place_name"] ?></td>
                    <td><?= $value["publish_unit_name"] ?></td>
                    <td><?= $value["publish_year"].(!empty($value["publish_month"])?'-'.$value["publish_month"]:'').(!empty($value["publish_day"])?'-'.$value["publish_day"]:'') ?></td>
                    <td><?= $value["book_version"] ?></td>
                    <td><?= $value["book_pages"] ?></td>
                    <td><?= $value["size_name"] ?></td>
                    <td><?= $value["series_name"] ?></td>
                    <td><?= $value["summary"] ?></td>
                    <td><?= $value["status"]==1?'上架':'下架' ?></td>
                    <td>
                    	<?php if($view_power){?>
                            <div class="row">
							<a class="oprate-right" href="<?php echo Yii::app()->createUrl('book/update') ?>/<?= $value["book_id"] ?>"><i class="fa fa-search fa-lg">檢視</i></a></div><br/>
                    	<?php  }?>
                    	<?php if($update_power){?>
                            <div class="row">
							<a class="update" title="Update" href="<?php echo Yii::app()->createUrl('book/update') ?>/<?= $value["book_id"] ?>"><i class="fa fa-pencil-square-o fa-lg">更新</i></a></div><br/>
                    	<?php  }?>
                        <?php if($delete_power){?>
                            <a class="oprate-right oprate-del" data-acc-id="<?=$value["book_id"]?>"><div class="row"><i class="fa fa-times fa-lg">刪除</i></div></a>
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
    $(".oprate-del").on('click', function(){
        var answer = confirm("你確定要刪除此項目嗎?");
        var accId = $(this).data("acc-id");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method',"post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('book/delete'); ?>/"+accId);
            document.body.appendChild(form);

            form.submit();
        }
    });
</script>