<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">地點名稱修改</h3>
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

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('local/update'); ?>" method="post"
      enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <input type="hidden" name="id" value="<?= $model->id ?>">
                <label for="name" class="col-sm-2 control-label">狀態名稱:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="name" placeholder="請輸入名稱"
                           value="<?= $model->name ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">是否開啟</label>
                <div class="col-sm-5">
                    <label class="radio-inline">
                        <input type="radio" name="status"
                               value="1" <?php echo ($model->status == 1) ? 'checked="checked"' : '' ?>>開啟
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status"
                               value="0" <?php echo ($model->status == 0) ? 'checked="checked"' : '' ?>>關閉
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">新增時間:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control slider_example_2" name="create_date"
                           value="<?= $model->create_date ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">異動時間:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control slider_example_2" name="edit_date"
                           value="<?= $model->edit_date ?>">
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

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery-ui-timepicker-addon.js">
</script>
<link rel="stylesheet" type="text/css"
      href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/jquery-ui-timepicker-addon.css"/>


<script>
    $(function () {
        $('.slider_example_2').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'hh:mm:ss',
            stepHour: 1,
            stepMinute: 1,
            stepSecond: 1,
            timezone: "+0800",
        });
    });
</script>
<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>

