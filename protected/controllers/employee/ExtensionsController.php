<?php

class ExtensionsController extends Controller
{
    public function actionIndex()
    {
        $exts = EmployeeExtensions::model()->byUpdateAt()->findAll();
        $this->render('list', ['exts' => $exts]);
    }

    public function actionNew()
    {
        $this->render('new');
    }

    public function actionCreate()
    {
        $this->checkCSRF('index');

        $extNumber = filter_input(INPUT_POST, 'ext_number');

        $exts = EmployeeExtensions::model()->find(
            'ext_number=:ext_number',
            [':ext_number' => $extNumber]
        );

        if ($exts) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '資料已存在';
            $this->redirect('new');
        }

        $exts = new EmployeeExtensions();
        $exts->ext_number = $extNumber;
        $now = Common::now();
        $exts->create_at = $now;
        $exts->update_at = $now;
        $exts->save();

        if ($exts->hasErrors()) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '新增使用者失敗';
            $this->redirect('new');
        }

        $this->redirect('index');
    }

    public function actionEdit($id)
    {
        $ext = EmployeeExtensions::model()->findByPk($id);

        if (!$ext) {
            $this->redirect('index');
        }

        $this->render('edit', ['ext' => $ext]);
    }

    public function actionUpdate()
    {
        $this->checkCSRF('index');

        $pk = filter_input(INPUT_POST, 'id');
        $extNumber = filter_input(INPUT_POST, 'ext_number');

        $exts = EmployeeExtensions::model()->find(
            'ext_number=:ext_number',
            [':ext_number' => $extNumber]
        );

        if ($exts) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '資料已存在';
            $this->redirect("edit?id={$pk}");
        }

        $repo = new EmployeeExtensionsRepo();
        $seats = $repo->update($pk, $extNumber);

        if ($seats) {
            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
        } else {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '更新失敗';
        }

        $this->redirect("edit?id={$pk}");
    }

    public function actionDelete()
    {
        try {
            $this->checkCsrfAjax();
            $pk = filter_input(INPUT_POST, 'id');

            $employee = Employee::model()->find(
                'ext_num=:ext_num',
                [':ext_num' => $pk]
            );

            if ($employee) {
                $this->sendErrAjaxRsp(404, "無法刪除，員工({$employee->user_name})正在使用此分機");
            }

            $ext = EmployeeExtensions::model()->findByPk($pk);
            if (!$ext) {
                $this->sendErrAjaxRsp(404, "資料不存在");
            }

            $ext->delete();
            $this->sendSuccAjaxRsp();

        } catch (Throwable $ex) {
            $this->sendErrAjaxRsp(500, "系統錯誤");
        }
    }

    protected function needLogin(): bool
    {
        return true;
    }
}