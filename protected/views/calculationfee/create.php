<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">儀器基本計費設定 -- 批次新增</h3>
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

<div class="panel panel-default">
    <div class="panel-body">

        <form name="calculationfee_form" class="form-horizontal" method="post"
              action="<?php echo Yii::app()->createUrl('calculationfee/create'); ?>">
            <?php CsrfProtector::genHiddenField(); ?>

            <div class="panel panel-default">
                <div class="panel-body">
                    <p><input type="checkbox" name="all" onclick="check_all(this,'device[]')" />全選 or 全不選</p>
                    <?php foreach ($device as $value): ?>
                        <div class="form-calculationfee">
                            <div class="col-sm-offset-1 col-sm-10">
                                <div class="checkbox">
                                    <label style="color:#00CC66;">
                                        <input type="checkbox" name="device[]" value="<?= $value->id ?>" /><?= $value->name ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-calculationfee">
                <?php foreach ($level_one_all as $value): ?>
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <p>計費方式：『<?= $value->name ?> 』</p>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">基數分鐘 :</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="level_one[<?= $value->id ?>][base_minute]" placeholder="每個基數分鐘數"
                                           value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">基數收費 :</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="level_one[<?= $value->id ?>][base_charge]" placeholder="每個基數收費"
                                           value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">開機基數</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="level_one[<?= $value->id ?>][start_base_charge]"
                                           placeholder="開機費基數" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">最大基數:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="level_one[<?= $value->id ?>][max_use_base]"
                                           placeholder="機台最大使用基數(輸入 0 表示無上限)" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">預約未使用基數:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="level_one[<?= $value->id ?>][unused_base]"
                                           placeholder="在原預約時段使用者未使用之基數每個基數收費" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">折扣開始日期:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control slider_example" name="level_one[<?= $value->id ?>][start_date]"
                                           placeholder="折扣開始區間" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">折扣結束日期:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control slider_example" name="level_one[<?= $value->id ?>][end_date]"
                                           placeholder="折扣結束區間" value="">
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="form-calculationfee">
                <div class="col-sm-offset-1 col-sm-10">
                    <button type="submit" class="btn btn-default">批次新增</button>
                </div>
            </div>


        </form>

    </div>
</div>

<script>

    //全選/全不選
    function check_all(obj,deviceName)
    {
        var checkboxs = document.getElementsByName(deviceName);
        for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;}
    }


    function checkall(num, maj) {
        var tag = 'calculationfee_list[' + num + '][]';
        var switch_checked = maj.checked;
        var len = document.calculationfee_form.elements.length;
        for (var i = 0; i < len; i++) {
            if (document.calculationfee_form.elements[i].name == tag)
                document.calculationfee_form.elements[i].checked = switch_checked;
        }
    }

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
        $('.slider_example').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss',
            stepHour: 1,
            stepMinute: 1,
            stepSecond: 1,
            timezone: "+0800",
            pick12HourFormat: true
        });
    });
</script>
<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>

