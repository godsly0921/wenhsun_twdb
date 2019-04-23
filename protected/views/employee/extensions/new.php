<div role="main">
    <div class="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>員工分機新增</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="demo-form2" method="post" action="/employee/extensions/create" data-parsley-validate class="form-horizontal form-label-left">
                            <?php CsrfProtector::genHiddenField(); ?>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ext-number">分機號碼<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="number" oninput="if(value.length>8)value=value.slice(0,8)" id="ext-number" name="ext_number" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <a href="/employee/extensions"><button type="button" class="btn btn-default">返回</button></a>
                                    <button type="submit" class="btn btn-primary">新增</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])): ?>
        <div id="error_alert" class="alert alert-danger alert-dismissible fade in" role="alert">
            <?php echo Yii::app()->session[Controller::ERR_MSG_KEY];?>
            <?php unset(Yii::app()->session[Controller::ERR_MSG_KEY]);?>
        </div>
    <?php endif; ?>
</div>