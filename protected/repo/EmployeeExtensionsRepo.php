<?php

class EmployeeExtensionsRepo
{
    public function update($id, $extNumber)
    {
        return Yii::app()->db->createCommand(
            '
              UPDATE employee_extensions
              SET ext_number = :ext_number, update_at = :update_at
              WHERE id = :id 
            '
        )
            ->bindValues([
                ':id' => $id,
                ':ext_number' => $extNumber,
                ':update_at' => Common::now(),
            ])
            ->execute();
    }

    public function getAvailableExts()
    {
        return Yii::app()->db->createCommand(
            '
              SELECT * FROM employee_extensions e
              WHERE NOT EXISTS(SELECT 1 FROM employee_info i WHERE i.ext_num = e.id)
            '
        )->queryAll();
    }
}