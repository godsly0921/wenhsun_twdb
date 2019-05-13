<!-- bootstrap-daterangepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">優惠管理</h3>
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
    <div class="panel-heading">優惠管理</div>
    <div class="panel-body">
        <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('coupon/new');?>" method="post">
            <?php CsrfProtector::genHiddenField(); ?>
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">優惠名稱:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="coupon_name" name="coupon_name" placeholder="請輸入優惠名稱" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">折扣代號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="請輸入折扣代號" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">張數:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="coupon_pic" name="coupon_pic" placeholder="請輸入張數" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="single_cal2">開始時間
                </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control has-feedback-left datetime" name="start_time" placeholder="開始時間" aria-describedby="inputSuccess2Status2">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="single_cal2">結束時間
                </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control has-feedback-left datetime" name="end_time" placeholder="結束時間" aria-describedby="inputSuccess2Status2">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                </div>
            </div>      
            <div class="form-group">
                <label class="col-sm-2 control-label">啟用:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="status" required>                      
                        <option value="1">是</option>
                        <option value="0">否</option>
                    </select>
                </div>
            </div>   
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">新增</button>
                </div>
            </div>
        </form>
    </div>        
</div>
<!-- bootstrap-daterangepicker -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->    
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.datetime').daterangepicker({
            singleDatePicker: true,
            singleClasses: "picker_2",
            locale: {
                format: 'YYYY-MM-DD'
            }
        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    })
</script>