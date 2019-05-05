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
            ['name', 'required'],
            ['gender', 'required'],
            ['role', 'required'],
            ['enable', 'required'],
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