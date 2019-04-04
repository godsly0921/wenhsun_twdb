<?php
/**
 * @author Neil Kuo
 * @copyright Neil Kuo
 * @since 2015-07-01
 * @return 網站後台-系統資料控制與傳遞
 */
class SystemController extends Controller
{
    public $layout = "//layouts/back_end";

	public function actionIndex()
    {
        $this->clearMsg();

        $systemService = new SystemService();
        $systems = $systemService->findSystems();

        $data = ["systems" => $systems];

        $this->render('index', $data);
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
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs["system_number"] = filter_input(INPUT_POST, 'system_number');
        $inputs["system_name"] = filter_input(INPUT_POST, 'system_name');
        $inputs["system_controller"] = filter_input(INPUT_POST, 'system_controller');
        $inputs["system_type"] = filter_input(INPUT_POST, 'system_type');
        $inputs["system_range"] = filter_input(INPUT_POST, 'system_range');

        $systemService = new SystemService();
        $systemModel = $systemService->create($inputs);

        if ($systemModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $systemModel->getErrors();
            $this->redirect('create');
            return;
        }

        $this -> redirect('index');
    }

    private function doGetCreate()
    {
        $this->render('create');
        $this->clearMsg();
    }

	public function actionDelete()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $this->doPostDelete();
        } else {
            $this->redirect(['index']);
        }
	}

    private function doPostDelete()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect(['index']);

        $system_id = filter_input(INPUT_POST, 'system_id');

        if ($system_id !== '' && $system_id !== null) {
            $system = System::model()->findByPk($system_id);

            if ($system !== null) {
                $system->delete();
                $this->redirect(['index']);
            }

        } else {
            $this->redirect(['index']);
        }
    }

    /**
     * @param $id
     */
	public function actionUpdate($id = null)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $this->doPostUpdate();
        } else {
            $this->doGetUpdate($id);
        }
	}

    /**
     * @param $id
     */
    private function doGetUpdate($id)
    {
        $systems = System::model()->findByPk($id);
        if ($systems !== null) {
            $this->render('update', array('systems' => $systems));
            $this->clearMsg();
        } else {
            $this->redirect(['index']);
        }
    }

    private function doPostUpdate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs["system_id"] = filter_input(INPUT_POST, 'system_id');
        $inputs["system_number"] = filter_input(INPUT_POST, 'system_number');
        $inputs["system_name"] = filter_input(INPUT_POST, 'system_name');
        $inputs["system_controller"] = filter_input(INPUT_POST, 'system_controller');
        $inputs["system_type"] = filter_input(INPUT_POST, 'system_type');
        $inputs["system_range"] = filter_input(INPUT_POST, 'system_range');

        $systemService = new SystemService();
        $systemModel = $systemService->update($inputs);

        if ($systemModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $systemModel->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '系統更新成功';
        }

        $this->redirect('update/'.$inputs['system_id']);
    }

    protected function needLogin(): bool
    {
        return true;
    }
}

