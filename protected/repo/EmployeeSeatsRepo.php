<?php

class EmployeeSeatsRepo
{
    function update($id, $seatName, $seatNumber)
    {
        return Yii::app()->db->createCommand(
            '
              UPDATE employee_seats
              SET seat_name = :seat_name, seat_number = :seat_number, update_at = :update_at
              WHERE id = :id
            '
        )
        ->bindValues([
            ':id' => $id,
            ':seat_name' => $seatName,
            ':seat_number' => $seatNumber,
            ':update_at' => Common::now(),
        ])
        ->execute();
    }
}