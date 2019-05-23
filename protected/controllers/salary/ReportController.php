<?php

declare(strict_types=1);

use Wenhsun\Salary\Service\SalaryReportService;

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

    public function actionEmployee($batchId, $employeeId)
    {
        $serv = new SalaryReportService();
        $data = $serv->findByBatchAndEmployeeId($batchId, $employeeId);

        $this->render('employee', ['data' => $data, 'batch_id' => $batchId]);
    }

    public function actionUpdate()
    {

    }

    public function actionEmail()
    {

    }

    public function actionExport()
    {

    }
}