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
                <?php if (!empty(Yii::app()->session[Controller::SUCCESS_MSG_KEY])): ?>
                    <div id="succ-alert" class="alert alert-success alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::SUCCESS_MSG_KEY];?>
                        <?php unset(Yii::app()->session[Controller::SUCCESS_MSG_KEY]);?>
                    </div>
                <?php endif; ?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>編輯員工薪資設定</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="form" method="post" action="<?= Yii::app()->createUrl('/salary/employees/update');?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>

                            <?php CsrfProtector::genHiddenField(); ?>
                            <input type="hidden" id="id" name="id" value="<?=$data['employee_id']?>">
                            <p>員工資訊</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_name">帳號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data['user_name']?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">姓名</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data['name']?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>薪資資訊</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="salary">本薪</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="salary" name="salary" value="<?=$data['salary']?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="health_insurance">健保</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="health_insurance" name="health_insurance" value="<?=$data['health_insurance']?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="labor_insurance">勞保</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="labor_insurance" name="labor_insurance" value="<?=$data['labor_insurance']?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pension">退休金提撥</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="pension" name="pension" value="<?=$data['pension']?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">修改</button>
                                    <a class="btn btn-default pull-right" href="/salary/employees">返回</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
