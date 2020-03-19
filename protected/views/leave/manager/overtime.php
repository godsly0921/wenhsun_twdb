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
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">加班時間起</label>
                            <div class="col-md-3 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="start_date" name="start_date" aria-describedby="inputSuccess2Status">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                            </div>
                            <div class="col-md-1">
                                <select id="start_time" name="start_time" class="form-control" onChange="checkTime();">
                                    <?php
                                    for ($i = 0;$i <= 23;$i++)
                                    {
                                            $time = str_pad($i,2,'0',STR_PAD_LEFT).":00";
                                            echo  "<option value='{$time}'>{$time}</option>";

                                            

                                            $time = str_pad($i,2,'0',STR_PAD_LEFT).":30";
                                            echo  "<option value='{$time}'>{$time}</option>";

                                            if($i == 23){//最後一筆
                                                $time = str_pad($i,2,'0',STR_PAD_LEFT).":59";
                                                echo  "<option value='{$time}'>{$time}</option>";

                                            }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">加班日期訖</label>
                            <div class="col-md-3 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="end_date" name="end_date" aria-describedby="inputSuccess2Status">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                            </div>
                            <div class="col-md-1">
                                <select id="end_time" name="end_time" class="form-control" onChange="checkTime();">
                                    <?php
                                    for ($i = 0;$i <= 23;$i++)
                                    {




                                            $time = str_pad($i,2,'0',STR_PAD_LEFT).":00";
                                            echo  "<option value='{$time}'>{$time}</option>";

                                            

                                            $time = str_pad($i,2,'0',STR_PAD_LEFT).":30";
                                            echo  "<option value='{$time}'>{$time}</option>";

                                            if($i == 23){//最後一筆
                                                $time = str_pad($i,2,'0',STR_PAD_LEFT).":59";
                                                echo  "<option value='{$time}'>{$time}</option>";

                                            }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!--<input type="hidden" class="form-control has-feedback-left" id="end_date" name="end_date" aria-describedby="inputSuccess2Status" readonly>-->

                        <input type="hidden" id="days" name="days" value="1">
                        <input type="hidden" id="first_hours" name="first_hours" value="0.5">
                        <input type="hidden" id="last_hours" name="last_hours" value="0.5">

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
        $("#start_date").daterangepicker({
            singleDatePicker: true,
            locale: {
                format: "YYYY-MM-DD"
            }
        });

        $("#end_date").daterangepicker({
            singleDatePicker: true,
            locale: {
                format: "YYYY-MM-DD"
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

        $("#end_date").val($("#start_date").val());
    });

    function checkTime() {
        var start = moment($("#start_date").val() + " " + $("#start_time").val(), "YYYY/MM/DD HH:mm");
        console.log(start);
        var end = moment($("#end_date").val() + " " + $("#end_time").val(), "YYYY/MM/DD HH:mm");
        console.log(end);
        var diff = 0;

        if (start >= end) {
            alert("請確認加班時間是否正確,“開始時間不可大於等於結束時間”。");
        } else {

            var firstDay = moment($("#start_date").val() + " " + $("#start_time").val(), "YYYY/MM/DD HH:mm");
            var lastDay = moment(end.format("YYYY-MM-DD") + " " + $("#end_time").val(), "YYYY/MM/DD HH:mm");
            diff = lastDay.diff(firstDay, "hours", true);
            $("#last_hours").val(end.diff(lastDay, "hours", true));
            console.log(lastDay);
            console.log(diff);
            
            $("#leave_minutes").val(diff);

            diff = diff.toFixed(2); 
            $("#minutes").val(diff + "小時");
        }
    }
</script>