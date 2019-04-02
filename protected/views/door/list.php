<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">門組管理列表</h3>
        <?php foreach ($session_jsons as $jsons): ?>
            <?php if ($jsons["power_controller"] == 'door/create'): ?>
                <a href="<?php echo Yii::app()->createUrl('door/create'); ?>" class="btn btn-default btn-right">新增門組設定</a>
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
                <th>門禁中文名稱</th>
                <th>門禁英文名稱</th>
                <th>放置地點</th>
                <th>門禁狀態</th>
                <th>門禁站號</th>
                <th>價格</th>
                <th>建檔人</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $key => $value): ?>
                <tr class="gradeC" role="row">
                    <td><?= $value->name ?></td>
                    <td><?= $value->en_name ?></td>
                    <td>
                        <?php foreach($local as $k=>$v): ?>
                            <?php if($k == $value->position):?>
                                <?= $v->name ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>
                    <td><?php echo ($value->status == 0) ? "開啟" : "關閉" ?></td>
                    <td><?= $value->station ?></td>
                    <td><?= $value->price ?></td>
                    <td>
                        <?php foreach($accounts as $k=>$v): ?>
                            <?php if($k == $value->builder):?>
                                <?= $v->account_name ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($session_jsons as $jsons):?>

                            <?php if ($jsons["power_controller"] == 'door/update'):?>
                                <a class="oprate-right" href="<?php echo Yii::app()->createUrl('door/update') ?>/<?= $value->id ?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                            <?php endif; ?>

                            <?php if ($jsons["power_controller"] == 'door/delete'):?>
                                <a class="oprate-right oprate-del" data-door-id="<?=$value->id?>" data-door-name="<?=$value->name?>"><i class="fa fa-times fa-lg"></i></a>
                            <?php endif; ?>

                        <?php endforeach; ?>
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
                "sEmptyTable": "無任何門組資料"
            }
        } );
    } );
</script>
<script>    
    $(document).ready(function () {
        $('#doorTable').DataTable({
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
        var doorId = $(this).data("door-id");
        var doorName = $(this).data("door-name");
        var answer = confirm("確定要刪除 (" + doorName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('door/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', doorId);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>