<?php

declare(strict_types=1);

class SalaryReport extends CActiveRecord
{
    public function tableName()
    {
        return 'salary_report';
    }

    public function rules()
    {
        return [
            ['employee_id', 'required'],
            ['salary', 'required'],
            ['draft_allowance', 'required'],
            ['traffic_allowance', 'required'],
            ['overtime_wage', 'required'],
            ['project_allowance', 'required'],
            ['taxable_salary_total', 'required'],
            ['tax_free_overtime_wage', 'required'],
            ['salary_total', 'required'],
            ['health_insurance', 'required'],
            ['labor_insurance', 'required'],
            ['pension', 'required'],
            ['deduction_total', 'required'],
            ['real_salary', 'required'],
            ['status', 'required'],
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