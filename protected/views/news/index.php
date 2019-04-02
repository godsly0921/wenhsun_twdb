<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">公布欄管理</h3>
        <?php foreach ($session_jsons as $jsons): ?>
            <?php if ($jsons["power_controller"] == 'news/create'): ?>
                <a href="<?php echo Yii::app()->createUrl('news/create'); ?>" class="btn btn-default btn-right">新增消息</a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <table id="specialcaseTable" width="100%"
               class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>標題</th>
                <th>上架</th>
                <th>時間</th>
                <th>建檔人</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($news as $key => $value): ?>
                <tr class="gradeC" role="row">
                    <td><?= $value->new_title ?></td>
                    <td><?php echo ($value->new_type == 1) ? "是" : "否" ?></td>
                    <td class="sort"><?= $value->new_createtime ?></td>
                    <?php $account_name = '';
                    foreach ($account as $v):?>
                        <?php if ($value->	builder == $v->id): ?>
                            <?php $account_name = $v->account_name ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td><?= $account_name ?></td>
                    <td>                        <?php foreach ($session_jsons as $jsons): ?><?php if ($jsons["power_controller"] == 'news/update'): ?>
                            <a class="oprate-right"
                               href="<?php echo Yii::app()->createUrl('news/update') ?>/<?= $value->id ?>"><i
                                    class="fa fa-pencil-square-o fa-lg"></i></a>                            <?php endif; ?><?php if ($jsons["power_controller"] == 'news/delete'): ?>
                            <a class="oprate-right oprate-del" data-news-id="<?= $value->id ?>"
                               data-news-name="<?= $value->new_title ?>"><i
                                    class="fa fa-times fa-lg"></i></a>                            <?php endif; ?><?php endforeach; ?>
                    </td>
                </tr>            <?php endforeach; ?>            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#specialcaseTable').DataTable({
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#newsTable').DataTable({
            "lengthChange": false,
            "paging": true,
            "responsive": true,
            "info": false,
            "order": [[3, "asc"]],
            "columnDefs": [{"targets": 5, "orderable": false}],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何資料"
            }
        });
    });
    $(".oprate-del").on('click', function () {
        var newsId = $(this).data("news-id");
        var newsName = $(this).data("news-name");
        var answer = confirm("確定要刪除 (" + newsName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('news/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', newsId);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });</script>