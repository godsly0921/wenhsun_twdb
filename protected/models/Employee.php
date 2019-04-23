<?php

declare(strict_types=1);

class Employee extends CActiveRecord
{
    public function tableName()
    {
        return 'employee';
    }

    public function rules()
    {
        return [
            ['user_name', 'required'],
            ['email', 'required'],
            ['name', 'required'],
            ['gender', 'required'],
            ['birth', 'required'],
            ['person_id', 'required'],
            ['nationality', 'required'],
            ['country', 'required'],
            ['dist', 'required'],
            ['address', 'required'],
            ['mobile', 'required'],
            ['phone', 'required'],
            ['enable', 'required'],
            ['door_card_num', 'required'],
            ['ext_num', 'required'],
            ['seat_num', 'required'],
            ['bank_name', 'required'],
            ['bank_code', 'required'],
            ['bank_branch_name', 'required'],
            ['bank_branch_code', 'required'],
            ['bank_account', 'required'],
            ['bank_account_name', 'required'],
        ];
    }

    public function relations()
    {
        return [
            'ext' => [self::BELONGS_TO, 'EmployeeExtensions', 'ext_num'],
            'seat' => [self::BELONGS_TO, 'EmployeeSeats', 'seat_num'],
        ];
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function scopes() {
        return array(
            'byUpdateAt' => array('order' => 'update_at DESC'),
        );
    }
}