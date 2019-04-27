<?php
class AttendanceController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function needLogin(): bool
    {
        return true;
    }

    public function actionList()
    {
        $service = new AttendanceService();
        $model = $service->findAttendance();

        $this->render('list',['model'=>$model]);
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
        $inputs["day"] = filter_input(INPUT_POST, "day");
        $inputs["type"] = filter_input(INPUT_POST, "type");
        $inputs["description"] = filter_input(INPUT_POST, "description");

        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $modelService = new AttendanceService();
        $modelModel = $modelService->create($inputs);

        if ($modelModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $modelModel->getErrors();
            $this->redirect('create');
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect(Yii::app()->createUrl('attendance/list'));
        }
    }

    private function doGetCreate()
    {
        CsrfProtector::putToken();
        $service = new AttendanceService();
        $attendance = $service->DoCreateAttendance();
        $this->render('create',["attendance"=>$attendance]);
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
        $inputs["day"] = filter_input(INPUT_POST, "day");
        $inputs["type"] = filter_input(INPUT_POST, "type");
        $inputs["description"] = filter_input(INPUT_POST, "description");

        $service = new AttendanceService();
        $model = $service->updateAttendance($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '修改成功';
        }
        $this->redirect(Yii::app()->createUrl('attendance/list'));
        /*$this->redirect('update/'.$inputs['id']);*/
    }

    private function doGetUpdate($id)
    {
        $model = Attendance::model()->findByPk($id);

        if ($model !== null) {
            $this->render('update',['model' => $model]);
            $this->clearMsg();
        } else {
            $this->redirect(Yii::app()->createUrl('attendance/list'));
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

        $this->redirect(Yii::app()->createUrl('attendance/list'));
      
      }

      $tmpser = new attendanceService();
      $delres = $tmpser -> del( $_POST['id'] );

      $this->redirect(Yii::app()->createUrl('attendance/list'));

    }else{

      $this->redirect(Yii::app()->createUrl('attendance/list'));

    }
    }

    private function doPostDelete()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('list');

        $id = filter_input(INPUT_POST, 'id');
        $service = new AttendanceService();
        $model = $service->del($id);
        /*$model = Attendance::model()->findByPk($id);*/

        if ($model !== null) {
            $model -> delete();
            $this->redirect(['list']);
        }
    }

}