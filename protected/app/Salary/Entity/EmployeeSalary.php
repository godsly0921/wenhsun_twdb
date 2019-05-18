<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Entity;

class EmployeeSalary
{
    private $employeeId;
    private $salary;

    private $healthInsurance;
    private $laborInsurance;
    private $pension;

    public function __construct(
        $employeeId,
        $salary,
        $healthInsurance,
        $laborInsurance,
        $pension
    ) {
        $this->setEmployeeId($employeeId);
        $this->setSalary($salary);
        $this->setHealthInsurance($healthInsurance);
        $this->setLaborInsurance($laborInsurance);
        $this->setPension($pension);
    }

    /**
     * @return mixed
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * @param mixed $employeeId
     */
    private function setEmployeeId($employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @return mixed
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * @param mixed $salary
     */
    private function setSalary($salary): void
    {
        $this->salary = $salary;
    }

    /**
     * @return mixed
     */
    public function getHealthInsurance()
    {
        return $this->healthInsurance;
    }

    /**
     * @param mixed $healthInsurance
     */
    private function setHealthInsurance($healthInsurance): void
    {
        $this->healthInsurance = $healthInsurance;
    }

    /**
     * @return mixed
     */
    public function getLaborInsurance()
    {
        return $this->laborInsurance;
    }

    /**
     * @param mixed $laborInsurance
     */
    private function setLaborInsurance($laborInsurance): void
    {
        $this->laborInsurance = $laborInsurance;
    }

    /**
     * @return mixed
     */
    public function getPension()
    {
        return $this->pension;
    }

    /**
     * @param mixed $pension
     */
    private function setPension($pension): void
    {
        $this->pension = $pension;
    }
}