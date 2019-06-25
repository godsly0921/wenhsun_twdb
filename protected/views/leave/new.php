<div role="main">
    <div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])): ?>
                    <div id="error_alert" class="alert alert-danger alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::ERR_MSG_KEY];?>
                        <?php unset(Yii::app()->session[Controller::ERR_MSG_KEY]);?>
                    </div>
                <?php endif; ?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>申請休假</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="form" method="post" action="<?php echo Yii::app()->createUrl('/leave/apply'); ?>" data-parsley-validate class="form-horizontal form-label-left" novalidate enctype="multipart/form-data">

                            <?php CsrfProtector::genHiddenField(); ?>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">類別</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="leave_type" name="leave_type">
                                        <option value="ANNUAL">特休假</option>
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

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">內容</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="memo" name="memo" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="document_file">附件</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="file" id="leave_file" name="leave_file" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">確認申請</button>
                                    <a class="btn btn-default pull-right" href="<?php echo Yii::app()->createUrl('/leave'); ?>">返回</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>