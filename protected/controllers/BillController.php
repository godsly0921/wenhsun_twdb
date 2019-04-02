<?php
class BillController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function beforeAction($action)
    {
        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
    }

    public function actionDevice(){

        $billser = new BillService;
        $data    = $billser->getall();

        $this->render('list', array('datas'=>$data));

    }
}