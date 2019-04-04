<?php

class AccountController extends Controller
{
    private $defaultAccountType = ["0" => "啟用", "1" => "停權"];
    public $layout = "//layouts/back_end";

	public function actionIndex()
    {
        $this->clearMsg();

        $accountService = new AccountService();
        $accounts = $accountService->findAccounts();
        $groups = ExtGroup::model()->group_list();

        $this->render('index', ['accounts' => $accounts, 'groups' => $groups]);
	}

	public function actionCreate()
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostCreate() : $this->doGetCreate();
	}

    private function doPostCreate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs = [];
        $inputs["user_account"] = filter_input(INPUT_POST, "user_account");
        $inputs["password"] = filter_input(INPUT_POST, "password");
        $inputs["account_name"] = filter_input(INPUT_POST, "account_name");
        $inputs["account_group"] = filter_input(INPUT_POST, "account_group");
        $inputs["account_type"] = filter_input(INPUT_POST, "account_type");
        $inputs["password_confirm"] = filter_input(INPUT_POST, "password_confirm");

        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $accountService = new AccountService();
        $accountModel = $accountService->create($inputs);

        if ($accountModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $accountModel->getErrors();
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

        $groups = ExtGroup::model()->group_list();
        $powers = ExtPower::model()->power_list();

        $this->render('create', ['powers' => $powers, 'groups' => $groups, 'account_type' => $this->defaultAccountType]);
        $this->clearMsg();
    }

	public function actionDelete()
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostDelete() : $this->redirect(['index']);
	}

    private function doPostDelete()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        if (!CsrfProtector::comparePost()){
            $this->redirect(['index']);
        }



        $accid = filter_input(INPUT_POST, 'acc_id');

        $account = Account::model()->findByPk($accid);

        if ($account !== null) {
            $account->delete();
            $this->redirect(['index']);
        }
    }

    /**
     * account data update
     * @param $id
     */
	public function actionUpdate($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            //INFO, PW
            $updateType = filter_input(INPUT_POST, 'update_type');

            if ($updateType === 'PW') {
                $this->doPostUpdatePasswd();
            } else {
                $this->doPostUpdate();
            }

        } else {
            $this->doGetUpdate($id);
        }
	}

    private function doPostUpdate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs = [];
        $inputs['id'] = filter_input(INPUT_POST, "account_id");
        $inputs["user_account"] = filter_input(INPUT_POST, "user_account");
        $inputs["account_name"] = filter_input(INPUT_POST, "account_name");
        $inputs["account_group"] = filter_input(INPUT_POST, "account_group");
        $inputs["account_type"] = filter_input(INPUT_POST, "account_type");

        $accountService = new AccountService();
        $accountModel = $accountService->updateAccount($inputs);

        if ($accountModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $accountModel->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '帳號修改成功';
        }

        $this->redirect('update/'.$inputs['id']);
    }

    private function doPostUpdatePasswd()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs = [];
        $inputs["id"] = filter_input(INPUT_POST, "account_id");
        $inputs["password"] = filter_input(INPUT_POST, "password");
        $inputs["password_confirm"] = filter_input(INPUT_POST, "password_confirm");

        $accountService = new AccountService();
        $accountModel = $accountService->updateAccountPassword($inputs);

        if ($accountModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $accountModel->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '密碼修改成功';
        }

        $this->redirect('update/'.$inputs['id']);

    }

    private function doGetUpdate($id)
    {
        $groups = ExtGroup::model()->group_list();
        $powers = ExtPower::model()->power_list();
        $accounts = Account::model()->findByPk($id);

        $data = [
            'accounts' => $accounts,
            'powers' => $powers,
            'groups' => $groups,
            'account_type' => $this->defaultAccountType
        ];

        if ($accounts !== null) {
            $this->render('update', $data);
            $this->clearMsg();
        } else {
            $this->redirect(Yii::app()->createUrl('account'));
        }
    }

    protected function needLogin(): bool
    {
        return true;
    }
}