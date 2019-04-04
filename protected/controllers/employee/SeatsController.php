<?php

class SeatsController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {
        $seats = EmployeeSeats::model()->findAll();

        $this->render('list', ['seats' => $seats]);
    }

    public function actionNew()
    {
        $this->render('new');
    }

    public function actionCreate()
    {
        $this->checkCSRF('index');

        $seatName = filter_input(INPUT_POST, 'seat_name');
        $seatNumber = filter_input(INPUT_POST, 'seat_number');

        $seats = EmployeeSeats::model()->find(
            'seat_name=:seat_name AND seat_number=:seat_number',
            [':seat_name' => $seatName, ':seat_number' => $seatNumber]
        );

        if ($seats) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '資料已存在';
            $this->redirect('new');
        }

        $seats = new EmployeeSeats();
        $seats->seat_name = $seatName;
        $seats->seat_number = $seatNumber;
        $now = Common::now();
        $seats->create_at = $now;
        $seats->update_at = $now;
        $seats->save();

        if ($seats->hasErrors()) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '新增使用者失敗';
            $this->redirect('new');
        }

        $this->redirect('index');
    }

    public function actionEdit($id)
    {
        $seat = EmployeeSeats::model()->findByPk($id);

        if (!$seat) {
            $this->redirect('index');
        }

        $this->render('edit', ['seat' => $seat]);
    }

    public function actionUpdate()
    {
        $this->checkCSRF('index');

        $pk = filter_input(INPUT_POST, 'id');
        $seatName = filter_input(INPUT_POST, 'seat_name');
        $seatNumber = filter_input(INPUT_POST, 'seat_number');

        $repo = new EmployeeSeatsRepo();
        $seats = $repo->update($pk, $seatName, $seatNumber);

        if ($seats) {
            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
        } else {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '座位已存在';
        }

        $this->redirect("edit?id={$pk}");
    }

    public function actionDelete()
    {
        try {
            $this->checkCsrfAjax();

            $pk = filter_input(INPUT_POST, 'id');
            $ext = EmployeeSeats::model()->findByPk($pk);
            if (!$ext) {
                $this->sendErrAjaxRsp(404, "資料不存在");
            }

            $ext->delete();
            $this->sendSuccAjaxRsp();

        } catch (Throwable $ex) {
            $this->sendErrAjaxRsp(500, "系統錯誤");
        }
    }
}