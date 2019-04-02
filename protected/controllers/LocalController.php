<?php
class LocalController extends Controller
{
    public $layout = "//layouts/back_end";

//    protected function beforeAction($action)
//    {
//        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
//    }

    public function actionIndex()
    {
        $service = new LocalService();
        $model = $service->findLocal();

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
        $inputs["name"] = filter_input(INPUT_POST, "name");
        $inputs["status"] = filter_input(INPUT_POST, "status");
        $inputs["create_date"] = filter_input(INPUT_POST, "create_date");
        $inputs["edit_date"] = filter_input(INPUT_POST, "edit_date");

        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $modelService = new LocalService();
        $modelModel = $modelService->create($inputs);

        if ($modelModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $modelModel->getErrors();
            $this->redirect('create');
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect(Yii::app()->createUrl('local/index'));
        }
    }

    private function doGetCreate()
    {
        CsrfProtector::putToken();
        $service = new LocalService();
        $local = $service->DoCreateLocal();
        $this->render('create',["local"=>$local]);
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
        $inputs["name"] = filter_input(INPUT_POST, "name");
        $inputs["status"] = filter_input(INPUT_POST, "status");
        $inputs["create_date"] = filter_input(INPUT_POST, "create_date");
        $inputs["edit_date"] = filter_input(INPUT_POST, "edit_date");

        $service = new LocalService();
        $model = $service->updateLocal($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '修改成功';
        }
        $this->redirect(Yii::app()->createUrl('local/index'));
        /*$this->redirect('update/'.$inputs['id']);*/
    }

    private function doGetUpdate($id)
    {
        $model = Local::model()->findByPk($id);

        if ($model !== null) {
            $this->render('update',['model' => $model]);
            $this->clearMsg();
        } else {
            $this->redirect(Yii::app()->createUrl('local/index'));
        }
    }

    /**
     * 聯絡資訊刪除
     */
    public function actionDelete()
    {
        if( Yii::app()->request->isPostRequest ){

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }
      
      if(  empty( trim($_POST['id']) ) || !isset($_POST['id']) ){

        $this->redirect(Yii::app()->createUrl('local/index'));
      
      }

      $tmpser = new localService();
      $delres = $tmpser -> del( $_POST['id'] );

      $this->redirect(Yii::app()->createUrl('local/index'));

    }else{

      $this->redirect(Yii::app()->createUrl('local/index'));

    }
    }

    private function doPostDelete()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $id = filter_input(INPUT_POST, 'id');
        $service = new LocalService();
        $model = $service->del($id);
        /*$model = Local::model()->findByPk($id);*/

        if ($model !== null) {
            $model -> delete();
            $this->redirect(['index']);
        }
    }

}