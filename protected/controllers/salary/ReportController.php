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

    }

    public function actionEmployee($batchId, $employeeId)
    {

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