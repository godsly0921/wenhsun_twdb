<?php

class EmployeeExtensions extends CActiveRecord
{
    public function tableName()
    {
        return 'employee_extensions';
    }

    public function rules()
    {
        return [
            ['ext_number', 'required'],
            ['ext_number', 'length', 'max' => 8],
        ];
    }

    public function relations()
    {
        return [];
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}