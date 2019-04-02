<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">特殊狀況修改</h3>
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

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('specialcase/update'); ?>" method="post"
      enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <input type="hidden" name="id" value="<?= $model->id ?>">
                <label for="name" class="col-sm-2 control-label">標題:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="title" placeholder="請輸入標題"
                           value="<?= $model->title ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">申請人:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="member_id" placeholder="請輸入申請人"
                           value="<?= $model->member_id ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">申請時間:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control slider_example_2" name="application_time" placeholder="請輸入申請時間"
                           value="<?= $model->application_time ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">狀況分類:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="category">
                        <?php foreach($category as $key=>$value): ?>
                            <?php if($model->category == $value->id):?>
                                <option selected="selected" value="<?= $value->id ?>">
                                    <?= $value->name ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $value->id ?>">
                                    <?= $value->name ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">審核狀態</label>
                <div class="col-sm-5">
                    <label class="radio-inline">
                        <input type="radio" name="approval_status"
                               value="0" <?php echo ($model->approval_status == 1) ? 'checked="checked"' : '' ?>>審核通過
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="approval_status"
                               value="1" <?php echo ($model->approval_status == 0) ? 'checked="checked"' : '' ?>>審核未通過
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">審核時間:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control slider_example_2" name="approval_time"
                           value="<?= $model->approval_time ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">審核人:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="approval_account_id"
                           value="<?= $model->approval_account_id ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">申請人IP:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="member_ip" value="<?= $model->member_ip ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="detail" class="col-sm-2 control-label">詳細訊息:</label>
                <div class="col-sm-5">
                    <textarea class="form-control" name="msg" placeholder="請輸入諮詢資訊"><?= $model->msg ?>
                    </textarea>
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

