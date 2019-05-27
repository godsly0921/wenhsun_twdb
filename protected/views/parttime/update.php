<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">特殊權限設定(預約取消)</h3>
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

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('reservation/update'); ?>" method="post"
      enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <input type="hidden" name="id" value="<?= $model->id ?>">
                    <label class="col-sm-2 control-label">儀器名稱:</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="device_id">
                            <?php foreach($devices as $key=>$value): ?>
                                <?php if($model->device_id == $value->id):?>
                                    <option selected="selected" value="<?= $value->id ?>">
                                        <?= $value->name ?>
                                    </option>
                                <?php else: ?>
                                    <option disabled="disabled" value="<?= $value->id ?>">
                                        <?= $value->name ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">預約開始時間:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="start_time" placeholder="預約開始時間"
                           value="<?= $model->start_time ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">預約結束時間:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="end_time" placeholder="預約結束時間"
                           value="<?= $model->end_time?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">預約狀態:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="status">
                        <?php foreach($categorys as $key=>$value): ?>
                            <?php if($model->status == $key):?>
                                <option selected="selected" value="<?= $key ?>">
                                    <?= $value ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $key ?>">
                                    <?= $value ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">取消原因</label>
                <div class="col-sm-5">
                     <textarea class="form-control" name="remark" placeholder="請輸入取消原因"><?= $model->remark ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">預約者:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="builder">
                        <?php foreach($members as $key=>$value): ?>
                            <?php if($model->builder == $value->id):?>
                                <option selected="selected" value="<?= $value->id ?>">
                                    <?= $value->name ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $value->id ?>" disabled="disabled">
                                    <?= $value->name ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php foreach($accounts as $key=>$value): ?>
                            <?php if($model->builder == $value->id):?>
                                <option selected="selected" value="<?= $value->id ?>">
                                    <?= $value->account_name ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $value->id ?>" disabled="disabled">
                                    <?= $value->account_name ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">取消者:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="canceler">
                        <?php foreach($accounts as $key=>$value): ?>
                            <?php if($model->canceler == $value->id):?>
                                <option selected="selected" value="<?= $value->id ?>">
                                    <?= $value->account_name ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $value->id ?>">
                                    <?= $value->account_name ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
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

