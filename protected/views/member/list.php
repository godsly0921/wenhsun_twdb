<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">會員列表</h3>
        <?php foreach ($session_jsons as $jsons) : ?>
            <?php if ($jsons["power_controller"] == 'member/create') : ?>
                <a href="<?php echo Yii::app()->createUrl('member/create'); ?>" class="btn btn-default btn-right">新增會員帳號</a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <table id="memberTable" width=100% class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
                <tr role="row">
                    <th>帳號</th>
                    <th>姓名</th>
                    <th>會員類型</th>
                    <th>性別</th>
                    <th>生日</th>
                    <th>國別</th>
                    <th>手機</th>
                    <th>是否啟用</th>
                    <th>註冊類型</th>
                    <th>註冊時間</th>
                    <th>異動時間</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($datas != null) : ?>
                <?php foreach ($datas as $value) : ?>
                    <tr class="gradeC" role="row">
                        <td><?= $value->account ?></td>
                        <td><?= $value->name ?></td>
                        <td>
                            <?php if ($value->member_type == '1') : ?>
                                <?= '一般' ?>
                            <?php elseif ($value->member_type == '2') : ?>
                                <?= 'VIP' ?>
                            <?php else : ?>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($value->gender == 'F') : ?>
                                <?= '女生' ?>
                            <?php elseif ($value->gender == 'M') : ?>
                                <?= '男生' ?>
                            <?php else : ?>
                                <?= '未設定' ?>
                            <?php endif ?>
                        </td>
                        <td><?= $value->birthday ?></td>
                        <td><?= $value->nationality ?></td>
                        <td><?= $value->mobile ?></td>
                        <td>
                            <?php if ($value->active == 'Y') : ?>
                                <?= '是' ?>
                            <?php elseif ($value->active == 'N') : ?>
                                <?= '否' ?>
                            <?php endif ?>
                        </td>
                        <td><?= $value->account_type ?></td>
                        <td><?= $value->create_date ?></td>
                        <td><?= $value->update_date ?></td>
                        <td>
                            <?php foreach ($session_jsons as $jsons) : ?>
                                <?php if ($jsons["power_controller"] == 'member/update') : ?>
                                    <a class="oprate-right" href="<?php echo Yii::app()->createUrl('member/update') ?>/<?= $value->id ?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                                <?php endif; ?>
                                <?php if ($jsons["power_controller"] == 'member/delete') : ?>
                                    <a class="oprate-right oprate-del" data-mem-id="<?= $value->id ?>" data-mem-name="<?= $value->name ?>"><i class="fa fa-times fa-lg"></i></a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#memberTable').DataTable({
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {
                    "sFirst": "第一頁",
                    "sPrevious": "上一頁",
                    "sNext": "下一頁",
                    "sLast": "最後一頁"
                },
                "sEmptyTable": "無任何會員資料"
            }
        });
    });

    $(".oprate-del").on('click', function() {
        var id = $(this).data("mem-id");
        var memName = $(this).data("mem-name");
        var answer = confirm("確定要刪除 (" + memName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "POST");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('member/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', id);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>