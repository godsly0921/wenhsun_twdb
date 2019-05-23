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
                        <h2>薪資報表(<?=$batch_id?>) - 員工本月薪資設定</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="form" method="post" action="<?= Yii::app()->createUrl('/salary/report/update');?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>

                            <?php CsrfProtector::genHiddenField(); ?>
                            <input type="hidden" id="employee_id" name="employee_id" value="<?=$data->employee_id?>">
                            <input type="hidden" id="batch_id" name="batch_id" value="<?=$data->batch_id?>">
                            <p>員工資訊</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee_login_id">帳號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->employee_login_id?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee_name">姓名</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->employee_name?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>薪資資訊</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="salary">本薪(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="salary" name="salary" value="<?=$data['salary']?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="draft_allowance">稿費津貼(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="draft_allowance" name="draft_allowance" value="<?=$data['draft_allowance']?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="traffic_allowance">交通津貼(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="traffic_allowance" name="traffic_allowance" value="<?=$data['traffic_allowance']?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="overtime_wage">應稅加班費(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="overtime_wage" name="overtime_wage" value="<?=$data['overtime_wage']?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="project_allowance">專案津貼(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="project_allowance" name="project_allowance" value="<?=$data['project_allowance']?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12 red" for="taxable_salary_total">應稅薪資合計(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="taxable_salary_total" name="taxable_salary_total" value="<?=$data['taxable_salary_total']?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tax_free_overtime_wage">免稅加班費(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="tax_free_overtime_wage" name="tax_free_overtime_wage" value="<?=$data['tax_free_overtime_wage']?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12 red" for="salary_total">薪資合計(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="salary_total" name="salary_total" value="<?=$data['salary_total']?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="health_insurance">健保(-)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="health_insurance" name="health_insurance" value="<?=$data['health_insurance']?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="labor_insurance">勞保(-)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="labor_insurance" name="labor_insurance" value="<?=$data['labor_insurance']?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pension">退休金提撥(-)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="pension" name="pension" value="<?=$data['pension']?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12 red" for="deduction_total">應扣合計(-)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="deduction_total" name="deduction_total" value="<?=$data['deduction_total']?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12 red" for="real_salary">實領薪資</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="real_salary" name="real_salary" value="<?=$data['real_salary']?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">修改</button>
                                    <a class="btn btn-default pull-right" href="/salary/report/batch?batchId=<?=$batch_id?>">返回</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
