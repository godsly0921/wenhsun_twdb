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

    public function getAvailableSeats()
    {
        return Yii::app()->db->createCommand(
            '
              SELECT * FROM employee_seats e
              WHERE NOT EXISTS(SELECT 1 FROM employee i WHERE i.seat_num = e.id)
            '
        )->queryAll();
    }

    public function getSeatEmployeeInfo()
    {
        return Yii::app()->db->createCommand(
            '
              SELECT * FROM employee_seats s
              LEFT JOIN employee e on s.id = e.seat_num
              LEFT JOIN employee_extensions p on e.ext_num = p.id
              LEFT JOIN `group` g on e.`role` = g.id
              ORDER BY seat_name ASC, seat_number ASC
            '
        )->queryAll();
    }
}