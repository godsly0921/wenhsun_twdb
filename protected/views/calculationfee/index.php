<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">儀器計算方式列表</h3>
        <?php foreach ($session_jsons as $jsons): ?>
            <?php if ($jsons["power_controller"] == 'calculationfee/create'): ?>
                <a href="<?php echo Yii::app()->createUrl('calculationfee/create'); ?>"
                   class="btn btn-default btn-right">批次新增</a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th>項目編號</th>
                                <th>儀器名稱</th>
                                <th>第一層單位</th>
                                <th>每個基數分鐘</th>
                                <th>每個基數收費</th>
                                <th>開機費基數</th>
                                <th>最大使用基數</th>
                                <th>預約未使用基數</th>
                                <th>建檔人</th>
                                <th>操作選項</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($model as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <td><?= $value->id ?></td>
                                    <td>
                                        <?php foreach ($device as $v):?>
                                            <?php if($value->device_id  == $v->id):?>
                                                <?= $v->name ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($level_one_all as $v):?>
                                            <?php if($value->level_one_id  == $v->id):?>
                                                <?= $v->name ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><?= $value->base_minute ?></td>
                                    <td><?= $value->base_charge ?></td>
                                    <td><?= $value->start_base_charge ?></td>
                                    <td><?= $value->max_use_base ?></td>
                                    <td><?= $value->unused_base ?></td>
                                    <td>
                                        <?php foreach ($account as $v):?>
                                            <?php if($value->builder  == $v->id):?>
                                                <?= $v->account_name ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($session_jsons as $jsons): ?>

                                            <?php if ($jsons["power_controller"] == 'calculationfee/update'): ?>
                                                <a class="oprate-right"
                                                   href="<?php echo Yii::app()->createUrl('calculationfee/update') ?>/<?= $value->id ?>"><i
                                                        class="fa fa-pencil-square-o fa-lg"></i></a>
                                            <?php endif; ?>

                                            <?php if ($jsons["power_controller"] == 'calculationfee/delete'): ?>
                                                <a class="oprate-right oprate-del" data-calculationfee-id="<?= $value->id ?>"
                                                  data-calculationfee-id="<?= $value->device_id ?>"><i
                                                        class="fa fa-times fa-lg"></i></a>
                                            <?php endif; ?>

                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script
    src="https://blackrockdigital.github.io/startbootstrap-sb-admin-2/vendor/datatables-responsive/dataTables.responsive.js"></script>
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
    $('#myTable').DataTable({
        responsive: true,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1}
        ]
    });
    $(document).ready(function () {
        $('#calculationfeeTable').DataTable({
            "scrollX": true,
            "lengthChange": true,
            "paging": true,
            "info": true,
            "order": [[4, "desc"], [0, "asc"]],
            "columnDefs": [{"targets": 5, "orderable": false}],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        });
    });
    $(".oprate-del").on('click', function () {
        var calculationfeeId = $(this).data("calculationfee-id");
        var answer = confirm("確定要刪除 (" + calculationfeeId + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('calculationfee/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', calculationfeeId);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>