<?php

declare(strict_types=1);

use Wenhsun\Salary\Service\SalaryReportService;
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

    public function actionBatch($batchId)
    {
        $serv = new SalaryReportService();
        $list = $serv->getListByBatch($batchId);

        $this->render('batch', ['list' => $list, 'batch_id' => $batchId]);
    }

    public function actionEmployee($id)
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

            $serv->setEmployeeSalary($employeeSalary);

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
            $this->redirect("employee?id={$_POST['id']}");

        } catch (Throwable $ex) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = $ex->getMessage();
            $this->redirect("employee?id={$_POST['id']}");
        }
    }

    public function actionEmail()
    {

    }

    public function actionExport()
    {
        $this->checkCSRF('index');

        $batchId = $_POST['batch_id'];

        $serv = new SalaryReportService();
        $batchEnt = $serv->getAllEmployeesByBatch($batchId);

        $rows = [];
        foreach ($batchEnt->getEmployees() as $employee) {
            $rows[] = [
                $employee->getEmployeeLoginId(),
                $employee->getEmployeeName(),
                $employee->getSalary(),
                $employee->getDraftAllowance(),
                $employee->getTrafficAllowance(),
                $employee->getOvertimeWage(),
                $employee->getProjectAllowance(),
                $employee->calcTaxableSalaryTotal(),
                $employee->getTaxFreeOvertimeWage(),
                $employee->calcSalaryTotal(),
                $employee->getHealthInsurance() * -1,
                $employee->getLaborInsurance() * -1,
                $employee->getPension() * -1,
                $employee->calcDeductionTotal() * -1,
                $employee->calcRealSalary(),
            ];
        }

        $fileName = "{$batchEnt->getBatchId()}_文訊員工薪資報表";

        Helper::newSpreadsheet()
            ->addRow([
                '員工帳號',
                '員工姓名',
                '本薪(+)',
                '稿費津貼(+)',
                '交通津貼(+)',
                '應稅加班費(+)',
                '專案津貼(+)',
                '應稅薪資合計(+)',
                '免稅加班費(+)',
                '薪資合計(+)',
                '健保(-)',
                '勞保(-)',
                '退休金提撥(-)',
                '應扣合計(-)',
                '實領薪資'
            ])
            ->addRows($rows)
            ->output($fileName);
    }
}