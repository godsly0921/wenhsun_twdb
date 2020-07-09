<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3>全體請假查詢</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])): ?>
                    <div id="error_alert" class="alert alert-danger alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::ERR_MSG_KEY];?>
                        <?php unset(Yii::app()->session[Controller::ERR_MSG_KEY]);?>
                    </div>
                <?php endif; ?>
                <!-- 參數選擇 -->
                <div class="panel panel-default">
                    <div class="panel-heading">查詢條件設定</div>
                    <div class="panel-body">
                        <form action="<?php echo Yii::app() -> createUrl('/leave/manager/all_hist'); ?>"  method="GET" >
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
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
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
    $(document).ready(function() {
        $("#datatable1").DataTable({
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {
                    "sFirst": "第一頁",
                    "sPrevious": "上一頁",
                    "sNext": "下一頁",
                    "sLast": "最後一頁"
                },
                "sEmptyTable": "無任何請假資料"
            },
            "order": [
                [0, 'desc']
            ]
        });
    });
</script>

