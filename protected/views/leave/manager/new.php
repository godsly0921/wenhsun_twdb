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
                        <?php echo Yii::app()->session[Controller::ERR_MSG_KEY];?>
                        <?php unset(Yii::app()->session[Controller::ERR_MSG_KEY]);?>
                    </div>
                <?php endif; ?>
                <div class="x_panel">
                    <div class="clearfix"></div>
                    <form id="form" method="post" action="<?php echo Yii::app()->createUrl('/leave/manager/create'); ?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>

                        <?php CsrfProtector::genHiddenField(); ?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee">員工</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="employee" name="employee">
                                    <?php foreach($employees as $employee):?>
                                        <option value="<?=$employee->id?>"><?=$employee->name?>(<?=$employee->id?>)</option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">類別</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="leave_type" name="leave_type">
                                    <?php foreach($leaveMap as $leaveCode => $leaveText):?>
                                        <option value="<?=$leaveCode?>"><?=$leaveText?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_date">起始日</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="start_date" name="start_date" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">起始時間</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="start_time" name="start_time">
                                    <option value="09:00:00">09:00</option>
                                    <option value="09:30:00">09:30</option>
                                    <option value="10:00:00">10:00</option>
                                    <option value="10:30:00">10:30</option>
                                    <option value="11:00:00">11:00</option>
                                    <option value="11:30:00">11:30</option>
                                    <option value="12:00:00">12:00</option>
                                    <option value="12:30:00">12:30</option>
                                    <option value="13:00:00">13:00</option>
                                    <option value="13:30:00">13:30</option>
                                    <option value="14:00:00">14:00</option>
                                    <option value="14:30:00">14:30</option>
                                    <option value="15:00:00">15:00</option>
                                    <option value="15:30:00">15:30</option>
                                    <option value="16:00:00">16:00</option>
                                    <option value="16:30:00">16:30</option>
                                    <option value="17:00:00">17:00</option>
                                    <option value="17:30:00">17:30</option>
                                    <option value="18:00:00">18:00</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_date">結束日</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="end_date" name="end_date" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">結束時間</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" id="end_time" name="end_time">
                                    <option value="09:00:00">09:00</option>
                                    <option value="09:30:00">09:30</option>
                                    <option value="10:00:00">10:00</option>
                                    <option value="10:30:00">10:30</option>
                                    <option value="11:00:00">11:00</option>
                                    <option value="11:30:00">11:30</option>
                                    <option value="12:00:00">12:00</option>
                                    <option value="12:30:00">12:30</option>
                                    <option value="13:00:00">13:00</option>
                                    <option value="13:30:00">13:30</option>
                                    <option value="14:00:00">14:00</option>
                                    <option value="14:30:00">14:30</option>
                                    <option value="15:00:00">15:00</option>
                                    <option value="15:30:00">15:30</option>
                                    <option value="16:00:00">16:00</option>
                                    <option value="16:30:00">16:30</option>
                                    <option value="17:00:00">17:00</option>
                                    <option value="17:30:00">17:30</option>
                                    <option value="18:00:00">18:00</option>
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

