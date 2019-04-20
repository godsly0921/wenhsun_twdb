<?php

declare(strict_types=1);

namespace Wenhsun\Employee;

class EmployeeInfo
{
    private $employeeId;

    public $userName;
    public $email;
    private $password;

    public $name;
    public $gender;
    public $birth;
    public $personId;
    public $nationality;

    public $city;
    public $dist;
    public $address;
    public $mobile;
    public $phone;

    public $isAvailable;
    public $doorCardNum;
    public $extensionNum;
    public $seatNum;

    public $bankName;
    public $bankCode;
    public $bankBranchName;
    public $bankBranchCode;
    public $bankAcct;
    public $bankAcctName;

    public function __construct(EmployeeId $employeeId)
    {
        $this->employeeId = $employeeId;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return md5($this->password);
    }
}