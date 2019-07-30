<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3>加班申請</h3>
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
                                <select class="form-control" id="leave_type" name="leave_type" readonly>
                                    <option value="11">加班</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">加班日期</label>
                            <div class="col-md-2 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="leave_date" name="leave_date" aria-describedby="inputSuccess2Status">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                            </div>
                            <div class="col-md-2">
                                <select id="start_time" name="start_time" class="form-control" onChange="checkTime();">
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                    <option value="18:30">18:30</option>
                                    <option value="19:00">19:00</option>
                                    <option value="19:30">19:30</option>
                                    <option value="20:00">20:00</option>
                                    <option value="20:30">20:30</option>
                                    <option value="21:00">21:00</option>
                                    <option value="21:30">21:30</option>
                                    <option value="22:00">22:00</option>
                                    <option value="22:30">22:30</option>
                                    <option value="23:00">23:00</option>
                                    <option value="23:30">23:30</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="end_time" name="end_time" class="form-control" onChange="checkTime();">
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                    <option value="18:30">18:30</option>
                                    <option value="19:00">19:00</option>
                                    <option value="19:30">19:30</option>
                                    <option value="20:00">20:00</option>
                                    <option value="20:30">20:30</option>
                                    <option value="21:00">21:00</option>
                                    <option value="21:30">21:30</option>
                                    <option value="22:00">22:00</option>
                                    <option value="22:30">22:30</option>
                                    <option value="23:00">23:00</option>
                                    <option value="23:30">23:30</option>
                                    <option value="23:59">23:59</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">加班時數</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="minutes" name="minutes" class="form-control col-md-7 col-xs-12" value="0.5小時" readonly>
                                <input type="hidden" id="leave_minutes" name="leave_minutes" class="form-control col-md-7 col-xs-12" value="0.5">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agent">*主管</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="manager" name="manager" class="form-control col-md-7 col-xs-12" required>
                            </div>
                        </div>

                        <div class="ln_solid"></div>

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
<script>
    $(function() {

        $('#leave_date').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'YYYY/MM/DD'
            }
        });

        let availableTags = [<?= $userNameSearchWord ?>];
        $("#user_name").autocomplete({
            source: availableTags
        });

        let availableNameTags = [<?= $nameSearchWord ?>];

        $("#manager").autocomplete({
            source: availableNameTags
        });
    });

    function checkTime() {
        if ($("#start_time").val() < $("#end_time").val()) {
            var hour = parseInt($("#end_time").val().substr(0, 2)) - parseInt($("#start_time").val().substr(0, 2));
            var minute = parseInt($("#end_time").val().substr(3, 2)) - parseInt($("#start_time").val().substr(3, 2));
            if (minute == 59) {
                minute = 0;
                hour++;
            } else if (minute == 29) {
                minute = 30;
            }
            var total = (hour * 60 + minute) / 60;
            $("#leave_minutes").val(total);
            $("#minutes").val(total.toString() + "小時");
        } else {
            alert("請確認加班時間是否正確");
        }
    }
</script>
