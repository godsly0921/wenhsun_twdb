<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3>請假申請</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])) : ?>
                    <div id="error_alert" class="alert alert-danger alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::ERR_MSG_KEY]; ?>
                        <?php unset(Yii::app()->session[Controller::ERR_MSG_KEY]); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty(Yii::app()->session[Controller::SUCCESS_MSG_KEY])) : ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::SUCCESS_MSG_KEY]; ?>
                        <?php unset(Yii::app()->session[Controller::SUCCESS_MSG_KEY]); ?>
                    </div>
                <?php endif; ?>
                <div class="x_panel">
                    <div class="clearfix"></div>
                    <form id="form" method="post" action="<?php echo Yii::app()->createUrl('/leave/manager/create'); ?>" data-parsley-validate class="form-horizontal form-label-left" novalidate onsubmit="alert('完成申請後請至請假紀錄留意審核狀況，謝謝');">

                        <?php CsrfProtector::genHiddenField(); ?>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_name">員工帳號</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="user_name" name="user_name" class="form-control col-md-7 col-xs-12" value="<?php echo Yii::app()->session['pid'] ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">員工姓名</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="name" class="form-control col-md-7 col-xs-12" value="<?= $emp->name ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="create_date">申請日期</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="create_date" class="form-control col-md-7 col-xs-12" value="<?= date('Y-m-d') ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="reason">*事由</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="reason" name="reason" class="form-control col-md-7 col-xs-12" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">類別</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="leave_type" name="leave_type">
                                    <?php foreach ($leaveMap as $leaveCode => $leaveText) : ?>
                                        <option value="<?= $leaveCode ?>"><?= $leaveText ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">請假日期起</label>
                            <div class="col-md-2 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="start_date" name="start_date" onChange="checkTime();" aria-describedby="inputSuccess2Status">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                            </div>
                            <div class="col-md-2">
                                <select id="start_time" name="start_time" class="form-control" onChange="checkTime();">
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">請假日期訖</label>
                            <div class="col-md-2 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="end_date" name="end_date" onChange="checkTime();" aria-describedby="inputSuccess2Status">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                            </div>
                            <div class="col-md-2">
                                <select id="end_time" name="end_time" class="form-control" onChange="checkTime();">
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" id="days" name="days" value="1">
                        <input type="hidden" id="last_hours" name="last_hours">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">請假時數</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="minutes" name="minutes" class="form-control col-md-7 col-xs-12" value="0.5 小時" readonly>
                                <input type="hidden" id="leave_minutes" name="leave_minutes" class="form-control col-md-7 col-xs-12" value="0.5">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">工作交辦</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="remark" name="remark" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agent">*代理人</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="agent" name="agent" class="form-control col-md-7 col-xs-12" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agent">*主管</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="manager" name="manager" class="form-control col-md-7 col-xs-12" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">申請確認</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<script>
    $(function() {

        $("#start_date").daterangepicker({
            singleDatePicker: true,
            locale: {
                format: "YYYY/MM/DD"
            }
        });

        $("#end_date").daterangepicker({
            singleDatePicker: true,
            locale: {
                format: "YYYY/MM/DD"
            }
        });

        let availableTags = [<?= $userNameSearchWord ?>];
        $("#user_name").autocomplete({
            source: availableTags
        });

        let availableNameTags = [<?= $nameSearchWord ?>];

        $("#agent").autocomplete({
            source: availableNameTags
        });

        $("#manager").autocomplete({
            source: availableNameTags
        });
    });

    function checkTime() {
        if ($("#start_date").val() > $("#end_date").val()) {
            $("#end_date").val($("#start_date").val());
        }

        var start = moment($("#start_date").val() + " " + $("#start_time").val());
        var end = moment($("#end_date").val() + " " + $("#end_time").val());
        var diff = 0;

        $("#days").val(end.diff(start, 'days') + 1);

        if (start >= end) {
            alert("請確認請假時間是否正確");
        } else {
            if ($("#start_date").val() === $("#end_date").val()) {
                if ($("#start_time").val() <= "12:00" && $("#end_time").val() >= "13:00") {
                    diff = end.diff(start, "hours", true) - 1;
                } else {
                    diff = end.diff(start, "hours", true);
                }
            } else {
                for (var i = 1; i <= end.diff(start, "days"); i++) {
                    diff += 8;
                }
                var lastDay = moment($("#end_date").val() + " " + "09:00");
                if ($("#end_time").val() !== "18:00") {
                    if ($("#end_time").val() >= "13:00") {
                        diff += end.diff(lastDay, "hours", true) - 1;
                        $("#last_hours").val(end.diff(lastDay, "hours", true) - 1);
                    } else {
                        diff += end.diff(lastDay, "hours", true);
                        $("#last_hours").val(end.diff(lastDay, "hours", true));
                    }
                } else {
                    diff += 8;
                    $("#last_hours").val(8);
                }
            }

            $("#leave_minutes").val(diff);
            $("#minutes").val(diff + "小時");
        }
    }
</script>