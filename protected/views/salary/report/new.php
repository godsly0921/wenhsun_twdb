<div role="main">
    <div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php use Wenhsun\Salary\Entity\SalaryReportEmployee;

                if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])): ?>
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
                <?php /** @var SalaryReportEmployee $data */ ?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>產生薪資報表</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="form" method="post" action="<?= Yii::app()->createUrl('/salary/report/create');?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>

                            <?php CsrfProtector::genHiddenField(); ?>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="year">年份</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="year" name="year" type="text" value="" class="form-control col-md-7 col-xs-12" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="month">月份</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="month" id="month" class="form-control">
                                        <option value="01">1月</option>
                                        <option value="02">2月</option>
                                        <option value="03">3月</option>
                                        <option value="04">4月</option>
                                        <option value="05">5月</option>
                                        <option value="06">6月</option>
                                        <option value="07">7月</option>
                                        <option value="08">8月</option>
                                        <option value="09">9月</option>
                                        <option value="10">10月</option>
                                        <option value="11">11月</option>
                                        <option value="12">12月</option>
                                    </select>
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">確認產生</button>
                                    <a class="btn btn-default pull-right" href="<?= Yii::app()->createUrl('/salary/report');?>">返回</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

