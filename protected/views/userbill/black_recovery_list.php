<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">黑名單復卡管理</h3>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <!-- 參數選擇 -->
        <div class="panel panel-default">
            <div class="panel-heading">查詢條件設定</div>
            <div class="panel-body">
                <form action="<?php echo Yii::app()->createUrl('member/get_black_recovery_list'); ?>" method="POST">

                    <div class="form-group">
                        <label for="date_start" class="col-sm-5 control-label">
                            查詢停卡日期(起):
                        </label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="datepicker_start" name="start_date"
                                   placeholder="請開始日期">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="date_start" class="col-sm-5 control-label">
                            查詢停卡日期(迄):</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="datepicker_end" name="end_date"
                                   placeholder="請結束日期">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label">第一層單位:</label>
                        <select class="form-control" id='grp1' name='grp1'>
                            <option value="0" selected="selected">---請選擇---</option>
                            <?php foreach ($grp_data as $grp_key => $grp_val): ?>
                                <option value="<?= $grp_val->id ?>"><?= $grp_val->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label">第二層單位:</label>
                        <select class="form-control" id='grp2' name='grp2'>
                            <option value="0" selected="selected">---請選擇---</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label">教授選擇:</label>
                        <select class="form-control" id='grp3' name='grp3'>
                            <option value="0" selected="selected">---請選擇---</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否已復卡:</label>
                        <div class="col-sm-5">
                            <label class="radio-inline">
                                <input type="radio" name="status" value="0">未復卡
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="1">已復卡
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sort" class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-default">查詢</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <!-- 參數選擇結束 -->

        <div class="panel panel-default">

            <div class="panel-heading col-md-12">
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th>筆數</th>
                                <th>停卡日期</th>
                                <th>使用者姓名</th>
                                <th>使用者卡號</th>
                                <th>第一層單位/第二層單位</th>
                                <th>所屬教授</th>
                                <th>備註</th>
                                <th>復卡人/復卡時間</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($datas != 0): ?>
                                <?php $i=1; foreach ($datas as $key => $value): ?>
                                    <tr class="gradeC" role="row">
                                        <td>
                                            <?= $i ?>
                                        </td>
                                        <td>
                                            <?= date("Y-m-d",strtotime($value->stop_card_datetime));?>
                                        </td>

                                        <td>
                                            <?= $value->name ?>
                                        </td>

                                        <td>
                                            <?= $value->card_number ?>
                                        </td>

                                        <td>
                                            <?php foreach ($grp_data as $grp_key => $grp_val): ?>
                                                <?php if($grp_val->id == $value->grp_lv1): ?>
                                                    <?= $grp_val->name ?>
                                                <?php endif; ?>
                                            <?php endforeach ?>
                                            <?='/'?>
                                            <?php foreach ($grp_data2 as $grp_key => $grp_val): ?>
                                                <?php if($grp_val->id == $value->grp_lv2): ?>
                                                    <?= $grp_val->name ?>
                                                <?php endif; ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td><?php foreach ($professor as $k => $v): ?>
                                                <?php if ($v->id == $value->professor): ?>
                                                    <?= $v->name ?>
                                                <?php endif; ?>
                                            <?php endforeach ?>
                                            <?php if ($value->professor == 0): ?>
                                                <?= '無教授' ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $value->stop_card_remark ?>
                                        </td>
                                        <td>
                                            <?php foreach ($accounts as $k => $v): ?>
                                                <?php if ($v->id == $value->stop_card_people): ?>
                                                    <?= $v->name ?>
                                                <?php endif; ?>
                                            <?php endforeach ?>
                                            <?php if ($value->stop_card_people == 0): ?>
                                                <?= '無復卡人' ?>
                                            <?php endif; ?>
                                            <?='/'?><?= $value->stop_card_datetime;?>
                                        </td>

                                    </tr>
                                <?php $i++; endforeach; ?>
                            <?php endif; ?>
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
    $(document).ready(function () {
        $('#specialcaseTable').DataTable({
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何使用者資料"
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

    $("#grp1").change(function () {
        // 如果分類一的值不等於0,才接著做計算
        if ($("#grp1").val() != 0) {

            // ajax傳送父分類的值去撈出所有子分類
            var request = $.ajax({
                url: "<?=Yii::app()->createUrl('/member/getgrp2')?>",
                method: "POST",
                data: {
                    grp1: $("#grp1").val()
                },
                dataType: "json"
            });

            request.done(function (msg) {

                // 清空子分類
                $("#grp2").empty();

                // 填充子分類
                $("#grp2").append("<option value='0' selected='selected'>---請選擇---</option>");
                $.each(msg, function (key, val) {
                    console.log(val);
                    $("#grp2").append("<option value=" + val['id'] + ">" + val['name'] + "</option>");
                });


            });

            request.fail(function (jqXHR, textStatus) {
                console.log("Request failed: " + textStatus);
            });

        }

    });
    // 使用者分類1改變時的動作結束

    $("#grp2").change(function () {
        // 如果分類一的值不等於0,才接著做計算
        if ($("#grp2").val() != 0) {


            // ajax傳送父分類的值去撈出所有子分類
            var request = $.ajax({
                url: "<?=Yii::app()->createUrl('/member/getgrp3')?>",
                method: "POST",
                data: {
                    grp2: $("#grp2").val()
                },
                dataType: "json"
            });

            request.done(function (msg) {

                // 清空子分類
                $("#grp3").empty();

                // 填充子分類
                $("#grp3").append("<option value='0' selected='selected'>---請選擇---</option>");
                $.each(msg, function (key, val) {
                    console.log(val);
                    $("#grp3").append("<option value=" + val['id'] + ">" + val['name'] + "</option>");
                });


            });

            request.fail(function (jqXHR, textStatus) {
                console.log("Request failed: " + textStatus);
            });

        }

    });
</script>