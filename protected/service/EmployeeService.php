<?php
class EmployeeService
{
    public static function findEmployeelist()
    {
        $datas = Employee::model()->findAll(array(
            'select' => '*',
            'order' => 'id DESC ',
        ));

        if ($datas == null) {
            $datas = false;
        }

        return $datas;
    }
}
