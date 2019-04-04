<?php

class ExtensionsController extends Controller
{
    public function actionIndex()
    {
        $exts = EmployeeExtensions::model()->findAll();
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

        $repo = new EmployeeExtensionsRepo();
        $seats = $repo->update($pk, $extNumber);

        if ($seats) {
            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
        } else {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '分機已存在';
        }

        $this->redirect("edit?id={$pk}");
    }

    public function actionDelete()
    {

    }

    protected function needLogin(): bool
    {
        return true;
    }
}