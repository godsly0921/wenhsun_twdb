<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3>新增假單</h3>
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
                    <form id="form" method="post" action="<?php echo Yii::app()->createUrl('/leave/manager/create'); ?>"
                          data-parsley-validate class="form-horizontal form-label-left" novalidate>

                        <?php CsrfProtector::genHiddenField(); ?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee">員工</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="employee_id" name="employee_id">
                                    <?php foreach ($employees as $employee): ?>
                                        <option value="<?= $employee->id ?>"><?= $employee->name ?>(<?= $employee->id ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">類別</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="leave_type" name="leave_type">
                                    <?php foreach ($leaveMap as $leaveCode => $leaveText): ?>
                                        <option value="<?= $leaveCode ?>"><?= $leaveText ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">請假日期</label>
                            <div class="col-md-6 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="leave_date" name="leave_date" aria-describedby="inputSuccess2Status">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">請假時數</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="leave_minutes" name="leave_minutes">
                                    <option value="30">0.5 小時</option>
                                    <option value="60">1 小時</option>
                                    <option value="90">1.5 小時</option>
                                    <option value="120">2 小時</option>
                                    <option value="150">2.5 小時</option>
                                    <option value="180">3 小時</option>
                                    <option value="210">3.5 小時</option>
                                    <option value="240">4 小時</option>
                                    <option value="270">4.5 小時</option>
                                    <option value="300">5 小時</option>
                                    <option value="330">5.5 小時</option>
                                    <option value="360">6 小時</option>
                                    <option value="390">6.5 小時</option>
                                    <option value="420">7 小時</option>
                                    <option value="450">7.5 小時</option>
                                    <option value="480">8 小時</option>
                                </select>
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
    $('#leave_date').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY/MM/DD'
        }
    });
</script>
