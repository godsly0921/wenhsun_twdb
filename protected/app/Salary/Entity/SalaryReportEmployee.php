<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Entity;

class SalaryReportEmployee
{
    public const NOT_SET_SALARY_YET = 'YET';
    public const SET_SALARY = 'OKZ';

    private $id;
    private $batchId;
    private $employeeId;
    private $employeeLoginId;
    private $employeeName;
    private $employeeDepartment;
    private $employeePosition;
    private $salary;
    private $draftAllowance;
    private $trafficAllowance;
    private $overtimeWage;
    private $projectAllowance;
    private $taxFreeOvertimeWage;
    private $healthInsurance;
    private $laborInsurance;
    private $pension;
    private $status;
    private $memo;
    private $otherPlus;
    private $otherMinus;
    private $leaveSalary;
    private $AnnualLeaveFiscalYear;

    public function getLeaveSalary()
    {
        return $this->leaveSalary;
    }

    public function setLeaveSalary($leaveSalary): void
    {
        $this->leaveSalary = $leaveSalary;
    }

    public function getBatchMonth(): string
    {
        $batchId = $this->getBatchId();

        return ltrim(substr($batchId, 4), '0');
    }

    /**
     * @return mixed
     */
    public function getEmployeeLoginId()
    {
        return $this->employeeLoginId;
    }

    /**
     * @return mixed
     */
    public function getEmployeeName()
    {
        return $this->employeeName;
    }

    public function getEmployeeDepartment()
    {
        return $this->employeeDepartment;
    }

    public function getEmployeePosition()
    {
        return $this->employeePosition;
    }

    /**
     * @param mixed $draftAllowance
     */
    public function setDraftAllowance($draftAllowance): void
    {
        $this->draftAllowance = $draftAllowance;
    }

    /**
     * @param mixed $trafficAllowance
     */
    public function setTrafficAllowance($trafficAllowance): void
    {
        $this->trafficAllowance = $trafficAllowance;
    }

    /**
     * @param mixed $overtimeWage
     */
    public function setOvertimeWage($overtimeWage): void
    {
        $this->overtimeWage = $overtimeWage;
    }

    /**
     * @param mixed $projectAllowance
     */
    public function setProjectAllowance($projectAllowance): void
    {
        $this->projectAllowance = $projectAllowance;
    }

    /**
     * @param mixed $taxFreeOvertimeWage
     */
    public function setTaxFreeOvertimeWage($taxFreeOvertimeWage): void
    {
        $this->taxFreeOvertimeWage = $taxFreeOvertimeWage;
    }

    public function getOtherPlus()
    {
        return $this->otherPlus;
    }

    public function getOtherMinus()
    {
        return $this->otherMinus;
    }

    public function getMemo()
    {
        return $this->memo;
    }

    public function setMemo($memo): void
    {
        $this->memo = $memo;
    }

    public function setOtherPlus($otherPlus): void
    {
        $this->otherPlus = $otherPlus;
    }

    public function setOtherMinus($otherMinus): void
    {
        $this->otherMinus = $otherMinus;
    }

    public function __construct(
        $id,
        $batchId,
        $employeeId,
        $employeeLoginId,
        $employeeName,
        $employeeDepartment,
        $employeePosition,
        $salary,
        $draftAllowance,
        $trafficAllowance,
        $overtimeWage,
        $projectAllowance,
        $taxFreeOvertimeWage,
        $healthInsurance,
        $laborInsurance,
        $pension,
        $memo,
        $otherPlus,
        $otherMinus,
        $leaveSalary,
        $AnnualLeaveFiscalYear
    ) {
        $this->id = $id;
        $this->batchId = $batchId;
        $this->employeeId = $employeeId;
        $this->employeeLoginId = $employeeLoginId;
        $this->employeeName = $employeeName;
        $this->employeeDepartment = $employeeDepartment;
        $this->employeePosition = $employeePosition;
        $this->salary = $salary;
        $this->draftAllowance = $draftAllowance;
        $this->trafficAllowance = $trafficAllowance;
        $this->overtimeWage = $overtimeWage;
        $this->projectAllowance = $projectAllowance;
        $this->taxFreeOvertimeWage = $taxFreeOvertimeWage;
        $this->healthInsurance = $healthInsurance;
        $this->laborInsurance = $laborInsurance;
        $this->pension = $pension;
        $this->memo = $memo;
        $this->otherPlus = $otherPlus;
        $this->otherMinus = $otherMinus;
        $this->leaveSalary = $leaveSalary;
        $this->AnnualLeaveFiscalYear = $AnnualLeaveFiscalYear;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getBatchId()
    {
        return $this->batchId;
    }

    /**
     * @return mixed
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * @return mixed
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * @return mixed
     */
    public function getDraftAllowance()
    {
        return $this->draftAllowance;
    }

    /**
     * @return mixed
     */
    public function getTrafficAllowance()
    {
        return $this->trafficAllowance;
    }

    /**
     * @return mixed
     */
    public function getOvertimeWage()
    {
        return $this->overtimeWage;
    }

    /**
     * @return mixed
     */
    public function getProjectAllowance()
    {
        return $this->projectAllowance;
    }

    /**
     * @return mixed
     */
    public function calcTaxableSalaryTotal()
    {
        return $this->salary + $this->draftAllowance + $this->trafficAllowance + $this->overtimeWage + $this->projectAllowance - $this->leaveSalary;
    }

    /**
     * @return mixed
     */
    public function getTaxFreeOvertimeWage()
    {
        return $this->taxFreeOvertimeWage;
    }

    /**
     * @return mixed
     */
    public function calcSalaryTotal()
    {
        return $this->calcTaxableSalaryTotal() + $this->taxFreeOvertimeWage + $this->otherPlus + $this->AnnualLeaveFiscalYear;
    }

    /**
     * @return mixed
     */
    public function getHealthInsurance()
    {
        return $this->healthInsurance;
    }

    /**
     * @return mixed
     */
    public function getLaborInsurance()
    {
        return $this->laborInsurance;
    }

    /**
     * @return mixed
     */
    public function getPension()
    {
        return $this->pension;
    }

    /**
     * @return mixed
     */
    public function calcDeductionTotal()
    {
        return $this->healthInsurance + $this->laborInsurance + $this->otherMinus;
    }

    /**
     * @return mixed
     */
    public function calcRealSalary()
    {
        return $this->calcSalaryTotal() - $this->calcDeductionTotal();
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @return mixed
     */
    public function AnnualLeaveFiscalYear()
    {
        return $this->AnnualLeaveFiscalYear;
    }
}