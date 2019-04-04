<?php

class EmployeeExtensionsRepo
{
    public function update($id, $extNumber)
    {
        return Yii::app()->db->createCommand(
            '
              UPDATE employee_extensions
              SET ext_number = :ext_number
              WHERE id = :id
              AND NOT EXISTS(
                select 1 from employee_extensions
                where ext_number = :ext_number 
              )
            '
        )
            ->bindValues([
                ':id' => $id,
                ':ext_number' => $extNumber,
            ])
            ->execute();
    }
}