<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">特殊狀況管理列表</h3>
        <?php foreach ($session_jsons as $jsons): ?>
            <?php if ($jsons["power_controller"] == 'specialcase/create'): ?>
                <a href="<?php echo Yii::app()->createUrl('specialcase/create'); ?>" class="btn btn-default btn-right">新增狀況</a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer"
                               role="grid">
                            <thead>
                            <tr role="row">
                                <!--<th>項目編號</th>-->
                                <th>標題</th>
                                <th>狀況分類</th>
                                <th>申請人</th>
                                <th>申請時間</th>
                                <th>審核狀態</th>
                                <th>審核時間</th>
                                <th>審核人</th>
                                <th>申請人IP</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($model as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <!--<td><?= $value->id ?></td>-->
                                    <td><?= $value->title ?></td>
                                    <td><?= $value->category ?></td>
                                    <td><?= $value->member_id ?></td>
                                    <td><?= $value->application_time ?></td>
                                    <td><?php echo ($value->approval_status == 1) ? "已審核" : "尚未審核" ?></td>
                                    <td><?= $value->approval_time ?></td>
                                    <td><?= $value->approval_account_id ?></td>
                                    <td><?= $value->member_ip ?></td>
                                    <td>
                                        <?php foreach ($session_jsons as $jsons): ?>
                                            <?php if ($jsons["power_controller"] == 'specialcase/update'): ?>
                                                <a class="oprate-right oprate-del"
                                                   data-specialcase-id="<?= $value->id ?>"
                                                   data-specialcase-name="<?= $value->id ?>"><i
                                                        class="fa fa-times fa-lg"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($jsons["power_controller"] == 'specialcase/delete'): ?>
                                                
                                                <a class="oprate-right"
                                               href="<?php echo Yii::app()->createUrl('specialcase/update') ?>/<?= $value->id ?>">
                                                <i class="fa fa-pencil-square-o fa-lg"></i>
                                                </a>
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
    /*$(document).ready(function () {
        $('#specialcaseTable').DataTable({
            "lengthChange": false,
            //"scrollX": true,
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
    });*/
    $(".oprate-del").on('click', function () {
        var specialcaseId = $(this).data("specialcase-id");
        var specialcaseName = $(this).data("specialcase-name");
        var answer = confirm("確定要刪除 (" + specialcaseName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('specialcase/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', specialcaseId);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });</script>