<?php

declare(strict_types=1);

class SalaryEmployee extends CActiveRecord
{
    public function tableName()
    {
        return 'salary_employee';
    }

    public function rules()
    {
        return [
            ['employee_id', 'required'],
            ['health_insurance', 'required'],
            ['labor_insurance', 'required'],
            ['pension', 'required'],
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