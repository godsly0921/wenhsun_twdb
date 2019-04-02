<div class="row">
    <div class="title-wrap col-lg-12"><h3 class="title-left">功能列表</h3>        <a
            href="<?php echo Yii::app()->createUrl('power/create'); ?>" class="btn btn-default btn-right">新增功能</a></div>
</div>
<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <!--<th>功能編號</th>-->
                <th>功能名稱</th>
                <th>程式位置</th>
                <th>系統編號</th>
                <th>排序</th>
                <th>功能顯示</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>            <?php foreach ($powers as $power): ?>
                <tr class="gradeC" role="row">
                    <!--<td><?= $power->power_number ?></td>-->
                    <td><?= $power->power_name ?></td>
                    <td><?= $power->power_controller ?></td>
                    <td>                        <?php foreach ($systems as $system): ?><?php if ($power->power_master_number == $system->system_number): ?>                                <?= $system->system_name ?><?php endif; ?><?php endforeach; ?>                    </td>
                    <td><?= $power->power_range ?></td>
                    <td><?php echo ($power->power_display == 1) ? "顯示" : "隱藏" ?></td>
                    <td><a class="oprate-right"
                           href="<?php echo Yii::app()->createUrl('power/update') ?>/<?= $power->id ?>"><i
                                class="fa fa-pencil-square-o fa-lg"></i></a> <a class="oprate-right oprate-del"
                                                                                data-pow-id="<?= $power->id ?>"
                                                                                data-pow-neme="<?= $power->power_name ?>"><i
                                class="fa fa-times fa-lg"></i></a></td>
                </tr>            <?php endforeach; ?>            </tbody>
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
   /* $(document).ready(function () {
        var powerTable = $('#powerTable').DataTable({
            'iDisplayLength': 10,
            "lengthChange": false,
            "paging": true,
            "responsive": true,
            "info": false,
            "order": [[4, "desc"]],
            "columnDefs": [{"targets": 2, "orderable": false}],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何功能資料"
            }
        });
    });*/
    $(".oprate-del").on('click', function () {
        var powId = $(this).data("pow-id");
        var powName = $(this).data("pow-neme");
        var answer = confirm("確定要刪除 (" + powName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('power/delete') ?>/" + powId);
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });</script>