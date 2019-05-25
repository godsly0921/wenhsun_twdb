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

    public static function findEmployeeId($employee_id)
    {
        $result = Employee::model()->findByPk([
            'condition' => 'id=:id',
            'params' => [
                ':id' => $employee_id,
            ]
        ]);

        return $result;

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

    public function getEmailByEmployeeId($employeeId): ?string
    {
        $employeeModel = Employee::model()->findByPk($employeeId);

        if (!$employeeModel || empty($employeeModel['email'])) {
            return null;
        }

        return $employeeModel['email'];
    }
}
