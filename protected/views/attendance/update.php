<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">出勤日修改</h3>
    </div>
</div>

<?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                <li><?= $error[0] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl(Yii::app()->controller->id.'/update'); ?>" method="post"
      enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <input type="hidden" name="id" value="<?= $model->id ?>">
                <label for="phone" class="col-sm-2 control-label">新增出勤日:</label>
                <div class="col-sm-5">
                    <input type="text" id="day" class="form-control" name="day" value="<?= $model->day ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">是否為出勤日</label>
                <div class="col-sm-5">
                    <label class="radio-inline">
                        <input type="radio" name="type"
                               value="1" <?php echo ($model->type == 1) ? 'checked="checked"' : '' ?>>出勤日
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="type"
                               value="0" <?php echo ($model->type == 0) ? 'checked="checked"' : '' ?>>非出勤日
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">描述:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="description"
                           value="<?= $model->description ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">修改</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(function () {
        if ($('#success_msg').html() != '') {
            $('#success_msg').show().fadeOut(2000)
        }
    })

    $(function () {
        if ($('#error_msg').html() != '') {
            $('#error_msg').show().fadeOut(2000)
        }
    })
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

