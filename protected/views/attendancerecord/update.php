<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">出勤異常單</h3>
    </div>
</div>

<?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== '') : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (Yii::app()->session['error_msg'] as $error) : ?>
                <li><?= $error[0] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== '') : ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl(Yii::app()->controller->id . '/update'); ?>" method="post" enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <input type="hidden" name="id" value="<?= $model->id ?>">
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">異常日期:</label>
                <div class="col-sm-5">
                    <input type="text" disabled class="form-control" name="reply_description" value="<?= $model->day  ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">員工帳號:</label>
                <div class="col-sm-5">
                    <input type="text" disabled class="form-control" value="<?= $employee->user_name  ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">員工姓名:</label>
                <div class="col-sm-5">
                    <input type="text" disabled class="form-control" value="<?= $employee->name  ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">假別選擇:</label>
                <div class="col-sm-5">

                    <select id="type" class="form-control" name="take">
                        <?php foreach ($data as $key => $val) : ?>
                            <?php if ($model->take == $key) : ?>
                                <option selected="selected" value="<?= $key ?>">
                                    <?= $val ?>
                                </option>
                            <?php else : ?>
                                <option value="<?= $key ?>">
                                    <?= $val ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div id="hours" class="form-group" <?php if ($model->take != 9 && $model->take != 11) : ?>style="display:none"<?php endif; ?>>
                <label class="col-sm-2 control-label">時數:</label>
                <div class="col-md-5">
                    <select class="form-control" id="leave_minutes" name="leave_minutes">
                        <option value="0"></option>
                        <option value="30" <?php if ($model->leave_minutes == 30) : ?>selected<?php endif; ?>>0.5 小時</option>
                        <option value="60" <?php if ($model->leave_minutes == 60) : ?>selected<?php endif; ?>>1 小時</option>
                        <option value="90" <?php if ($model->leave_minutes == 90) : ?>selected<?php endif; ?>>1.5 小時</option>
                        <option value="120" <?php if ($model->leave_minutes == 120) : ?>selected<?php endif; ?>>2 小時</option>
                        <option value="150" <?php if ($model->leave_minutes == 150) : ?>selected<?php endif; ?>>2.5 小時</option>
                        <option value="180" <?php if ($model->leave_minutes == 180) : ?>selected<?php endif; ?>>3 小時</option>
                        <option value="210" <?php if ($model->leave_minutes == 210) : ?>selected<?php endif; ?>>3.5 小時</option>
                        <option value="240" <?php if ($model->leave_minutes == 240) : ?>selected<?php endif; ?>>4 小時</option>
                        <option value="270" <?php if ($model->leave_minutes == 270) : ?>selected<?php endif; ?>>4.5 小時</option>
                        <option value="300" <?php if ($model->leave_minutes == 300) : ?>selected<?php endif; ?>>5 小時</option>
                        <option value="330" <?php if ($model->leave_minutes == 330) : ?>selected<?php endif; ?>>5.5 小時</option>
                        <option value="360" <?php if ($model->leave_minutes == 360) : ?>selected<?php endif; ?>>6 小時</option>
                        <option value="390" <?php if ($model->leave_minutes == 390) : ?>selected<?php endif; ?>>6.5 小時</option>
                        <option value="420" <?php if ($model->leave_minutes == 420) : ?>selected<?php endif; ?>>7 小時</option>
                        <option value="450" <?php if ($model->leave_minutes == 450) : ?>selected<?php endif; ?>>7.5 小時</option>
                        <option value="480" <?php if ($model->leave_minutes == 480) : ?>selected<?php endif; ?>>8 小時</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">出勤異常回覆:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="reply_description" value="<?= $model->reply_description ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">送出</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        if ($('#success_msg').html() != '') {
            $('#success_msg').show().fadeOut(2000)
        }
    })

    $(function() {
        if ($('#error_msg').html() != '') {
            $('#error_msg').show().fadeOut(2000)
        }
    })

    $("#type").on("change", function() {
        if (this.value == 9 || this.value == 11) {
            $("#hours").show();
        } else {
            $("#hours").hide();
            $("#leave_minutes").val(0);
        }
    });
</script>


<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script>
    $('#day').daterangepicker({
        singleDatePicker: true,
        singleClasses: "picker_2",
        locale: {
            format: 'YYYY-MM-DD'
        }
    }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });
</script>

<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>