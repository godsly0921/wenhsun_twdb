<?php

declare(strict_types=1);

use Wenhsun\Salary\Entity\SalaryReportBatch;
use Wenhsun\Salary\Entity\SalaryReportEmployee;
use Wenhsun\Salary\Repository\EmployeeRepository;
use Wenhsun\Salary\Service\SalaryReportService;
use Wenhsun\Salary\Service\SalaryService;
use Wenhsun\Tool\Uuid;
use yidas\phpSpreadsheet\Helper;

class ReportController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {
        $serv = new SalaryReportService();
        $list = $serv->getBatchList();
        $this->render('list', ['list' => $list]);
    }

    public function actionNew(): void
    {
        $this->render('new');
    }

    public function actionCreate(): void
    {
        try {

            $this->checkCSRF('index');

            $batchId = $_POST['year'] . $_POST['month'];

            $salaryReportServ = new SalaryReportService();
            $salaryReportServ->deleteBatch($batchId);

            $salaryRepository = new EmployeeRepository();
            $salaryServ = new SalaryService($salaryRepository);

            $employees = $salaryServ->getEmployees();

            $batchReportBatch = new SalaryReportBatch($batchId);
            foreach ($employees as $employee) { 
                if (!empty($employee['salary'])) {
                    $configService = new ConfigService();
                    $AnnualLeaveType = $configService->findByConfigName("AnnualLeaveType");
                    if(!empty($AnnualLeaveType)){
                        $AnnualLeaveType = $AnnualLeaveType[0]['config_value'];
                    }else{
                        $AnnualLeaveType = 1;
                    }
                    $check_role = [2,5,26];
                    $AnnualLeaveFiscalYear = 0;
                    if($AnnualLeaveType == 1 && in_array($employee['role'], $check_role) && $_POST['month'] == AnnualLeaveFiscalYearClose){
                        $leaveService = new LeaveService();
                        $annualLeaveMinutes = $leaveService->calcAnnualLeaveSummaryYear_FiscalYear($employee['employee_id'], $_POST['year']-1);
                        if(!empty($annualLeaveMinutes)){
                            $annualLeaveMinutes = $annualLeaveMinutes[0];
                            if($annualLeaveMinutes["is_close"] == '1'){
                                $appliedAnnualLeave = $leaveService->getEmployeeLeaves_FiscalYear(
                                    $annualLeaveMinutes["id"],
                                    $employee['employee_id']
                                );
                                $annualLeaveMinutes = $annualLeaveMinutes["special_leave"];
                                $AnnualLeaveFiscalYear = round(
                                    ($employee['salary']/30/8) * (($annualLeaveMinutes-$appliedAnnualLeave)/60)
                                );
                            }
                        }
                    }
                    $salaryReportEmployee = new SalaryReportEmployee(
                        Uuid::gen(),
                        $batchId,
                        $employee['employee_id'],
                        $employee['user_name'],
                        $employee['name'],
                        $employee['department'],
                        $employee['position'],
                        (float)$employee['salary'],
                        0,
                        0,
                        0,
                        0,
                        0,
                        (float)$employee['health_insurance'],
                        (float)$employee['labor_insurance'],
                        (float)$employee['pension'],
                        '',
                        0,
                        0,
                        0,
                        $AnnualLeaveFiscalYear
                    );

                    $batchReportBatch->addEmployee($salaryReportEmployee);
                }
            }

            $salaryReportServ = new SalaryReportService();
            $salaryReportServ->addBatch($batchReportBatch);

            $this->redirect('index');

        } catch (Throwable $ex) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = $ex->getMessage();
            $this->redirect('new');
        }

    }

    public function actionBatch($batchId)
    {
        $serv = new SalaryReportService();
        $list = $serv->getListByBatch($batchId);

        $this->render('batch', ['list' => $list, 'batch_id' => $batchId]);
    }

    public function actionEmployee($id): void
    {
        $serv = new SalaryReportService();
        $data = $serv->findBySalaryId($id);
        $this->render('employee', ['data' => $data]);
    }

    public function actionUpdate()
    {
        try {
            $this->checkCSRF('index');

            $serv = new SalaryReportService();
            $employeeSalary = $serv->findBySalaryId($_POST['id']);

            if ($employeeSalary === null) {
                Yii::log("{$_POST['id']} not found", CLogger::LEVEL_ERROR);
                Yii::app()->session[Controller::ERR_MSG_KEY] = "更新失敗";
                $this->redirect("employee?id={$_POST['id']}");
            }

            $employeeSalary->setDraftAllowance($_POST['draft_allowance']);
            $employeeSalary->setTrafficAllowance($_POST['traffic_allowance']);
            $employeeSalary->setOvertimeWage($_POST['overtime_wage']);
            $employeeSalary->setProjectAllowance($_POST['project_allowance']);
            $employeeSalary->setTaxFreeOvertimeWage($_POST['tax_free_overtime_wage']);
            $employeeSalary->setMemo($_POST['memo']);
            $employeeSalary->setOtherPlus($_POST['other_plus']);
            $employeeSalary->setOtherMinus($_POST['other_minus']);
            $employeeSalary->setLeaveSalary($_POST['leave_salary']);

            $serv->setEmployeeSalary($employeeSalary);

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
            $this->redirect("employee?id={$_POST['id']}");

        } catch (Throwable $ex) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = $ex->getMessage();
            $this->redirect("employee?id={$_POST['id']}");
        }
    }

    public function actionEmail(): void
    {
        $this->checkCSRF('index');
        $batchId = $_POST['batch_id'];

        $serv = new SalaryReportService();

        if (empty($_POST['checked'])) {
            $batchEnt = $serv->getAllEmployeesByBatch($batchId);
        } else {
            $batchEnt = $serv->getRangeEmployeeByBatch($batchId, $_POST['checked']);
        }

        if ($batchEnt === null) {
            $this->sendErrAjaxRsp('400', '無薪資料可寄送');
        } else {
            $serv->sendBatchEmail($batchEnt);
            $this->sendSuccAjaxRsp();
        }
    }

    public function actionExport(): void
    {
        $this->checkCSRF('index');

        $batchId = $_POST['batch_id'];

        $serv = new SalaryReportService();

        if (empty($_POST['checked'])) {
            $batchEnt = $serv->getAllEmployeesByBatch($batchId);
        } else {
            $batchEnt = $serv->getRangeEmployeeByBatch($batchId, $_POST['checked']);
        }

        if ($batchEnt !== null) {
            $rows = [];
            $totalSalaryOutcome = 0;
            $employeeCount = 0;

            foreach ($batchEnt->getEmployees() as $employee) {
                $rows[] = [
                    $employee->getEmployeeLoginId(),
                    $employee->getEmployeeName(),
                    $employee->getEmployeeDepartment(),
                    $employee->getEmployeePosition(),
                    $employee->getSalary(),
                    $employee->getDraftAllowance(),
                    $employee->getTrafficAllowance(),
                    $employee->getOvertimeWage(),
                    $employee->getProjectAllowance(),
                    $employee->getLeaveSalary() * -1,
                    $employee->calcTaxableSalaryTotal(),
                    $employee->getTaxFreeOvertimeWage(),
                    $employee->getOtherPlus(),
                    $employee->calcSalaryTotal(),
                    $employee->getHealthInsurance() * -1,
                    $employee->getLaborInsurance() * -1,
                    $employee->getOtherMinus() * -1,
                    $employee->getPension() * -1,
                    $employee->calcDeductionTotal() * -1,
                    $employee->calcRealSalary(),
                    $employee->getMemo()
                ];

                $totalSalaryOutcome += $employee->calcRealSalary();
                $employeeCount++;
            }

            $rows[] = [
                '總人數',
                $employeeCount,
                '薪資總金額',
                $totalSalaryOutcome,
            ];

            $fileName = "{$batchEnt->getBatchId()}_文訊員工薪資報表";

            Helper::newSpreadsheet()
                ->addRow([
                    '員工帳號',
                    '員工姓名',
                    '部門',
                    '職務',
                    '本薪(+)',
                    '稿費津貼(+)',
                    '交通津貼(+)',
                    '應稅加班費(+)',
                    '專案津貼(+)',
                    '請假扣薪(-)',
                    '應稅薪資合計(+)',
                    '免稅加班費(+)',
                    '其他加項(+)',
                    '薪資合計(+)',
                    '健保(-)',
                    '勞保(-)',
                    '其他減項(-)',
                    '退休金提撥(不計算)',
                    '應扣合計(-)',
                    '實領薪資',
                    '備註'
                ])
                ->addRows($rows)
                ->output($fileName);
        }
    }
}