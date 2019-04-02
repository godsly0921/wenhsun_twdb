<div class="row">    
	<div class="title-wrap col-lg-12">        
		<h3 class="title-left">系統列表</h3>        
		<a href="<?php echo Yii::app()->createUrl('system/create'); ?>" class="btn btn-default btn-right">新增系統</a>   
	</div>
</div><?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']);?><div class="panel panel-default" style="width=100%; overflow-y:scroll;">    
	<div class="panel-body">        
		<table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">            
			<thead>            
				<tr role="row">               
				 <!--<th>系統編號</th>-->                
				 <th>系統名稱</th>                
				 <th>Controller</th>                
				 <th>排序</th>                
				 <th>系統狀態</th>                
				 <th>操作</th>            
				</tr>            
			</thead>            
			<tbod>                <?php foreach($systems as $system): ?>                    <tr>                        <!--<td><?=$system->system_number?></td>-->                        <td><?=$system->system_name?></td>                        <td><?=$system->system_controller?></td>                        <td><?=$system->system_range?></td>                        <td><?php echo $system->system_type == 1 ? "啟用" : "停用" ;?></td>                        <td>                        <?php foreach ($session_jsons as $jsons):?>                            <?php if ($jsons["power_controller"] == 'system/update'):?>                                <a class="oprate-right" href="<?php echo Yii::app()->createUrl('system/update') ?>/<?=$system->id?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>                            <?php endif; ?>                            <?php if ($jsons["power_controller"] == 'system/delete'):?>                                <a class="oprate-right oprate-del" data-sys-id="<?=$system->id?>" data-sys-name="<?=$system->system_name?>"><i class="fa fa-times fa-lg"></i></a>                            <?php endif; ?>                        <?php endforeach; ?>                        </td>                <?php endforeach; ?>            </tbod>        </table>    </div></div>
	<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
	<script>
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        } );
    } );
	</script>
	<script>    $(document).ready(function(){        $('#systemTable').DataTable({            "lengthChange": false,            "paging": true,            "responsive": true,            "info": false,            "order": [[ 3, "asc" ]],            "columnDefs": [ { "targets": 5, "orderable": false } ],            "oLanguage": {                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁","sNext": "下一頁","sLast": "最後一頁"},                "sEmptyTable": "無任何群組資料"            }        });    });    $(".oprate-del").on('click', function(){        var systemId = $(this).data("sys-id");        var systemName = $(this).data("sys-name");        var answer = confirm("確定要刪除 (" + systemName + ") ?");        if (answer == true) {            var form = document.createElement("form");            form.setAttribute('method',"post");            form.setAttribute('action', "<?php echo Yii::app()->createUrl('system/delete') ?>");            var input = document.createElement("input");            input.setAttribute('type', 'hidden');            input.setAttribute('name', '_token');            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");            var idInput = document.createElement("input");            idInput.setAttribute('type', 'hidden');            idInput.setAttribute('name' , 'system_id');            idInput.setAttribute('value', systemId);            form.appendChild(input);            form.appendChild(idInput);            document.body.appendChild(form);            form.submit();        }    });</script>