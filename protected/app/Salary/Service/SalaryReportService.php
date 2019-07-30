<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Service;

use CLogger;
use Common;
use EmployeeService;
use PHPMailer;
use SalaryReport;
use SalaryReportBatch as SalaryReportBatchModel;
use Throwable;
use Wenhsun\Salary\Entity\SalaryReportBatch;
use Wenhsun\Salary\Entity\SalaryReportEmployee;
use Wenhsun\Salary\Exception\SalaryReportServiceException;
use Wenhsun\Salary\Repository\ReportRepository;
use Yii;

class SalaryReportService
{
    public function getBatchList()
    {
        $repo = new ReportRepository();
        return $repo->fetchBatch();
    }

    public function getListByBatch($batchId)
    {
        $repo = new ReportRepository();

        return $repo->fetchEmployeeByBatch($batchId);
    }

    public function getAllEmployeesByBatch($batchId): ?SalaryReportBatch
    {
        $repo = new ReportRepository();

        return $repo->forAllEmployeeByBatch($batchId);
    }

    public function getRangeEmployeeByBatch($batchId, array $id): ?SalaryReportBatch
    {
        $repo = new ReportRepository();

        return $repo->forRangeEmployeeByBatch($batchId, $id);
    }

    public function setEmployeeSalary(SalaryReportEmployee $ent)
    {
        try {

            $repo = new ReportRepository();
            $repo->updateEmployeeSalary($ent);

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            throw new SalaryReportServiceException($ex->getMessage());
        }
    }

    public function deleteBatch(string $batchId): void
    {
        try {
            $repo = new ReportRepository();
            $repo->deleteBatch($batchId);
        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            throw new SalaryReportServiceException($ex->getMessage());
        }
    }

    public function addBatch(SalaryReportBatch $ent)
    {
        $now = Common::now();
        $tx = Yii::app()->db->beginTransaction();
        try {

            $salaryReportBatchModel = new SalaryReportBatchModel();
            $salaryReportBatchModel->batch_id = $ent->getBatchId();
            $salaryReportBatchModel->create_at = $now;
            $salaryReportBatchModel->update_at = $now;
            $salaryReportBatchModel->save();

            if ($salaryReportBatchModel->hasErrors()) {
                throw new SalaryReportServiceException(serialize($salaryReportBatchModel->getErrors()));
            }

            $cnt = 0;
            foreach ($ent->getEmployees() as $employeeEnt) {

                /** @var SalaryReportEmployee $employeeEnt */

                $salaryReportModel = new SalaryReport();
                $salaryReportModel->id = $employeeEnt->getId();
                $salaryReportModel->batch_id = $employeeEnt->getBatchId();
                $salaryReportModel->employee_id = $employeeEnt->getEmployeeId();
                $salaryReportModel->employee_login_id = $employeeEnt->getEmployeeLoginId();
                $salaryReportModel->employee_name = $employeeEnt->getEmployeeName();
                $salaryReportModel->employee_department = $employeeEnt->getEmployeeDepartment();
                $salaryReportModel->employee_position = $employeeEnt->getEmployeePosition();
                $salaryReportModel->salary = $employeeEnt->getSalary();
                $salaryReportModel->draft_allowance = $employeeEnt->getDraftAllowance();
                $salaryReportModel->traffic_allowance = $employeeEnt->getTrafficAllowance();
                $salaryReportModel->overtime_wage = $employeeEnt->getOvertimeWage();
                $salaryReportModel->project_allowance = $employeeEnt->getProjectAllowance();
                $salaryReportModel->leave_salary = $employeeEnt->getLeaveSalary();
                $salaryReportModel->taxable_salary_total = $employeeEnt->calcTaxableSalaryTotal();
                $salaryReportModel->tax_free_overtime_wage = $employeeEnt->getTaxFreeOvertimeWage();
                $salaryReportModel->salary_total = $employeeEnt->calcSalaryTotal();
                $salaryReportModel->health_insurance = $employeeEnt->getHealthInsurance();
                $salaryReportModel->labor_insurance = $employeeEnt->getLaborInsurance();
                $salaryReportModel->pension = $employeeEnt->getPension();
                $salaryReportModel->deduction_total = $employeeEnt->calcDeductionTotal();
                $salaryReportModel->real_salary = $employeeEnt->calcRealSalary();
                $salaryReportModel->status = SalaryReportEmployee::NOT_SET_SALARY_YET;
                $salaryReportModel->create_at = $now;
                $salaryReportModel->update_at = $now;

                $salaryReportModel->save();

                if ($salaryReportModel->hasErrors()) {
                    throw new SalaryReportServiceException(serialize($salaryReportModel->getErrors()));
                }

                $cnt++;
                Yii::log("inserted {$employeeEnt->getEmployeeId()} salary in report\n");
            }

            $tx->commit();

            Yii::log("inserted {$cnt} empoyees\n");

        } catch (Throwable $ex) {
            $tx->rollback();
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($ex->getTraceAsString(), CLogger::LEVEL_ERROR);

            throw new SalaryReportServiceException($ex->getMessage());
        }
    }

    public function findBySalaryId($id): ?SalaryReportEmployee
    {
        $repo = new ReportRepository();

        return $repo->findById($id);
    }

    public function findByBatchAndEmployeeId($batchId, $employeeId)
    {
        $repo = new ReportRepository();

        return $repo->fetchByBatchAndEmployeeId($batchId, $employeeId);
    }

    public function sendBatchEmail(SalaryReportBatch $batchEnt)
    {
        $employeeServ = new EmployeeService();

        foreach ($batchEnt->getEmployees() as $employee) {

            $email = $employeeServ->getEmailByEmployeeId($employee->getEmployeeId());
            Yii::log("{$employee->getEmployeeId()} email: {$email}", CLogger::LEVEL_INFO);

            $this->sendEmail($employee, $email);
        }
    }

    public function sendEmployeeSalaryEmail(SalaryReportEmployee $employee)
    {
        $employeeServ = new EmployeeService();
        $email = $employeeServ->getEmailByEmployeeId($employee->getEmployeeId());

        Yii::log("{$employee->getEmployeeId()} email: {$email}", CLogger::LEVEL_INFO);

        if ($email !== null) {
            $this->sendEmail($employee, $email);
        }
    }

    private function sendEmail(SalaryReportEmployee $employee, $email)
    {
        $mail = new PHPMailer();
        $mail->IsHTML(true);
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->CharSet = 'utf-8';
        $mail->Username = 'wenhsun0509@gmail.com';
        $mail->Password = 'cute0921';
        $mail->From = 'wenhsun0509@gmail.com';
        $mail->FromName = '文訊雜誌社人資系統';
        $mail->addAddress($email);
        $mail->Subject = "薪資通知";
        $mail->Body = "
            <h2>{$employee->getBatchMonth()}月份文訊薪資通知</h2>
            <table style='border: 1px solid black;border-collapse: collapse; width:500px'>
                <thead>
                    <th style='border: 1px solid black'>項目</th>
                    <th style='border: 1px solid black'>內容</th>    
                </thead>
                <tbody>
                    <tr>
                        <td style='border: 1px solid black'>員工帳號</td>
                        <td style='border: 1px solid black'>{$employee->getEmployeeLoginId()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>員工姓名</td>
                        <td style='border: 1px solid black'>{$employee->getEmployeeName()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>部門</td>
                        <td style='border: 1px solid black'>{$employee->getEmployeeDepartment()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>職務</td>
                        <td style='border: 1px solid black'>{$employee->getEmployeePosition()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>本薪</td>
                        <td style='border: 1px solid black'>{$employee->getSalary()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>稿費津貼</td>
                        <td style='border: 1px solid black'>{$employee->getDraftAllowance()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>交通津貼</td>
                        <td style='border: 1px solid black'>{$employee->getTrafficAllowance()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>應稅加班費</td>
                        <td style='border: 1px solid black'>{$employee->getOvertimeWage()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>專案津貼</td>
                        <td style='border: 1px solid black'>{$employee->getProjectAllowance()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>請假扣薪</td>
                        <td style='border: 1px solid black'>-{$employee->getLeaveSalary()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>應稅薪資合計</td>
                        <td style='border: 1px solid black'>{$employee->calcTaxableSalaryTotal()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>免稅加班費</td>
                        <td style='border: 1px solid black'>{$employee->getTaxFreeOvertimeWage()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>其他加項</td>
                        <td style='border: 1px solid black'>{$employee->getOtherPlus()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>薪資合計</td>
                        <td style='border: 1px solid black'>{$employee->calcSalaryTotal()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>健保</td>
                        <td style='border: 1px solid black'>-{$employee->getHealthInsurance()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>勞保</td>
                        <td style='border: 1px solid black'>-{$employee->getLaborInsurance()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>其他減項</td>
                        <td style='border: 1px solid black'>-{$employee->getOtherMinus()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>退休金提撥(不計算)</td>
                        <td style='border: 1px solid black'>-{$employee->getPension()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>應扣合計</td>
                        <td style='border: 1px solid black'>-{$employee->calcDeductionTotal()}</td>
                        </tr>
                    <tr>
                        <td style='border: 1px solid black'>實領薪資</td>
                        <td style='border: 1px solid black'>{$employee->calcRealSalary()}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid black'>備註</td>
                        <td style='border: 1px solid black'>{$employee->getMemo()}</td>
                    </tr>
                </tbody>
            </table>
            ";

        $mail->Send();
    }
}