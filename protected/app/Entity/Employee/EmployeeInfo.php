<?php

declare(strict_types=1);

namespace Wenhsun\Entity\Employee;

use EmployeeInfo as EmployeeInfoModel;

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
        $employeeInfoModel = new EmployeeInfoModel();
        $employeeInfoModel->user_name = $this->user_name;
        $employeeInfoModel->password = $this->getPassword();
        $employeeInfoModel->email = $this->email;
        $employeeInfoModel->name = $this->name;
        $employeeInfoModel->gender = $this->gender;
        $employeeInfoModel->birth = $this->birth;
        $employeeInfoModel->person_id = $this->person_id;
        $employeeInfoModel->nationality = $this->nationality;
        $employeeInfoModel->country = $this->country;
        $employeeInfoModel->dist = $this->dist;
        $employeeInfoModel->address = $this->address;
        $employeeInfoModel->mobile = $this->mobile;
        $employeeInfoModel->phone = $this->phone;
        $employeeInfoModel->enable = $this->enable;
        $employeeInfoModel->door_card_num = $this->door_card_num;
        $employeeInfoModel->ext_num = $this->ext_num;
        $employeeInfoModel->seat_num = $this->seat_num;
        $employeeInfoModel->bank_name = $this->bank_name;
        $employeeInfoModel->bank_code = $this->bank_code;
        $employeeInfoModel->bank_branch_name = $this->bank_branch_name;
        $employeeInfoModel->bank_branch_code = $this->bank_branch_code;
        $employeeInfoModel->bank_account = $this->bank_account;
        $employeeInfoModel->bank_account_name = $this->bank_account_name;

        $now = date('Y-m-d H:i:s');
        $employeeInfoModel->create_at = $now;
        $employeeInfoModel->update_at = $now;

        $employeeInfoModel->save();
    }
}