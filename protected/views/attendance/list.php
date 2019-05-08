<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">出勤日設定列表</h3>
        <p>週一～至週五為系統預設出勤日～週六週日為系統非出勤日</p>
        <p>如五月一日為公司非出勤日需新增</p>
        <p>如本週六為補班日需新增</p>
        <?php foreach ($session_jsons as $jsons): ?>
            <?php if ($jsons["power_controller"] == Yii::app()->controller->id.'/create'): ?>
                <a href="<?php echo Yii::app()->createUrl(Yii::app()->controller->id.'/create'); ?>" class="btn btn-default btn-right">新增出勤日</a>
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
                <th>出勤日日期</th>
                <td>是否為出勤日</td>
                <td>說明</td>
                <th>新增時間</th>
                <th>更新時間</th>
                <th>選項</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $key => $value): ?>
                <tr class="gradeC" role="row">
                    <td><?= $value->day ?></td>
                    <td><?= ($value->type == 1) ? "出勤日" : "非出勤日" ?></td>
                    <td><?= $value->description ?></td>
                    <td><?= $value->create_at ?></td>
                    <td><?= $value->update_at ?></td>
                    <td>
                        
                                <a class="oprate-right" href="<?php echo Yii::app()->createUrl(Yii::app()->controller->id.'/update') ?>/<?= $value->id ?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                            
                                <a class="oprate-right oprate-del" data-attendance-id="<?=$value->id?>" data-attendance-name="<?=$value->day?>"><i class="fa fa-times fa-lg"></i></a>
                            
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
                "sEmptyTable": "查無資料, 快去新增資料吧"
            }
        } );
    } );
</script>
<script>
    $(".oprate-del").on('click', function () {
        var attendanceId = $(this).data("attendance-id");
        var attendanceName = $(this).data("attendance-name");
        var answer = confirm("確定要刪除 (" + attendanceName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl(Yii::app()->controller->id.'/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', attendanceId);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>