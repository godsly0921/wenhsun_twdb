<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">特殊權限設定(預約)</h3>

    </div>
</div>

<?php foreach ($session_jsons as $jsons): ?>
    <?php if ($jsons["power_controller"] == 'reservation/get_special_list'): ?>
        <div class="panel panel-default">
            <div class="panel-heading">查詢條件設定</div>
            <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('reservation/get_special_list'); ?>"
                  method="post">


                <div class="panel-body">

                    <div class="form-group">
                        <label for="date_start" class="col-sm-2 control-label">查詢日期(起):</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="datepicker_start" name="start_time"
                                   placeholder="請開始日期">
                        </div>

                        <label for="date_start" class="col-sm-2 control-label">查詢日期(迄):</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="datepicker_end" name="end_time"
                                   placeholder="請結束日期">
                        </div>


                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">查詢儀器:</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="device_id">
                                <option value="0" selected="selected">全部選擇</option>
                                <?php foreach ($devices as $key => $value): ?>
                                    <option value="<?= $value->id ?>">
                                        <?= $value->name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-default">查詢</button>
                    </div>

                </div>
            </form>

        </div>

    <?php endif; ?>
<?php endforeach; ?>

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
                                <th>儀器名稱</th>
                                <th>預約人</th>
                                <th>預約開始時間</th>
                                <th>預約結束時間</th>
                                <th>是否正常使用</th>
                                <!--<th>備註</th>-->
                                <th>申請日</th>
                                <th>異動日</th>
                                <?php foreach ($session_jsons as $jsons):?>
                                    <?php if ($jsons["power_controller"] == 'reservation/update'):?>
                                        <th>操作</th>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($model!=0):?>
                            <?php foreach ($model as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <td>
                                        <?php foreach ($devices as $k => $v): ?>
                                            <?php if ($v->id == $value->device_id): ?>
                                                <?= $v->name ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php if ($value->builder_type == 1):?>
                                            <?php foreach ($members as $k => $v): ?>
                                                <?php if ($v->id == $value->builder): ?>
                                                    <?= $v->name ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else:?>
                                            <?php foreach ($accounts as $k => $v): ?>
                                                <?php if ($v->id == $value->builder): ?>
                                                    <?= $v->account_name ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif;?>
                                    </td>
                                    <td><?= $value->start_time ?></td>
                                    <td><?= $value->end_time ?></td>
                                    <td><?php foreach ($categorys as $k => $v): ?>
                                            <?php if ($k == $value->status): ?>
                                                <?= $v ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?></td>
                                    <!--<td><? /*= $value->remark */ ?></td>-->
                                    <td><?= $value->create_time ?></td>
                                    <td><?= $value->modify_time ?></td>

                                    <td>
                                        <?php foreach ($session_jsons as $jsons):?>

                                            <?php if ($jsons["power_controller"] == 'reservation/update'):?>
                                                <a class="oprate-right" href="<?php echo Yii::app()->createUrl('reservation/update') ?>/<?= $value->id ?>">取消預約</a>
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
                "sEmptyTable": "查詢的日期無任何預約資料"
            }
        });
    });
</script>

<script>
    $(function () {
        $("#datepicker_start").datepicker({dateFormat: 'yy-mm-dd'}).val();
        $("#datepicker_end").datepicker({dateFormat: 'yy-mm-dd'}).val();
    });
</script>