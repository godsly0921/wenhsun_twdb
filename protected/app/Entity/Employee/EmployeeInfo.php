<?php

declare(strict_types=1);

namespace Wenhsun\Entity\Employee;

use Employee as EmployeeModel;

class EmployeeInfo
{
    private $employeeId;

    public $user_name;
    public $email;
    private $password;

    public $name;
    public $gender;
    public $birth;
    public $person_id;
    public $nationality;

    public $country;
    public $dist;
    public $address;
    public $mobile;
    public $phone;

    public $enable;
    public $door_card_num;
    public $ext_num;
    public $seat_num;

    public $bank_name;
    public $bank_code;
    public $bank_branch_name;
    public $bank_branch_code;
    public $bank_account;
    public $bank_account_name;

    public function __construct(EmployeeId $employeeId)
    {
        $this->employeeId = $employeeId;
    }

    public function setPassword($password)
    {
        $this->password = $this->hashPassword($password);
    }

    public function hashPassword($password)
    {
        return md5($password);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function persist()
    {
        $employeeModel = new EmployeeModel();
        $employeeModel->id = $this->employeeId->employeeId();
        $employeeModel->user_name = $this->user_name;
        $employeeModel->password = $this->getPassword();
        $employeeModel->email = $this->email;
        $employeeModel->name = $this->name;
        $employeeModel->gender = $this->gender;
        $employeeModel->birth = $this->birth;
        $employeeModel->person_id = $this->person_id;
        $employeeModel->nationality = $this->nationality;
        $employeeModel->country = $this->country;
        $employeeModel->dist = $this->dist;
        $employeeModel->address = $this->address;
        $employeeModel->mobile = $this->mobile;
        $employeeModel->phone = $this->phone;
        $employeeModel->enable = $this->enable;
        $employeeModel->door_card_num = $this->door_card_num;
        $employeeModel->ext_num = $this->ext_num;
        $employeeModel->seat_num = $this->seat_num;
        $employeeModel->bank_name = $this->bank_name;
        $employeeModel->bank_code = $this->bank_code;
        $employeeModel->bank_branch_name = $this->bank_branch_name;
        $employeeModel->bank_branch_code = $this->bank_branch_code;
        $employeeModel->bank_account = $this->bank_account;
        $employeeModel->bank_account_name = $this->bank_account_name;

        $now = date('Y-m-d H:i:s');
        $employeeModel->create_at = $now;
        $employeeModel->update_at = $now;

        $employeeModel->save();
    }
}