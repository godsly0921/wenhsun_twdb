<!-- bootstrap-daterangepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<style type="text/css">
    .form-check-inline{
        display: inline-block;
        padding-top: 8px;
        padding-right: 5px;
    }
</style>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">紀州庵班別管理</h3>
    </div>
</div>
<?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                <li><?= $error[0] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if(isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
<div class="alert alert-success">
<strong>新增成功!</strong><?=Yii::app()->session['success_msg'];?>
</div>
<?php endif; ?>

<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>
<div class="panel panel-default">
    <div class="panel-heading">紀州庵班別管理</div>
    <div class="panel-body">
        <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('schedule/shift_update');?>/<?=$shift->shift_id?>" method="post" enctype="multipart/form-data">
            <?php CsrfProtector::genHiddenField(); ?>
            <div class="form-group row">
                <label class="col-sm-2 control-label">館別:</label>
                <div class="col-sm-10">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="store_id" <?=$shift->store_id == "1" ? "checked":""?> value="1">一般館舍
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="store_id" <?=$shift->store_id == "2" ? "checked":""?> value="2">茶館
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">場別:</label>
                <div class="col-sm-10">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="in_out" <?=$shift->in_out == "0" ? "checked":""?> value="0">不分
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="in_out" <?=$shift->in_out == "1" ? "checked":""?> value="1">內場
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="in_out" <?=$shift->in_out == "2" ? "checked":""?> value="2">外場
                        </label>
                    </div>
                </div>
            </div>     
            <div class="form-group row">
                <label class="col-sm-2 control-label">班別:</label>
                <div class="col-sm-10">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="class" <?=$shift->class == "A" ? "checked":""?> value="A">A
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="class" <?=$shift->is_special == "B" ? "checked":""?> value="B">B
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">是否為特殊上班時間:</label>
                <div class="col-sm-10">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="is_special" <?=$shift->is_special == "0" ? "checked":""?> value="0">否
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="is_special" <?=$shift->is_special == "1" ? "checked":""?> value="1">是
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">上班時間:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control has-feedback-left datetime_start" id="date_start" name="start_time" required placeholder="請輸入上班時間" value="<?=$shift->start_time?>" aria-describedby="inputSuccess2Status2">
                    <span class="glyphicon glyphicon-time form-control-feedback left" aria-hidden="true"></span>
                    <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">下班時間:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control has-feedback-left datetime_end" id="date_end" name="end_time" required placeholder="請輸入下班時間" value="<?=$shift->end_time?>" aria-describedby="inputSuccess2Status2">
                    <span class="glyphicon glyphicon-time form-control-feedback left" aria-hidden="true"></span>
                    <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary col-sm-12">修改</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->    
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
        $('.datetime_start').datetimepicker({
            format: "H:mm"
        });
        $('.datetime_end').datetimepicker({
            format: "H:mm",
            useCurrent: false //Important! See issue #1075
        });
        $(".datetime_start").on("dp.change", function (e) {
            $('.datetime_end').data("DateTimePicker").minDate(e.date);
        });
        $(".datetime_end").on("dp.change", function (e) {
            $('.datetime_start').data("DateTimePicker").maxDate(e.date);
        });       
    })
</script>