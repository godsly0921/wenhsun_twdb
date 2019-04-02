<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">地點名稱列表</h3>
        <?php foreach ($session_jsons as $jsons): ?>
            <?php if ($jsons["power_controller"] == 'local/create'): ?>
                <a href="<?php echo Yii::app()->createUrl('local/create'); ?>" class="btn btn-default btn-right">新增名稱</a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer"
               role="grid">
            <thead>
            <tr role="row">
                <!--<th>狀態編號</th>-->
                <th>狀態名稱</th>
                <th>是否開啟</th>
                <th>新增時間</th>
                <th>異動時間</th>
                <th>操作選項</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $key => $value): ?>
                <tr class="gradeC" role="row">
                    <!--<td><?= $value->id ?></td>-->
                    <td><?= $value->name ?></td>
                    <td><?php echo ($value->status == 1) ? "開啟" : "關閉" ?></td>
                    <td><?= $value->create_date ?></td>
                    <td><?= $value->edit_date ?></td>
                    <td>
                        
                                <a class="oprate-right" href="<?php echo Yii::app()->createUrl('local/update') ?>/<?= $value->id ?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                            
                                <a class="oprate-right oprate-del" data-local-id="<?=$value->id?>" data-local-name="<?=$value->name?>"><i class="fa fa-times fa-lg"></i></a>
                            
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
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
<script>    
    $(document).ready(function () {
        $('#localTable').DataTable({
            "lengthChange": false,
            "paging": true,
            "responsive": true,
            "info": false,
            "order": [[4, "desc"], [0, "asc"]],
            "columnDefs": [{"targets": 5, "orderable": false}],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        });
    });
    $(".oprate-del").on('click', function () {
        var localId = $(this).data("local-id");
        var localName = $(this).data("local-name");
        var answer = confirm("確定要刪除 (" + localName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('local/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', localId);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>