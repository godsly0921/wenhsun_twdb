<?php

declare(strict_types=1);

class InfoController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionNew()
    {
        $seats = EmployeeSeats::model()->findAll();
        $exts = EmployeeExtensions::model()->findAll();

        $data = [
            'seats' => $seats,
            'exts' => $exts,
        ];

        $this->render('new', $data);
    }
}