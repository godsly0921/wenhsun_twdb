<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">門禁計費方式新增</h3>
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

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('door/create'); ?>" method="post"
      enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">門禁名稱(中文):</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="name" placeholder="請輸入名稱"
                           value="">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">門禁名稱(英文):</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="en_name" placeholder="請輸入名稱"
                           value="">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">放置地點:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="position" placeholder="請輸入名稱"
                           value="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">是否開啟</label>
                <div class="col-sm-5">
                    <label class="radio-inline">
                        <input type="radio" name="status"
                               value="1" checked="checked">開啟
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status"
                               value="0" >關閉
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">站號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="station" placeholder="請輸入名稱"
                           value="">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">價格:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="price" placeholder="請輸入名稱"
                           value="">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">新增</button>
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

