<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">管理者信箱設定</h3>
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

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('mail/update'); ?>" method="post"
      enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <input type="hidden" name="id" value="<?= $model->id ?>">
                <label for="name" class="col-sm-2 control-label">伺服器IP或網域:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="mail_server" placeholder="請輸入伺服器IP或網域"
                           value="<?= $model->mail_server ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">寄件人:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="sender" placeholder="請輸入寄件人"
                           value="<?= $model->sender ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">收件人1:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="addressee_1" placeholder="請輸入收件人1" value="<?= $model->addressee_1 ?>">
                </div>
            </div>


            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">收件人2:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="addressee_2" placeholder="請輸入收件人2" value="<?= $model->addressee_2 ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">收件人3:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="addressee_3" placeholder="請輸入收件人3" value="<?= $model->addressee_3 ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">設定</button>
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

