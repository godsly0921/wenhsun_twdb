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


    public function findEmployeeInRolesList($rolse)
    {
        $role = '';
        $i = 0;
        foreach ($rolse as $key => $value) {
            if ($i == 0) {
                $role .= $value;
            } elseif ($i != 0 && $i > 0) {
                $role .= ',' . $value;
            }
            $i++;
        }
       $select = "SELECT * FROM employee WHERE enable = :enable AND role IN (".$role.")";

        return Yii::app()->db->createCommand($select
        )->bindValues([
            ':enable' => 'Y'
        ])->queryAll();
    }

    public function findEmployeeInRolesListObject($rolse)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '*';
        $criteria->addColumnCondition(array('enable'=>'Y'));
        $criteria->addInCondition('role', $rolse);
        $result = Employee::model() -> findAll($criteria);
        return $result;
    }

    public function findEmployeeInDepartmentListObject($department)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '*';
        $criteria->addColumnCondition(array('enable'=>'Y','delete_status'=>'0','position_type'=>'1'));
        // $criteria->addInCondition('department', $department);
        $result = Employee::model() -> findAll($criteria);
        return $result;
    }

    public function findEmployeeNotInRolesListObject($rolse)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '*';
        $criteria->addColumnCondition(array('enable'=>'Y'));
        $criteria->addNotInCondition('role', $rolse);
        $result = Employee::model() -> findAll($criteria);
        return $result;
    }




    public static function findEmployeeNoPTList($role)
    {
        $datas = Employee::model()->findAll(array(
            'select' => '*',
            'condition' => 'role !=:role',
            'order' => 'id DESC',
            'params' => [
                ':role' => $role,
                ],
            ));

        if ($datas == null) {
            $datas = false;
        }

        return $datas;
    }

    public static function findEmployeeId($employee_id)
    {

        $result = Employee::model()->findByPk([
            'select' => '*',
            'condition' => 'id = :id',
            'params' => [
                ':id' => $employee_id,
            ]
        ]);
        return $result;

    }

    public static function findEmployeeById($part_time_empolyee_id)
    {
        try{
            $result = Employee::model()->find([
                'select' => '*',
                'condition' => 'id = :id',
                'params' => [
                    ':id' => $part_time_empolyee_id,
                ]
            ]);
            return $result;

        }catch(Exception $e ){
            echo $e;
        }
    }

    public static function getEmployeeByRole($id)
    {
        $result = Employee::model()->findAll(array(
                'select' => '*',
                'condition' => 'role=:role',
                'order' => 'id DESC',
                'params' => [
                    ':role' => $id,
                ],
        ));

        if ($result == null) {
            $result = false;
        }

        return $result;

    }

    public static function getPTEmployee($id)
    {
        $result = Employee::model()->findAll(array(
                'select' => '*',
                'condition' => 'role=:role',
                'order' => 'id DESC',
                'params' => [
                    ':role' => $id,
                ],
        ));

        if ($result == null) {
            $result = false;
        }

        return $result;

    }


    public static function getEmployee($employee)
    {

        if($employee == 'all'){
            $result = Employee::model()->findAll(array(
                'select' => '*',
                //'condition' => 'id=:id',
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


    public static function getEmployeeName($name)
    {

        $emp = Employee::model()->find(
            'name=:name',
            [':name' => $name]
        );
        return $emp;
    }

    public static function getEmployeeUserName($user_name)
    {
        $emp = Employee::model()->find(
            'user_name=:user_name',
            [':user_name' => $user_name]
        );
        return $emp;
    }

    public function getEmployeeNameArray() {
        $result = Employee::model()->findAll();
        $emp = array();

        foreach($result as $value) {
            $emp[$value->id] = $value->name;
        }

        return $emp;
    }
}
