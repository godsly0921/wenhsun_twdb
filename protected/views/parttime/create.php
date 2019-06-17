<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">工讀生排班新增</h3>
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

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('parttime/create'); ?>" method="post"
      enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

        <div class="panel panel-default">
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label">選擇工讀生:</label>
                    <div class="col-sm-5">
                        <?php if($part_time_employees != false): ?>
                        <select class="form-control" name="part_time_empolyee_id">

                            <?php foreach ($part_time_employees as $key => $value): ?>
                                <?php if ($value->id == $part_time_empolyee_id): ?>
                                    <option selected="selected" value="<?= $value->id ?>"><?= $value->name ?></option>
                                <?php else: ?>
                                    <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </select>
                        <?php else: ?>
                        <select class="form-control" name="part_time_empolyee_id">
                           <option selected="selected" value="--">工讀生尚未設定</option>
                        </select>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adv_id" class="col-sm-2 control-label">開始日期:</label>
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
                    <label for="adv_id" class="col-sm-2 control-label">結束日期:</label>
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
<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>