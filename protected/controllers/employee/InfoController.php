<?php

declare(strict_types=1);

use EmployeeInfo as EmployeeInfoModel;
use Wenhsun\Entity\Employee\EmployeeId;
use Wenhsun\Entity\Employee\EmployeeInfo;

class InfoController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {
        $list = EmployeeInfoModel::model()->byUpdateAt()->findAll();
        $ext = EmployeeInfoModel::model()->find(
            "id=:id",
            [":id" => $list]
        );
        $this->render('list', ['list' => $list]);
    }

    public function actionNew()
    {
        $extRepo = new EmployeeExtensionsRepo();
        $exts = $extRepo->getAvailableExts();
        $seatsRepo = new EmployeeSeatsRepo();
        $seats = $seatsRepo->getAvailableSeats();

        $data = [
            'seats' => $seats,
            'exts' => $exts,
        ];

        $this->render('new', $data);
    }

    public function actionCreate()
    {
        try {
            $this->validateBeforePersist($_POST);
            $employeeInfo = $this->createEmployeeInfo($_POST);
            $employeeInfo->persist();
            $this->redirect('index');
        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '新增錯誤';
            $this->redirect('new');
        }
    }

    private function validateBeforePersist(array $post)
    {
        if (empty($post['ext_num'])) {
            Yii::log("無可用分機號碼", CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '無可用分機號碼';
            $this->redirect('new');
        }

        if (empty($post['ext_num'])) {
            Yii::log("無可用座位", CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '無可用座位';
            $this->redirect('new');
        }

        if (
        EmployeeInfoModel::model()->find("user_name=:user_name", [':user_name' => $post['user_name']])
        ) {
            Yii::log("帳號已存在({$post['user_name']})", CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '帳號已存在';
            $this->redirect('new');
        }
    }

    private function createEmployeeInfo(array $post): EmployeeInfo
    {
        $employeeInfo = new EmployeeInfo(new EmployeeId());
        foreach ($post as $key => $val) {

            if ($key === 'password' || $key === 'password_confirm') {
                continue;
            }

            if (empty($employeeInfo->{$key})) {
                $employeeInfo->{$key} = $val;
            }
        }
        $employeeInfo->setPassword($post['password']);

        return $employeeInfo;
    }
}