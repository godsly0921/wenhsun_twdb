<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/15
 * Time: 下午 11:42
 */
class SpecialcaseController extends Controller
{
    public $layout = "//layouts/back_end";

//    protected function beforeAction($action)
//    {
//        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
//    }

    public function actionIndex()
    {
        $service = new SpecialcaseService();
        $model = $service->findSpecialcase();

        $this->render('index',['model'=>$model]);
    }



    public function actionCreate()
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostCreate() : $this->doGetCreate();
    }

    private function doPostCreate()
    {
        if (!CsrfProtector::comparePost()) {
            $this->redirect('index');
        }

        $inputs = [];
        $inputs["title"] = filter_input(INPUT_POST, "title");
        $inputs["member_id"] = filter_input(INPUT_POST, "member_id");
        $inputs["application_time"] = filter_input(INPUT_POST, "application_time");
        $inputs["category"] = filter_input(INPUT_POST, "category");
        $inputs["approval_status"] = filter_input(INPUT_POST, "approval_status");
        $inputs["approval_time"] = filter_input(INPUT_POST, "approval_time");
        $inputs["approval_account_id"] = filter_input(INPUT_POST, "approval_account_id");
        $inputs["member_ip"] = filter_input(INPUT_POST, "member_ip");
        $inputs["msg"] = filter_input(INPUT_POST, "msg");

        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $contactService = new SpecialcaseService();
        $contactModel = $contactService->create($inputs);

        if ($contactModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $contactModel->getErrors();
            $this->redirect('create');
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect(Yii::app()->createUrl('specialcase/index'));
        }
    }

    private function doGetCreate()
    {
        CsrfProtector::putToken();
        $service = new CategoryService();
        $category = $service->findCategorys();
        $this->render('create',["category"=>$category]);
        $this->clearMsg();


    }

    /**
     * @param $id
     */
    public function actionUpdate($id = null)
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostUpdate() : $this->doGetUpdate($id);

    }

    private function doPostUpdate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs = [];
        $inputs["id"] = filter_input(INPUT_POST, "id");
        $inputs["title"] = filter_input(INPUT_POST, "title");
        $inputs["member_id"] = filter_input(INPUT_POST, "member_id");
        $inputs["application_time"] = filter_input(INPUT_POST, "application_time");
        $inputs["category"] = filter_input(INPUT_POST, "category");
        $inputs["approval_status"] = filter_input(INPUT_POST, "approval_status");
        $inputs["approval_time"] = filter_input(INPUT_POST, "approval_time");
        $inputs["approval_account_id"] = filter_input(INPUT_POST, "approval_account_id");
        $inputs["member_ip"] = filter_input(INPUT_POST, "member_ip");
        $inputs["msg"] = filter_input(INPUT_POST, "msg");

        $service = new SpecialcaseService();
        $model = $service->updateSpecialcase($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '修改成功';
        }

        $this->redirect('update/'.$inputs['id']);
    }

    private function doGetUpdate($id)
    {
        $model = Specialcase::model()->findByPk($id);

        if ($model !== null) {
            $service = new CategoryService();
            $category = $service->findCategorys();
            $this->render('update',['model' => $model,'category'=>$category]);
            $this->clearMsg();
        } else {
            $this->redirect(Yii::app()->createUrl('index'));
        }
    }

    /**
     * 聯絡資訊刪除
     */
    public function actionDelete()
    {
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? $this->doPostDelete() : $this->redirect(['index']);
    }

    private function doPostDelete()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $id = filter_input(INPUT_POST, 'id');

        $model = Specialcase::model()->findByPk($id);

        if ($model !== null) {
            $model -> delete();
            $this->redirect(['index']);
        }
    }

}