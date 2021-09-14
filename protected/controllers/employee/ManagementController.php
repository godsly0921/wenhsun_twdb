<?php

declare(strict_types=1);

use Employee as EmployeeModel;
use Wenhsun\Employee\Service\DepartmentQueryService;
use Wenhsun\Entity\Employee\EmployeeId;
use Wenhsun\Entity\Employee\EmployeeInfo;
use yidas\phpSpreadsheet\Helper;

class ManagementController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {

        $sql = "SELECT 
        CASE
            WHEN e.user_name LIKE 'HSUN%' THEN CONCAT('HSUN',LPAD(CONVERT(SUBSTRING_INDEX(e.user_name,'HSUN',-1),UNSIGNED INTEGER),3,0))
            WHEN e.user_name LIKE 'KSPT%' THEN CONCAT('KSPT',LPAD(CONVERT(SUBSTRING_INDEX(e.user_name,'KSPT',-1),UNSIGNED INTEGER),3,0))
            WHEN e.user_name LIKE 'KS%' THEN CONCAT('KS',LPAD(CONVERT(SUBSTRING_INDEX(e.user_name,'KS',-1),UNSIGNED INTEGER),3,0))
            WHEN e.user_name LIKE 'PT%' THEN CONCAT('PT',LPAD(CONVERT(SUBSTRING_INDEX(e.user_name,'PT',-1),UNSIGNED INTEGER),3,0))
        END as num,
        e.*,
        ext.ext_number,
        seats.seat_number,
        seats.seat_name
        FROM `employee` e
        LEFT JOIN employee_extensions ext ON e.ext_num=ext.id
        LEFT JOIN employee_seats seats ON e.seat_num=seats.id
        WHERE e.delete_status<>1
        ORDER BY num ASC";
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        // $list = EmployeeModel::model()->byUsernameAsc()->findAll(array('condition'=>'delete_status<>1'));
        $this->render('list', ['list' => $list]);
    }

    public function actionExport()
    {
        $this->checkCSRF('index');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index');
        }
        $roles = Group::model()->findAll();
        $roles_map = array();
        foreach ($roles as $key => $value) {
            $roles_map[$value['id']] = $value['group_name'];
        }
        $list = EmployeeModel::model()->byUsernameAsc()->findAll(array('condition'=>'delete_status<>1'));

        if (empty($list)) {
            $this->redirect('index');
        }

        $fileName = '員工資料匯出';

        $rows = [];
        //員工列表匯出功能：除了帳號與姓名，還需要匯出1部門、2職務、3生日、4身分證字號、5手機、6電子郵件、7地址、8匯款資料，這幾個欄位，其他則不用
        foreach ($list as $data) {
            $rows[] = [
                $data->user_name,
                $data->name,
                $data->ext_num,
                $data->seat_num,
                $data->department,
                $data->onboard_date,
                isset($roles_map[$data->role])?$roles_map[$data->role]:"",
                $data->position,
                str_replace('-', '/', $data->birth),
                $data->person_id,
                $data->mobile,
                $data->email,
                $data->country . $data->dist . $data->address,
                $data->bank_name,
                $data->bank_code,
                $data->bank_branch_name,
                $data->bank_branch_code,
                $data->bank_account,
                $data->bank_account_name,
                str_replace('-', '/', $data->update_at),
                str_replace('-', '/', $data->create_at),
            ];
        }

        Helper::newSpreadsheet()
            ->addRow([
                '帳號',
                '姓名',
                '分機',
                '座位',
                '部門',
                '到職日',
                '角色',
                '職務',
                '生日',
                '身分證字號',
                '手機',
                '電子郵件',
                '地址',
                '銀行名稱',
                '銀行代碼',
                '分行名稱',
                '分行代碼',
                '帳號',
                '戶名',
                '修改時間',
                '建立時間',
            ])
            ->addRows(
                $rows
            )
            ->output($fileName);
    }

    public function actionNew()
    {
        $extRepo = new EmployeeExtensionsRepo();
        $exts = $extRepo->getAvailableExts();
        $seatsRepo = new EmployeeSeatsRepo();
        $seats = $seatsRepo->getAvailableSeats();
        $roles = Group::model()->findAll();

        $departmentQueryServ = new DepartmentQueryService();
        $departments = $departmentQueryServ->getDepartments();

        $data = [
            'seats' => $seats,
            'exts' => $exts,
            'roles' => $roles,
            'departments' => $departments,
        ];

        $this->render('new', $data);
    }

    public function actionCreate()
    {
        try {

            Yii::log("starting to create employee {$_POST['user_name']}", CLogger::LEVEL_INFO);
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

        if (!empty($data->birth)) {
            $data->birth = explode(' ', $data->birth)[0];
        }

        $extRepo = new EmployeeExtensionsRepo();
        $exts = $extRepo->getAvailableExts();
        if (!empty($data->ext)) {
            $exts = array_merge($exts, [['id' => $data->ext->id,'ext_number' => $data->ext->ext_number]]);
        }

        $seatsRepo = new EmployeeSeatsRepo();
        $seats = $seatsRepo->getAvailableSeats();
        if (!empty($data->seat)) {
            $seats = array_merge($seats, [[
                'id' => $data->seat->id,
                'seat_number' => $data->seat->seat_number,
                'seat_name' => $data->seat->seat_name,
            ]]);
        }

        $roles = Group::model()->findAll();

        $departmentQueryServ = new DepartmentQueryService();
        $departments = $departmentQueryServ->getDepartments();

        $this->render(
            'edit',
            [
                'data' => $data,
                'exts' => $exts,
                'seats' => $seats,
                'roles' => $roles,
                'departments' => $departments,
            ]
        );
    }

    public function actionUpdatePassword()
    {
        Yii::log("starting to update employee {$_POST['id']}", CLogger::LEVEL_INFO);

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


                if (!empty($val)) {
                    $employeeInfoModel->{$key} = $val;
                } else {
                    $employeeInfoModel->{$key} = null;
                }

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

            $employee->delete_status = 1;
            $employee->save();
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

            if (empty($employeeInfo->{$key}) && !empty($val)) {
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
        $employee_service = new EmployeeService();
        // 抓出社長
        $management_data = $employee_service->getEmployeeByRole(27);
        $this->renderPartial("contract", ['data' => $data,'management_data' => $management_data]);
    }
}