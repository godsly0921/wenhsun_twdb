<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">首頁廣告圖管理</h3>
    </div>
</div>
<?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                <li><?= $error[0] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if(isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
<div class="alert alert-success">
<strong>新增成功!</strong><?=Yii::app()->session['success_msg'];?>
</div>
<?php endif; ?>

<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>
<div class="panel panel-default">
    <div class="panel-heading">首頁廣告圖管理</div>
    <div class="panel-body">
    	
        <div class="form-group col-md-12">
            <label for="date_start" class="col-sm-1 control-label"> 圖號:</label>
            <div class="col-sm-6">
            <input type="text" class="form-control" id="single_id" name="single_id" placeholder="請輸入圖號" >
            </div>
        </div>
        <div class="form-group col-md-12">
            <label for="date_start" class="col-sm-1 control-label"> 關鍵字:</label>
            <div class="col-sm-6">
            <input type="text" class="form-control" id="keyword" name="keyword" placeholder="請輸入關鍵字" >
            </div>
        </div>
        <div class="form-group col-md-12">
            <label for="date_start" class="col-sm-1 control-label"> 分類:</label>
            <div class="col-sm-6">
            	<select class="select2_multiple form-control" id="category_id" name="category_id[]" multiple="multiple" required>
                  <?php foreach ($category_data as $key => $value) { ?>
                    <option value="<?=$value['category_id']?>" <?=$key==0?'selected':''?>><?=$value['parents_name']?>_<?=$value['child_name']?></option>
                  <?php }?>
                </select>
            </div>
        </div>
        <div class="form-group col-md-12 ">
            <label for="sort" class="col-sm-1 control-label"></label>
            <div class="col-sm-6">
            <button type="button" class="btn btn-default" id="search_single">查詢</button>
            </div>
        </div>
        <table id="search_single_result" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
            	<th></th>
                <th>圖檔</th>
                <th>人物資訊</th>
                <th>事件名稱</th>
                <th>拍攝時間</th>
                <th>拍攝地點</th>
            </tr>
            </thead>
            <tbody> 

            </tbody>
        </table>
        <div class="panel panel-default">
		    <div class="panel-heading">選取的圖片</div>
		    <div class="panel-body">
		    	<form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('website/ad_new');?>" method="post">
		    		<?php CsrfProtector::genHiddenField(); ?>
			    	<table id="select_single" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
			            <thead>
				            <tr role="row">
				            	<th></th>
				                <th>圖檔</th>
				                <th>人物資訊</th>
				                <th>事件名稱</th>
				                <th>拍攝時間</th>
				                <th>拍攝地點</th>
				                <th>排序</th>
				            </tr>
			            </thead>
			            <tbody> 

			            </tbody>
			        </table>
			        <div class="form-group">
		                <div class="col-sm-12">
		                    <button type="submit" class="btn btn-primary col-sm-12">新增</button>
		                </div>
		            </div>
			    </form>
		    </div>
		</div>
   	</div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
	var search_result = select_single = '';

	function init_datatable(){
		search_result = $('#search_single_result').DataTable( {
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何資料"
            }
        });
	}

    function unselect_image(a){
    	select_single.row($(a).parents('tr')).remove().draw();
    	search_result.row.add([
        	'<input type="checkbox" value="' +$(a).parents('tr').children('td:eq(1)').text()+ '" onclick="select_image(this)"><input type="hidden" value="' +$(a).parents('tr').children('td:eq(1)').text()+ '" name="single_id[]">',
            '<img width="200" src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL?>' +$(a).parents('tr').children('td:eq(1)').text()+ '.jpg"/><br><center>' +$(a).parents('tr').children('td:eq(1)').text()+ '</center>',
            $(a).parents('tr').children('td:eq(2)').text(),
            $(a).parents('tr').children('td:eq(3)').text(),
            $(a).parents('tr').children('td:eq(4)').text(),
            $(a).parents('tr').children('td:eq(5)').text()
        ]).draw( false );
    }

	function select_image(a){
		search_result.row($(a).parents('tr')).remove().draw();
		select_single.row.add([
        	'<input type="checkbox" value="' +$(a).parents('tr').children('td:eq(1)').text()+ '" onclick="unselect_image(this)" checked><input type="hidden" value="' +$(a).parents('tr').children('td:eq(1)').text()+ '" name="single_id[]">',
            '<img width="200" src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL?>' +$(a).parents('tr').children('td:eq(1)').text()+ '.jpg"/><br><center>' +$(a).parents('tr').children('td:eq(1)').text()+ '</center>',
            $(a).parents('tr').children('td:eq(2)').text(),
            $(a).parents('tr').children('td:eq(3)').text(),
            $(a).parents('tr').children('td:eq(4)').text(),
            $(a).parents('tr').children('td:eq(5)').text(),
            '<input type="number" class="form-control" name="sort[]" placeholder="請輸入排序" value="0">'
        ]).draw( false );
	}

	$(document).ready(function() {
		init_datatable();
		select_single = $('#select_single').DataTable( {
	        "lengthChange": false,
	        "oLanguage": {
	            "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
	            "sEmptyTable": "無任何資料"
	        }
	    });
		$('#search_single').click(function(){
            $.ajax({
                type:"GET",
                url: '<?php echo Yii::app()->createUrl('website/findsingle'); ?>',
                data: {
                    single_id:$('#single_id').val(),
                    category_id:$('#category_id').val(),
                    keyword:$('#keyword').val()
                },
                success:function(data){
                   result = JSON.parse(data)
                    if(result.status == true){
                    	var table_row = "";
                        $.each(result.data, function(index, value){
                        	table_row += '<tr><td><input type="checkbox" value="' +value.single_id+ '" onclick="select_image(this)"></td><td><img width="200" src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL?>' +value.single_id+ '.jpg"/><br><center>' +value.single_id+ '</center></td><td>'+value.people_info+'</td><td>'+value.object_name+'</td><td>'+value.filming_date+'</td><td>'+value.filming_location+'</td></tr>';
						});
						$('#search_single_result').DataTable().clear().destroy();
						$('#search_single_result').append(table_row);
						init_datatable();
                    }
                }
            });
        });
         
	})
</script>