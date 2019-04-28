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


    public static function getEmployee($employee)
    {

        if($employee == 'all'){
            $result = Employee::model()->findAll(array(
                'select' => '*',
                'order' => 'id DESC ',
            ));

        }else{
            $result = Employee::model()->findAll([
                'select' => '*',
                'condition' => 'id=:id',
                'params' => [
                    ':id' => $employee,
                ],
                'order' => 'id DESC ',
            ]);
        }

        return $result;
    }
}
