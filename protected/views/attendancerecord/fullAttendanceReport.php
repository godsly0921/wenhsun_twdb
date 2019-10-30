<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">全勤記錄查詢</h3>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <!-- 參數選擇 -->
        <div class="panel panel-default">
            <div class="panel-heading">查詢條件設定</div>
            <div class="panel-body">
                <form action="<?php echo Yii::app() -> createUrl('attendancerecord/full'); ?>"  method="POST" >
                    <input type="hidden" name="filter" value="1">
                    <div class="form-group col-md-12">
                        <label for="date_start" class="col-sm-2 control-label">開始日期:</label>
                        <div class="col-sm-3">
                        <input type="text" class="form-control"  id="date_start" name="date_start" placeholder="請填入開始日期" >
                        </div>

                        <label for="date_start" class="col-sm-2 control-label">結束日期:</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control"  id="date_end" name="date_end" placeholder="請填入結束日期" >
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">

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

                <?php foreach ($session_jsons as $jsons):?>
                    <?php if ($jsons["power_controller"] == 'attendancerecord/getFullExcel'):?>
                        <div class='col-md-2'>
                            <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('attendancerecord/getFullExcel');?>" method="post">
                                <button type="submit" class="btn btn-default">匯出excel</button>

                            </form>
                        </div>
                    <?php endif;?>
                <?php endforeach;?>

                <!-- <?php foreach ($session_jsons as $jsons):?>
                    <?php if ($jsons["power_controller"] == 'doorrec/printer'):?>
                        <div class='col-md-2'>
                            <a href="<?=Yii::app()->createUrl('doorrec/printer');?>"  target="_blank">
                                <button class="btn btn-default">列印</button>
                            </a>
                        </div>
                    <?php endif;?>
                <?php endforeach;?> -->

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr role="row">
                              <!--  <th>刷卡地點</th>-->
                                <th>員工帳號</th>
                                <th>員工姓名</th>
                                <th>開始日期</th>
                                <th>結束日期</th>
                                <th>全勤天數</th>
                                <th>出勤總時數</th>
                                <th>出勤總天數</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rcdata as $value): ?>
                                <tr class="gradeC" role="row">
                                   <!-- <td><?/*=$value['position_name']*/?></td>-->
                                    <td><?=$value->id?></td>
                                    <td><?=$value->name?></td>
                                    <td><?=date('m/d/Y', $value->start_date)?></td>
                                    <td><?=date('m/d/Y', $value->end_date)?></td>
                                    <td>
                                        <?php
                                            if($value->a_normal_take == 0 && $value->b_normal_take == 0) {
                                                echo "A: 0,<br> B: 0";
                                            } else {
                                                echo "A: ".$value->a_normal_take ."  ("
                                                . floor(($value->a_normal_take * 100 ) / ($value->a_normal_take + $value->b_normal_take))
                                                . "%),<br> B: " . $value->b_normal_take ."  (" .(100 - floor(($value->a_normal_take * 100 ) / ($value->a_normal_take + $value->b_normal_take))) . "%)";
                                            }

                                    ?></td>
                                    <td><?=floor($value->minutes / 60 )?></td>
                                    <td><?=$value->inOfficeDays?></td>
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

<!-- bootstrap-daterangepicker -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
 <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何出勤資料"
            }
        } );
    } );
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

<script>
$('#date_start').daterangepicker({
singleDatePicker: true,
singleClasses: "picker_2",
locale: {
format: 'YYYY-MM-DD'
}
}, function(start, end, label) {
console.log(start.toISOString(), end.toISOString(), label);
});


$('#date_end').daterangepicker({
    singleDatePicker: true,
    singleClasses: "picker_2",
    locale: {
        format: 'YYYY-MM-DD'
    }
}, function(start, end, label) {
    console.log(start.toISOString(), end.toISOString(), label);
});
</script>
