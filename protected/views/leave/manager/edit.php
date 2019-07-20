<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3>修改假單</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])): ?>
                    <div id="error_alert" class="alert alert-danger alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::ERR_MSG_KEY]; ?>
                        <?php unset(Yii::app()->session[Controller::ERR_MSG_KEY]); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty(Yii::app()->session[Controller::SUCCESS_MSG_KEY])): ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::SUCCESS_MSG_KEY]; ?>
                        <?php unset(Yii::app()->session[Controller::SUCCESS_MSG_KEY]); ?>
                    </div>
                <?php endif; ?>
                <div class="x_panel">
                    <div class="clearfix"></div>
                    <form id="form" method="post" action="<?php echo Yii::app()->createUrl('/leave/manager/update'); ?>"
                          data-parsley-validate class="form-horizontal form-label-left" novalidate>

                        <?php CsrfProtector::genHiddenField(); ?>

                        <input type="hidden" value="<?=$attendanceRecord->id?>" name="id">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">員工帳號</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" disabled value="<?=$employee->user_name?>" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">類別</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="leave_type" name="leave_type">
                                    <?php foreach ($leaveMap as $leaveCode => $leaveText): ?>
                                        <?php if((string)$attendanceRecord->take === (string)$leaveCode):?>
                                            <option value="<?= $leaveCode ?>" selected><?= $leaveText ?></option>
                                        <?php else:?>
                                            <option value="<?= $leaveCode ?>"><?= $leaveText ?></option>
                                        <?php endif;?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">請假日期</label>
                            <div class="col-md-6 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="leave_date" name="leave_date" aria-describedby="inputSuccess2Status" value="<?=$attendanceRecord->day?>">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">請假時數</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="leave_minutes" name="leave_minutes">
                                    <option value="30" <?php if((int)$attendanceRecord->leave_minutes === 30):?>selected<?php endif;?>>0.5 小時</option>
                                    <option value="60" <?php if((int)$attendanceRecord->leave_minutes === 60):?>selected<?php endif;?>>1 小時</option>
                                    <option value="90" <?php if((int)$attendanceRecord->leave_minutes === 90):?>selected<?php endif;?>>1.5 小時</option>
                                    <option value="120" <?php if((int)$attendanceRecord->leave_minutes === 120):?>selected<?php endif;?>>2 小時</option>
                                    <option value="150" <?php if((int)$attendanceRecord->leave_minutes === 150):?>selected<?php endif;?>>2.5 小時</option>
                                    <option value="180" <?php if((int)$attendanceRecord->leave_minutes === 180):?>selected<?php endif;?>>3 小時</option>
                                    <option value="210" <?php if((int)$attendanceRecord->leave_minutes === 210):?>selected<?php endif;?>>3.5 小時</option>
                                    <option value="240" <?php if((int)$attendanceRecord->leave_minutes === 240):?>selected<?php endif;?>>4 小時</option>
                                    <option value="270" <?php if((int)$attendanceRecord->leave_minutes === 270):?>selected<?php endif;?>>4.5 小時</option>
                                    <option value="300" <?php if((int)$attendanceRecord->leave_minutes === 300):?>selected<?php endif;?>>5 小時</option>
                                    <option value="330" <?php if((int)$attendanceRecord->leave_minutes === 330):?>selected<?php endif;?>>5.5 小時</option>
                                    <option value="360" <?php if((int)$attendanceRecord->leave_minutes === 360):?>selected<?php endif;?>>6 小時</option>
                                    <option value="390" <?php if((int)$attendanceRecord->leave_minutes === 390):?>selected<?php endif;?>>6.5 小時</option>
                                    <option value="420" <?php if((int)$attendanceRecord->leave_minutes === 420):?>selected<?php endif;?>>7 小時</option>
                                    <option value="450" <?php if((int)$attendanceRecord->leave_minutes === 450):?>selected<?php endif;?>>7.5 小時</option>
                                    <option value="480" <?php if((int)$attendanceRecord->leave_minutes === 480):?>selected<?php endif;?>>8 小時</option>
                                </select>
                            </div>
                        </div>

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">修改</button>
                                <a class="btn btn-default pull-right" href="<?= Yii::app()->createUrl("/leave/manager/hist?user_name={$employee->user_name}&year={$year}");?>">返回</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#leave_date').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY/MM/DD'
        }
    });
</script>


