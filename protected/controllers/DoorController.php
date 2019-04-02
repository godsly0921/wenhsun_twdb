<?php
class DoorController extends Controller
{
    public $layout = "//layouts/back_end";

//    protected function beforeAction($action)
//    {
//        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/list'));
//    }

    public function actionList()
    {
        $service = new DoorService();
        $model = $service->findDoor();

        $service = new LocalService();
        $result = $service->findLocal();
        $local = '';
        foreach($result as $key=>$value){
            $local[$value->id] = $value;
        }

        $service = new AccountService();
        $result = $service->findAccounts();
        $accounts = '';
        foreach($result as $key=>$value){
            $accounts[$value->id] = $value;
        }

        $this->render('list',['model'=>$model,'local'=>$local,'accounts'=>$accounts]);
    }



    public function actionCreate()
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostCreate() : $this->doGetCreate();
    }

    private function doPostCreate()
    {
        if (!CsrfProtector::comparePost()) {
            $this->redirect('list');
        }

        $inputs = [];
        $inputs["name"] = filter_input(INPUT_POST, "name");
        $inputs["en_name"] = filter_input(INPUT_POST, "en_name");
        $inputs["position"] = filter_input(INPUT_POST, "position");
        $inputs["status"] = filter_input(INPUT_POST, "status");
        $inputs["station"] = filter_input(INPUT_POST, "station");
        $inputs["price"] = filter_input(INPUT_POST, "price");

        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $modelService = new DoorService();
        $modelModel = $modelService->create($inputs);

        $localModel=  new LocalService();
        $result = $localModel->findLocal();
        $local = '';
        foreach($result as $key=>$value){
            $local[$value->id] = $value;
        }

        if ($modelModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $modelModel->getErrors();
            $this->redirect('create',['local'=>$local]);
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect(Yii::app()->createUrl('door/list'));
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
            $this->redirect('list');

        $inputs = [];


        $inputs["id"] = filter_input(INPUT_POST, "id");
        $inputs["name"] = filter_input(INPUT_POST, "name");
        $inputs["en_name"] = filter_input(INPUT_POST, "en_name");
        $inputs["position"] = filter_input(INPUT_POST, "position");
        $inputs["status"] = filter_input(INPUT_POST, "status");
        $inputs["station"] = filter_input(INPUT_POST, "station");
        $inputs["ip"] = filter_input(INPUT_POST, "ip");
        $inputs["price"] = filter_input(INPUT_POST, "price");

        $service = new DoorService();
        $model = $service->updateDoor($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '修改成功';
        }



        $this->redirect('update/'.$inputs['id']);
    }

    private function doGetUpdate($id)
    {
        $model = Door::model()->findByPk($id);

        $service = new LocalService();
        $result = $service->findLocal();
        $local = '';
        foreach($result as $key=>$value){
            $local[$value->id] = $value;
        }

        $service = new AccountService();
        $result = $service->findAccounts();
        $accounts = '';
        foreach($result as $key=>$value){
            $accounts[$value->id] = $value;
        }

        if ($model !== null) {
            $this->render('update',['model' => $model,'local'=>$local,'accounts'=>$accounts]);
            $this->clearMsg();
        } else {
            $this->redirect(Yii::app()->createUrl('list'));
        }
    }

    /**
     * 聯絡資訊刪除
     */
    public function actionDelete()
    {
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? $this->doPostDelete() : $this->redirect(['list']);
    }

    private function doPostDelete()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('list');

        $id = filter_input(INPUT_POST, 'id');

        $model = Door::model()->findByPk($id);

        if ($model !== null) {
            $model -> delete();
            $this->redirect(['list']);
        }
    }

}