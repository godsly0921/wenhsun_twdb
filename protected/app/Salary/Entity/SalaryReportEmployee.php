<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Entity;

class SalaryReportEmployee
{
    const NOT_SET_SALARY_YET = "YET";
    const SET_SALARY = "OKZ";

    private $id;
    private $employeeId;
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

    public function __construct(
        $id,
        $employeeId,
        $salary,
        $draftAllowance,
        $trafficAllowance,
        $overtimeWage,
        $projectAllowance,
        $taxFreeOvertimeWage,
        $healthInsurance,
        $laborInsurance,
        $pension
    ) {
        $this->id = $id;
        $this->employeeId = $employeeId;
        $this->salary = $salary;
        $this->draftAllowance = $draftAllowance;
        $this->trafficAllowance = $trafficAllowance;
        $this->overtimeWage = $overtimeWage;
        $this->projectAllowance = $projectAllowance;
        $this->taxFreeOvertimeWage = $taxFreeOvertimeWage;
        $this->healthInsurance = $healthInsurance;
        $this->laborInsurance = $laborInsurance;
        $this->pension = $pension;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
        return $this->salary + $this->draftAllowance + $this->trafficAllowance + $this->overtimeWage + $this->projectAllowance;
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
        return $this->calcTaxableSalaryTotal() + $this->taxFreeOvertimeWage;
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
        return $this->healthInsurance + $this->laborInsurance + $this->pension;
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



}