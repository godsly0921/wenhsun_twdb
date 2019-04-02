<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">儀器預約新增</h3>
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

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('reservation/create'); ?>" method="post"
      enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

        <div class="panel panel-default">
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label">預約儀器:</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="device_id">
                            <?php foreach ($devices as $key => $value): ?>
                                <?php if ($value->id == $device_id): ?>
                                    <option selected="selected" value="<?= $value->id ?>"><?= $value->name ?></option>
                                <?php else: ?>
                                    <option value="<?= $value->id ?>"><?= $value->name ?></option>
<!--                                    <option disabled="disabled" value="--><?//= $value->id ?><!--">--><?//= $value->name ?><!--</option>-->
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adv_id" class="col-sm-2 control-label">預約開始日期:</label>
                    <div class="col-sm-3">
                        <input type="date" class="form-control" disabled="disabled" name="start_date_show" placeholder="" value="<?= $start?>">
                        <input type="hidden" name="start_date" value="<?= $start?>">
                    </div>
                    <label class="col-sm-1 control-label">小時:</label>
                    <div class="col-sm-1">
                        <select class="form-control" name="start_hour">
                            <?php foreach (Common::hours() as $key => $value): ?>
                                <option value="<?= $key; ?>"><?= $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label class="col-sm-1 control-label">分鐘:</label>
                    <div class="col-sm-1">
                        <select class="form-control" name="start_minute">
                            <?php foreach (Common::minutes() as $key => $value): ?>
                                <option value="<?= $key; ?>"><?= $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adv_id" class="col-sm-2 control-label">預約結束日期:</label>
                    <div class="col-sm-3">
                        <input type="date" class="form-control" disabled="disabled" name="end_date_show" placeholder="" value="<?= $end ?>">
                        <input type="hidden" name="end_date" value="<?= $end?>">
                    </div>
                    <label class="col-sm-1 control-label">小時:</label>
                    <div class="col-sm-1">
                        <select class="form-control" name="end_hour">
                            <?php foreach (Common::hours() as $key => $value): ?>
                                <option value="<?= $key; ?>"><?= $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label class="col-sm-1 control-label">分鐘:</label>
                    <div class="col-sm-1">
                        <select class="form-control" name="end_minute">
                            <?php foreach (Common::minutes() as $key => $value): ?>
                                <option value="<?= $key; ?>"><?= $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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
    <script type="text/javascript"
            src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery-ui-timepicker-addon.js">
    </script>
    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/jquery-ui-timepicker-addon.css"/>

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