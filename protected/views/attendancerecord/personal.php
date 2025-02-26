<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">個人出缺勤一覽表</h3>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <!-- 參數選擇 -->
        <div class="panel panel-default">
            <div class="panel-heading">查詢條件設定</div>
            <div class="panel-body">
                <form action="<?php echo Yii::app() -> createUrl(Yii::app()->controller->id.'/personal'); ?>"  method="POST" >
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
                    
                    <div class='col-md-2'>
                        <?php foreach ($session_jsons as $jsons):?>
                            <?php if ($jsons["power_controller"] == Yii::app()->controller->id.'/getexcel'):?>
                                <form class="form-horizontal" action="<?php echo Yii::app()->createUrl(Yii::app()->controller->id.'/getexcel');?>" method="post">
                                    <button type="submit" class="btn btn-default">匯出excel</button>

                                </form>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>
                    
                    <div class='col-md-2'>
                        <?php foreach ($session_jsons as $jsons):?>
                            <?php if ($jsons["power_controller"] == Yii::app()->controller->id.'/printer'):?>
                                <a href="<?=Yii::app()->createUrl(Yii::app()->controller->id.'/printer');?>"  target="_blank">
                                    <button class="btn btn-default">列印</button>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                <div class='col-md-2 col-sm-4 col-xs-4'>
                    
                </div>                
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="table" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th>員工帳號</th>
                                <th>員工姓名</th>
                                <th>出勤日</th>
                                <th>首筆出勤紀錄</th>
                                <th>末筆出勤紀錄</th>
                                <th>是否異常</th>
                                <th>說明</th>
                                <th>建立時間</th>
                                <?php foreach ($session_jsons as $jsons):?>

                                    <?php if ($jsons["power_controller"] == Yii::app()->controller->id.'/update'):?>
                                        <th>操作</th>
                                    <?php endif; ?>

                                <?php endforeach; ?>

                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rcdata as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <td><?=$value['user_name']?></td>
                                    <td><?=$value['name']?></td>
                                    <td><?=$value['day']?></td>
                                    <td><?=$value['first_time']?></td>
                                    <td><?=$value['last_time']?></td>
                                    <td><?=$value['abnormal_type']?></td>
                                    <td><?=$value['abnormal']?></td>
                                    <td><?=$value['att_create_at']?></td>
                                        <?php foreach ($session_jsons as $jsons):?>
                                            <?php if ($jsons["power_controller"] == Yii::app()->controller->id.'/update'):?>
                                            <td>
                                                <a class="oprate-right" href="<?php echo Yii::app()->createUrl(Yii::app()->controller->id.'/update') ?>/<?= $value['attendance_record_id']?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                                            </td>
                                            <?php endif; ?>
                                        <?php endforeach; ?>

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
        $('#table').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何出缺勤資料"
            }
        } );
    } );
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
