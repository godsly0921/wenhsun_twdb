<?php
/**
 * @author Neil Kuo
 * @copyright Neil Kuo
 * @since 2015-07-01
 * @return 網站後台-系統功能資料控制與傳遞
 */
class PowerController extends Controller
{

	private $defaultPowerDisplay = ["1" => "顯示", "0" => "隱藏"];
    public $layout = "//layouts/back_end";

	public function actionIndex() {

        $this->clearMsg();
		
		$powerService = new PowerService();
        $powers = $powerService->findPower();
		$systems = ExtSystem::model()->system_list();
		
		$this -> render('index', ['powers' => $powers,'systems' => $systems]);
	}

	public function actionCreate() 
	{
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === "POST") {
            $this->doPostCreate();
        } else {
            $this->doGetCreate();
        }
	}

    private function doPostCreate()
    {
        if (!CsrfProtector::comparePost()) {
            $this->redirect('index');
        }

        $inputs = [];
        // $inputs["power_number"] = filter_input(INPUT_POST, "power_number");
        $inputs["power_name"] = filter_input(INPUT_POST, "power_name");
        $inputs["power_controller"] = filter_input(INPUT_POST, "power_controller");
        $inputs["power_master_number"] = filter_input(INPUT_POST, "power_master_number");
        $inputs["power_range"] = filter_input(INPUT_POST, "power_range");
        $inputs["power_display"] = filter_input(INPUT_POST, "power_display");
        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $powerService = new PowerService();
        $powerModel = $powerService->create($inputs);

        if ($powerModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $powerModel->getErrors();
            $this->redirect('create');
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect('index');
        }
    }

    private function doGetCreate()
    {
        CsrfProtector::putToken();

        $powers = ExtPower::model()->power_list();
        $systems = ExtSystem::model()->system_list();
        $service = new PowerService();

        $data = ['powers'=>$powers,'systems'=>$systems,'power_display'=>$this->defaultPowerDisplay];

        $this->render('create', $data);
        $this->clearMsg();
    }


    /**
     * @param $id
     *
     * 權限功能刪除
     */
	public function actionDelete($id)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $this->doPostDelete($id);
        } else {
            $this->redirect(['index']);
        }
	}

    private function doPostDelete($id)
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $powers = Power::model()->findByPk($id);

        if ($powers !== null) {
            $powers -> delete();
            $this->redirect(['index']);
        }
    }

    /**
     * @param $id
     *
     * 權限功能更新
     */
	public function actionUpdate($id = null) {

        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === "POST") {

            $this->doPostUpdate();
        } else {
            $this->doGetUpdate($id);
        }
	}

    private function doPostUpdate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs = [];
        $inputs['id'] = filter_input(INPUT_POST, "power_id");
        $inputs["power_number"] = filter_input(INPUT_POST, "power_number");
        $inputs["power_name"] = filter_input(INPUT_POST, "power_name");
        $inputs["power_controller"] = filter_input(INPUT_POST, "power_controller");
        $inputs["power_master_number"] = filter_input(INPUT_POST, "power_master_number");
        $inputs["power_range"] = filter_input(INPUT_POST, "power_range");
        $inputs["power_display"] = filter_input(INPUT_POST, "power_display");

        $powerService = new PowerService();
        $powerModel = $powerService->update($inputs);

        if ($powerModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $powerModel->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '功能修改成功';
        }

        $this->redirect('update/'.$inputs['id']);
    }

    private function doGetUpdate($id)
    {
        $powers = ExtPower::model()->findByPk($id);
        $systems = ExtSystem::system_list();

        if ($powers != null) {
            $this -> render('update', array('powers' => $powers ,'systems' => $systems));
            $this->clearMsg();
        } else {
            $this->redirect(['index']);
        }
    }


    protected function needLogin(): bool
    {
        return true;
    }
}
