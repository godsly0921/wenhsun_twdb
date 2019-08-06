<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3>審核表單</h3>
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
                    <form id="form" method="post" action="<?php echo Yii::app()->createUrl('/leave/manager/update'); ?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>

                        <?php CsrfProtector::genHiddenField(); ?>

                        <input type="hidden" value="<?= $attendanceRecord->id ?>" name="id">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">員工帳號</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" disabled value="<?= $employee->user_name ?>" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">員工姓名</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="name" class="form-control col-md-7 col-xs-12" value="<?= $employee->name ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">類別</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="leave_type" name="leave_type">
                                    <?php foreach ($leaveMap as $leaveCode => $leaveText) : ?>
                                        <?php if ($attendanceRecord->take == $leaveCode) : ?>
                                            <option value="<?= $leaveCode ?>" selected><?= $leaveText ?></option>
                                        <?php else : ?>
                                            <option value="<?= $leaveCode ?>"><?= $leaveText ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">申請日期</label>
                            <div class="col-md-6 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" aria-describedby="inputSuccess2Status" value="<?= substr($attendanceRecord->create_at, 0, 10) ?>" readonly>
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="reason">*事由</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="reason" name="reason" class="form-control col-md-7 col-xs-12" value="<?= $attendanceRecord->reason ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">請假日期</label>
                            <div class="col-md-2 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="leave_date" name="leave_date" aria-describedby="inputSuccess2Status" value="<?= $attendanceRecord->leave_time ?>">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                            </div>
                            <div class="col-md-2">
                                <select id="start_time" name="start_time" class="form-control" onchange="checkTime();">
                                    <option value="08:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "08:00") : ?>selected<?php endif; ?>>08:00</option>
                                    <option value="08:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "08:30") : ?>selected<?php endif; ?>>08:30</option>
                                    <option value="09:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "09:00") : ?>selected<?php endif; ?>>09:00</option>
                                    <option value="09:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "09:30") : ?>selected<?php endif; ?>>09:30</option>
                                    <option value="10:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "10:00") : ?>selected<?php endif; ?>>10:00</option>
                                    <option value="10:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "10:30") : ?>selected<?php endif; ?>>10:30</option>
                                    <option value="11:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "11:00") : ?>selected<?php endif; ?>>11:00</option>
                                    <option value="11:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "11:30") : ?>selected<?php endif; ?>>11:30</option>
                                    <option value="12:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "12:00") : ?>selected<?php endif; ?>>12:00</option>
                                    <option value="12:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "12:30") : ?>selected<?php endif; ?>>12:30</option>
                                    <option value="13:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "13:00") : ?>selected<?php endif; ?>>13:00</option>
                                    <option value="13:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "13:30") : ?>selected<?php endif; ?>>13:30</option>
                                    <option value="14:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "14:00") : ?>selected<?php endif; ?>>14:00</option>
                                    <option value="14:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "14:30") : ?>selected<?php endif; ?>>14:30</option>
                                    <option value="15:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "15:00") : ?>selected<?php endif; ?>>15:00</option>
                                    <option value="15:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "15:30") : ?>selected<?php endif; ?>>15:30</option>
                                    <option value="16:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "16:00") : ?>selected<?php endif; ?>>16:00</option>
                                    <option value="16:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "16:30") : ?>selected<?php endif; ?>>16:30</option>
                                    <option value="17:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "17:00") : ?>selected<?php endif; ?>>17:00</option>
                                    <option value="17:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "17:30") : ?>selected<?php endif; ?>>17:30</option>
                                    <option value="18:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "18:00") : ?>selected<?php endif; ?>>18:00</option>
                                    <option value="18:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "18:30") : ?>selected<?php endif; ?>>18:30</option>
                                    <option value="19:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "19:00") : ?>selected<?php endif; ?>>19:00</option>
                                    <option value="19:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "19:30") : ?>selected<?php endif; ?>>19:30</option>
                                    <option value="20:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "20:00") : ?>selected<?php endif; ?>>20:00</option>
                                    <option value="20:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "20:30") : ?>selected<?php endif; ?>>20:30</option>
                                    <option value="21:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "21:00") : ?>selected<?php endif; ?>>21:00</option>
                                    <option value="21:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "21:30") : ?>selected<?php endif; ?>>21:30</option>
                                    <option value="22:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "22:00") : ?>selected<?php endif; ?>>22:00</option>
                                    <option value="22:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "22:30") : ?>selected<?php endif; ?>>22:30</option>
                                    <option value="23:00" <?php if (substr($attendanceRecord->start_time, 11, 5) == "23:00") : ?>selected<?php endif; ?>>23:00</option>
                                    <option value="23:30" <?php if (substr($attendanceRecord->start_time, 11, 5) == "23:30") : ?>selected<?php endif; ?>>23:30</option>
                                    <option value="23:59" <?php if (substr($attendanceRecord->start_time, 11, 5) == "23:59") : ?>selected<?php endif; ?>>23:59</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="end_time" name="end_time" class="form-control" onChange="checkTime();">
                                    <option value="08:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "08:30") : ?>selected<?php endif; ?>>08:30</option>
                                    <option value="09:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "09:00") : ?>selected<?php endif; ?>>09:00</option>
                                    <option value="09:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "09:30") : ?>selected<?php endif; ?>>09:30</option>
                                    <option value="10:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "10:00") : ?>selected<?php endif; ?>>10:00</option>
                                    <option value="10:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "10:30") : ?>selected<?php endif; ?>>10:30</option>
                                    <option value="11:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "11:00") : ?>selected<?php endif; ?>>11:00</option>
                                    <option value="11:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "11:30") : ?>selected<?php endif; ?>>11:30</option>
                                    <option value="12:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "12:00") : ?>selected<?php endif; ?>>12:00</option>
                                    <option value="12:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "12:30") : ?>selected<?php endif; ?>>12:30</option>
                                    <option value="13:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "13:00") : ?>selected<?php endif; ?>>13:00</option>
                                    <option value="13:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "13:30") : ?>selected<?php endif; ?>>13:30</option>
                                    <option value="14:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "14:00") : ?>selected<?php endif; ?>>14:00</option>
                                    <option value="14:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "14:30") : ?>selected<?php endif; ?>>14:30</option>
                                    <option value="15:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "15:00") : ?>selected<?php endif; ?>>15:00</option>
                                    <option value="15:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "15:30") : ?>selected<?php endif; ?>>15:30</option>
                                    <option value="16:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "16:00") : ?>selected<?php endif; ?>>16:00</option>
                                    <option value="16:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "16:30") : ?>selected<?php endif; ?>>16:30</option>
                                    <option value="17:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "17:00") : ?>selected<?php endif; ?>>17:00</option>
                                    <option value="17:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "17:30") : ?>selected<?php endif; ?>>17:30</option>
                                    <option value="18:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "18:00") : ?>selected<?php endif; ?>>18:00</option>
                                    <option value="18:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "18:30") : ?>selected<?php endif; ?>>18:30</option>
                                    <option value="19:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "19:00") : ?>selected<?php endif; ?>>19:00</option>
                                    <option value="19:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "19:30") : ?>selected<?php endif; ?>>19:30</option>
                                    <option value="20:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "20:00") : ?>selected<?php endif; ?>>20:00</option>
                                    <option value="20:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "20:30") : ?>selected<?php endif; ?>>20:30</option>
                                    <option value="21:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "21:00") : ?>selected<?php endif; ?>>21:00</option>
                                    <option value="21:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "21:30") : ?>selected<?php endif; ?>>21:30</option>
                                    <option value="22:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "22:00") : ?>selected<?php endif; ?>>22:00</option>
                                    <option value="22:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "22:30") : ?>selected<?php endif; ?>>22:30</option>
                                    <option value="23:00" <?php if (substr($attendanceRecord->end_time, 11, 5) == "23:00") : ?>selected<?php endif; ?>>23:00</option>
                                    <option value="23:30" <?php if (substr($attendanceRecord->end_time, 11, 5) == "23:30") : ?>selected<?php endif; ?>>23:30</option>
                                    <option value="23:59" <?php if (substr($attendanceRecord->end_time, 11, 5) == "23:59") : ?>selected<?php endif; ?>>23:59</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">申請時數</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="minutes" name="minutes" class="form-control col-md-7 col-xs-12" value="<?= $attendanceRecord->leave_minutes / 60 . '小時' ?>" readonly>
                                <input type="hidden" id="leave_minutes" name="leave_minutes" class="form-control col-md-7 col-xs-12" value="<?= $attendanceRecord->leave_minutes / 60 ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">工作交辦</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="remark" name="remark" class="form-control col-md-7 col-xs-12" value="<?= $attendanceRecord->remark ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agent">代理人</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="agent" name="agent" class="form-control col-md-7 col-xs-12" value="<?= $attendanceRecord->agent ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agent">*主管</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="manager" name="manager" class="form-control col-md-7 col-xs-12" value="<?= $attendanceRecord->manager ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">核准送出</button>
                                <a class="btn btn-default pull-right" href="<?= Yii::app()->createUrl("/leave/manager/hist?user_name={$employee->user_name}&year={$year}"); ?>">返回</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#leave_date').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'YYYY/MM/DD'
            }
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
        var start = moment($("#leave_date").val() + " " + $("#start_time").val());
        var end = moment($("#leave_date").val() + " " + $("#end_time").val());
        var diff = 0;

        if (start >= end) {
            alert("請確認時間起訖是否正確");
        } else {
            if ($("#start_time").val() <= "12:00" && $("#end_time").val() >= "13:00") {
                diff = end.diff(start, "hours", true) - 1;
            } else if ($("#end_time").val() === "23:59") {
                diff = Math.round(end.diff(start, "hours", true) * 10) / 10;
            } else {
                diff = end.diff(start, "hours", true);
            }

            $("#leave_minutes").val(diff);
            $("#minutes").val(diff + "小時");
        }
    }
</script>