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
                        <h2>薪資報表(<?=$data->getBatchId()?>) - 員工本月薪資設定</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="form" method="post" action="<?= Yii::app()->createUrl('/salary/report/update');?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>

                            <?php CsrfProtector::genHiddenField(); ?>
                            <input type="hidden" id="id" name="id" value="<?=$data->getId()?>">
                            <p>員工資訊</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee_login_id">帳號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->getEmployeeLoginId()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee_name">姓名</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->getEmployeeName()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="department">部門</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->getEmployeeDepartment()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="position">職務</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->getEmployeePosition()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>薪資資訊</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="salary">本薪(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="salary" name="salary" value="<?=$data->getSalary()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="draft_allowance">稿費津貼(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="draft_allowance" name="draft_allowance" value="<?=$data->getDraftAllowance()?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="traffic_allowance">交通津貼(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="traffic_allowance" name="traffic_allowance" value="<?=$data->getTrafficAllowance()?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="overtime_wage">應稅加班費(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="overtime_wage" name="overtime_wage" value="<?=$data->getOvertimeWage()?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="project_allowance">專案津貼(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="project_allowance" name="project_allowance" value="<?=$data->getProjectAllowance()?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12 red" for="taxable_salary_total">應稅薪資合計(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="taxable_salary_total" name="taxable_salary_total" value="<?=$data->calcTaxableSalaryTotal()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tax_free_overtime_wage">免稅加班費(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="tax_free_overtime_wage" name="tax_free_overtime_wage" value="<?=$data->getTaxFreeOvertimeWage()?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="other_plus">其他加項(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="other_plus" name="other_plus" value="<?=$data->getOtherPlus()?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12 red" for="salary_total">薪資合計(+)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="salary_total" name="salary_total" value="<?=$data->calcSalaryTotal()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="health_insurance">健保(-)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="health_insurance" name="health_insurance" value="<?=$data->getHealthInsurance()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="labor_insurance">勞保(-)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="labor_insurance" name="labor_insurance" value="<?=$data->getLaborInsurance()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="other_minus">其他減項(-)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="other_minus" name="other_minus" value="<?=$data->getOtherMinus()?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pension">退休金提撥(不計算)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="pension" name="pension" value="<?=$data->getPension()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12 red" for="deduction_total">應扣合計(-)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="deduction_total" name="deduction_total" value="<?=$data->calcDeductionTotal()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12 red" for="real_salary">實領薪資</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="real_salary" name="real_salary" value="<?=$data->calcRealSalary()?>" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="memo">備註</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea type="text" id="memo" name="memo" class="form-control col-md-7 col-xs-12"><?=$data->getMemo()?></textarea>
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">修改</button>
                                    <a class="btn btn-default pull-right" href="<?= Yii::app()->createUrl('/salary/report/batch?batchId=');?><?=$data->getBatchId()?>">返回</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
