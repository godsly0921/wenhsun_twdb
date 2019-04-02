<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">門禁異常紀錄表</h3>

    </div>
</div>

<?php foreach ($session_jsons as $jsons): ?>
    <?php if ($jsons["power_controller"] == 'record/get_abnormal_list'): ?>
        <div class="panel panel-default">
            <div class="panel-heading">查詢條件設定</div>
            <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('record/get_abnormal_list'); ?>"
                  method="post">


                <div class="panel-body">

                    <div class="form-group">
                        <label for="date_start" class="col-sm-2 control-label">查詢日期(起):</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="datepicker_start" name="start"
                                   placeholder="請開始日期">
                        </div>

                        <label for="date_start" class="col-sm-2 control-label">查詢日期(迄):</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="datepicker_end" name="end"
                                   placeholder="請結束日期">
                        </div>
                    </div>

                    <div class=form-group col-md-12">
                        <label class="col-sm-2 control-label">關鍵字:</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="keyword"
                                   placeholder="請輸入查詢關鍵字">
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="col-sm-2 control-label">篩選欄位:</label>
                        <div class="col-sm-5">
                            <label class="radio-inline">
                                <input type="radio" name="status" value="0" checked="checked">使用者姓名
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="1">卡號
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-md-12">

                        <label for="sort" class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-default">查詢</button>
                        </div>
                    </div>

                </div>
            </form>

        </div>

    <?php endif; ?>
<?php endforeach; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <div class="panel-heading col-md-12">

                <div class='col-md-2'>
                    <form class="form-horizontal"
                          action="<?php echo Yii::app()->createUrl('record/get_abnormal_list_excel'); ?>" method="post">
                        <button type="submit" class="btn btn-default">匯出excel</button>

                    </form>
                </div>

                <div class='col-md-2'>
                    <a href="<?= Yii::app()->createUrl('record/get_abnormal_list_printer'); ?>" target="_blank">
                        <button class="btn btn-default">列印</button>
                    </a>
                </div>

            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer"
                               role="grid">
                            <thead>
                            <tr role="row">
                                <th>日期</th>
                                <th>使用者</th>
                                <th>門禁分類</th>
                                <th>卡號</th>
                                <th>門禁刷卡時間</th>
                                <th>異常描述</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($model != 0): ?>
                                <?php foreach ($model as $key => $value): ?>
                                    <tr class="gradeC" role="row">
                                        <td><?= $value->date?></td>
                                        <td><?= $value->user_name?></td>
                                        <td><?= $value->station_name?></td>
                                        <td><?= $value->card_number?></td>
                                        <td><?= $value->card_time?></td>
                                        <td><?= $value->exception_description?></td>
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
                "sEmptyTable": "查詢設定無發現任何資料"
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