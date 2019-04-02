<?php
class CalculationfeeController extends Controller
{
    public $layout = "//layouts/back_end";

//    protected function beforeAction($action)
//    {
//        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
//    }

    public function actionIndex()
    {
        $service = new CalculationfeeService();
        $model = $service->findCalculationfee();

        $service = new DeviceService();
        $device = $service->findDevices();//取出所有儀器ID

        $service = new UsergrpService();
        $level_one_all = $service->getLevelOneAll();//取出所有第一層單位ID

        $service = new AccountService();
        $account = $service->findAccounts();//取出系統管理員帳號

        $this->render('index',['model'=>$model,'device'=>$device,'level_one_all'=>$level_one_all,'account'=>$account]);
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
        $inputs["device"][] =$_POST["device"];
        $inputs["level_one"][] = $_POST["level_one"];

        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $service = new CalculationfeeService();
        $model = $service->create($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('create');
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect(Yii::app()->createUrl('calculationfee/index'));
        }
    }

    private function doGetCreate()
    {

        $service = new DeviceService();
        $device = $service->findDevices();//取出所有儀器ID

        $service = new UsergrpService();
        $level_one_all = $service->getLevelOneAll();//取出所有第一層單位ID

        $this->render('create',['level_one_all'=>$level_one_all,'device'=>$device,'device'=>$device]);
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
        $inputs["device_id"] = filter_input(INPUT_POST, "device_id");
        $inputs["level_one_id"] = filter_input(INPUT_POST, "level_one_id");
        $inputs["base_minute"] = filter_input(INPUT_POST, "base_minute");
        $inputs["base_charge"] = filter_input(INPUT_POST, "base_charge");
        $inputs["start_base_charge"] = filter_input(INPUT_POST, "start_base_charge");
        $inputs["max_use_base"] = filter_input(INPUT_POST, "max_use_base");
        $inputs["unused_base"] = filter_input(INPUT_POST, "unused_base");

        $service = new CalculationfeeService();
        $model = $service->updateCalculationfee($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '修改成功';
        }

        $this->redirect('update/'.$inputs['id']);
    }

    private function doGetUpdate($id)
    {
        $model = Calculationfee::model()->findByPk($id);

        if ($model !== null) {

            $service = new DeviceService();
            $device = $service->findDevices();//取出所有儀器ID

            $service = new UsergrpService();
            $level_one_all = $service->getLevelOneAll();//取出所有第一層單位ID

            $service = new AccountService();
            $account = $service->findAccounts();//取出系統管理員帳號

            $this->render('update',['model' => $model,'device'=>$device,'level_one_all'=>$level_one_all,'account'=>$account]);
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

        $model = Calculationfee::model()->findByPk($id);

        if ($model !== null) {
            $model -> delete();
            $this->redirect(['index']);
        }
    }

}