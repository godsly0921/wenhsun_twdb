<?php

declare(strict_types=1);

use Employee as EmployeeModel;
use Wenhsun\Entity\Employee\EmployeeId;
use Wenhsun\Entity\Employee\EmployeeInfo;

class ManagementController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {
        $list = EmployeeModel::model()->byUpdateAt()->findAll();
        $this->render('list', ['list' => $list]);
    }

    public function actionNew()
    {
        $extRepo = new EmployeeExtensionsRepo();
        $exts = $extRepo->getAvailableExts();
        $seatsRepo = new EmployeeSeatsRepo();
        $seats = $seatsRepo->getAvailableSeats();
        $roles = Group::model()->findAll();

        $data = [
            'seats' => $seats,
            'exts' => $exts,
            'roles' => $roles,
        ];

        $this->render('new', $data);
    }

    public function actionCreate()
    {
        try {

            $this->checkCSRF('index');

            $this->validateBeforeCreate($_POST);
            $employeeInfo = $this->createEmployeeInfo($_POST);
            $employeeInfo->persist();
            $this->redirect('index');
        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '新增錯誤';
            $this->redirect('new');
        }
    }

    public function actionEdit($id)
    {
        $data = EmployeeModel::model()->findByPk($id);

        if (!$data) {
            $this->redirect('index');
        }

        $data->birth = str_replace("-", "/", $data->birth);
        $data->birth = explode(" ", $data->birth)[0];

        $extRepo = new EmployeeExtensionsRepo();
        $exts = $extRepo->getAvailableExts();
        $exts = array_merge($exts, [['id' => $data->ext->id,'ext_number' => $data->ext->ext_number]]);
        $seatsRepo = new EmployeeSeatsRepo();
        $seats = $seatsRepo->getAvailableSeats();
        $seats = array_merge($seats, [['id' => $data->seat->id, 'seat_number' => $data->seat->seat_number]]);
        $roles = Group::model()->findAll();

        $this->render(
            'edit',
            [
                'data' => $data,
                'exts' => $exts,
                'seats' => $seats,
                'roles' => $roles,
            ]
        );
    }

    public function actionUpdatePassword()
    {
        $this->checkCSRF('index');

        try {

            $employeeInfoModel = $this->validateBeforeUpdate($_POST['id']);

            $employeeInfo = new EmployeeInfo(new EmployeeId());
            $employeeInfoModel->password = $employeeInfo->hashPassword($_POST['password']);
            $employeeInfoModel->update_at = Common::now();
            $employeeInfoModel->update();

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
            $this->redirect("edit?id={$_POST['id']}");

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '更新失敗';
            $this->redirect("edit?id={$_POST['id']}");
        }
    }

    public function actionUpdate()
    {
        try {

            $this->checkCSRF('index');
            $employeeInfoModel = $this->validateBeforeUpdate($_POST['id']);

            foreach ($_POST as $key => $val) {
                if ($key === "_token" || $key === "id" || $key === "zipcode") {
                    continue;
                }
                $employeeInfoModel->{$key} = $val;
            }

            $employeeInfoModel->update_at = Common::now();
            $employeeInfoModel->update();

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
            $this->redirect("edit?id={$_POST['id']}");

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '更新失敗';
            $this->redirect("edit?id={$_POST['id']}");
        }
    }

    public function actionDelete()
    {
        try {
            $this->checkCsrfAjax();

            $pk = filter_input(INPUT_POST, 'id');
            $employee = EmployeeModel::model()->findByPk($pk);
            if (!$employee) {
                $this->sendErrAjaxRsp(404, "資料不存在");
            }

            $employee->delete();
            $this->sendSuccAjaxRsp();

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            $this->sendErrAjaxRsp(500, "系統錯誤");
        }
    }

    private function validateBeforeUpdate($id)
    {
        $employeeInfoModel = EmployeeModel::model()->findByPk($id);

        if (!$employeeInfoModel) {
            Yii::log("employee info not found by id ({$_POST['id']})", CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '更新失敗';
            $this->redirect("edit?id={$id}");
        }

        return $employeeInfoModel;
    }

    private function validateBeforeCreate(array $post)
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
            EmployeeModel::model()->find("user_name=:user_name", [':user_name' => $post['user_name']])
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

        if (!empty($post['password'])) {
            $employeeInfo->setPassword($post['password']);
        }

        return $employeeInfo;
    }

    /**
     * @param $id
     * @throws CException
     */
    public function actionContract($id)
    {
        $data = EmployeeModel::model()->findByPk($id);

        if (!$data) {
            $this->redirect('list');
        }

        $this->renderPartial("contract", ['data' => $data]);
    }
}