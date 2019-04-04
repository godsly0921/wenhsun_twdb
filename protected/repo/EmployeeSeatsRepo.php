<?php

class EmployeeSeatsRepo
{
    function update($id, $seatName, $seatNumber)
    {
        return Yii::app()->db->createCommand(
            '
              UPDATE employee_seats
              SET seat_name = :seat_name, seat_number = :seat_number
              WHERE id = :id
              AND NOT EXISTS(
                select 1 from employee_seats
                where seat_name = :seat_name
                and seat_number = :seat_number 
              )
            '
        )
        ->bindValues([
            ':id' => $id,
            ':seat_name' => $seatName,
            ':seat_number' => $seatNumber,
        ])
        ->execute();
    }
}