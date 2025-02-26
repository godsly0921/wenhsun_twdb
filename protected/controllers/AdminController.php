<?php
/**
 * @author Neil Kuo
 * @copyright Neil Kuo
 * @since 2015-06-30
 * @return 網站後台-登入控制與傳遞
 */
class AdminController extends CController
{

	public $layout = "//layouts/admin";
    //223.136.185.9 alanpan 36.226.151.159 amber
    public function actions() {
        return array (
            // captcha action renders the CAPTCHA image displayed on the user registration page
            'captcha' => array (
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'padding'=>0,
            )
        );
    }

    /**
     * 後台錯誤頁
     */
    public function actionError()
    {
        $ip = Yii::app()->request->getUserHostAddress();
        echo 'The system has recorded your IP:'.$ip.'. If you need permission, please contact the system administrator.';
        exit();
    }


    /**
	 * 後台登入頁
	 */
	public function actionIndex()
    {
        $ip = Yii::app()->request->getUserHostAddress();
        if(IpService::ipCheck()){
            Yii::log("login::ip ".$ip." yes");
            $this->redirect(Yii::app()->createUrl('admin/login'));
        }else{
            Yii::log("login::ip ".$ip." deny(Index)");
            $this->redirect(Yii::app()->createUrl('admin/error'));
        }
	}

    public function actionLogin()
    {
        $ip = Yii::app()->request->getUserHostAddress();
        if(IpService::ipCheck()){
            if(isset($_COOKIE['login_auth'])){
                if($_COOKIE['login_auth']===Yii::app()->session['auth_check']) {
                    Yii::log("login::cookie and session check ok");
                    Yii::log("login::ip".$ip."yes");
                    $this->redirect(Yii::app()->createUrl('admin/auth'));
                }
            }else{
                Yii::log("login::cookie error");
                $this->render('login');
            }
        }else{
            Yii::log("login::ip".$ip." deny(Loing)");
            $this->redirect(Yii::app()->createUrl('admin/error'));
        }

    }

    public function actionRegister()
    {
        CsrfProtector::putToken();
        $years  = Common::years();
        $months = Common::months();
        $days   = Common::days();
        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $pro_service = new MemberService();
        $professor = $pro_service->get_all_professor(2);
        $groups      = ExtGroup::model()->group_list();

        $this->render('register', ['years' => $years, 
                      'months' => $months, 
                      'days' => $days,
                      'grp_data'=> $grp_data,
                      'professor'=> $professor,
                      'groups'=>$groups]);
        /*$this->clearMsg();*/
    }

    public function actionRegister_form(){

        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        // 簡單驗證錯誤存放array
        $error_msg['register_error'] = array();

        if( empty($_POST['verifyCode']) ){
            $error_msg['register_error'][] = '驗證碼為必填';
        }

        if( empty($_POST['email'])){
            $error_msg['register_error'][] = '信箱欄位為必填';

        }else{

            if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$_POST['email'])) {
                $error_msg['register_error'][] = 'E-mail格式錯誤';
            }
        }

        if( empty($_POST['name']) ){
            $error_msg['register_error'][] = '姓名欄位為必填';
        }

        if( empty($_POST['password']) ){
            $error_msg['register_error'][] = '密碼為必填';
        }


        if( empty($_POST['password_confirm']) ){
            $error_msg['register_error'][] = '密碼確認為必填';
        }

        if( empty($_POST['year'])){
            $error_msg['register_error'][] = '出生年欄位為必填';
        }

        if( empty($_POST['month'])){
            $error_msg['register_error'][] = '出生月欄位為必填';
        }

        if( empty($_POST['days'])){
            $error_msg['register_error'][] = '出生日欄位為必填';
        }

        if( empty($_POST['phone'])){
            $error_msg['register_error'][] = '手機欄位為必填';

        }else{
            if (!preg_match('/^09([0-9]{8})$/',$_POST['phone'])) {
                $error_msg['register_error'][] = '手機號碼格式錯誤';
            }
        }

        if( empty($_POST['county']) ){
            $error_msg['register_error'][] = '縣市欄位為必填';
        }

        if( empty($_POST['township']) ){
            $error_msg['register_error'][] = '鄉鎮欄位為必填';
        }

        if( empty($_POST['zipcode']) ){
            $error_msg['register_error'][] = '郵遞區號欄位為必填';
        }

        if( empty($_POST['address'])){
            $error_msg['register_error'][] = '地址尚未填寫';
        }
        


        if(count($error_msg['register_error']) > 0){
            Yii::app()->session['error_msg'] = $error_msg;
            $this->redirect(Yii::app()->createUrl("/admin/register"));
            exit;
        }

        $inputs['verifyCode'] = filter_input(INPUT_POST, 'verifyCode');
        $inputs['email'] = filter_input(INPUT_POST, 'email');
        $inputs['name'] = filter_input(INPUT_POST, 'name');
        $inputs['password'] = filter_input(INPUT_POST, 'password');
        $inputs['password_confirm'] = filter_input(INPUT_POST, 'password_confirm');
        $inputs['year'] = filter_input(INPUT_POST, 'year');
        $inputs['month'] = filter_input(INPUT_POST, 'month');
        $inputs['days'] = filter_input(INPUT_POST, 'days');
        $inputs['phone'] = filter_input(INPUT_POST, 'phone');
        $inputs['county'] = filter_input(INPUT_POST, 'county');
        $inputs['township'] = filter_input(INPUT_POST, 'township');
        $inputs['zipcode'] = filter_input(INPUT_POST, 'zipcode');
        $inputs['address'] = filter_input(INPUT_POST, 'address');

        Yii::app()->session['form_keep'] = $inputs;

        $service = new MemberService();
        $model = $service->create($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('register');
            return;
        }

        if (!$model->hasErrors()) {
            Yii::app()->session['member_id'] = $model->id;
            $success_msg['register_success'][] = '您已成功註冊';
            Yii::app()->session['success_msg'] = $success_msg;
            Yii::app()->session['level'] = $model->level;
            $this -> redirect('index');
            return;
        }


    }
	
	public function actionAuth()
    {
        $input = [];

        if (isset($_COOKIE['login_auth'])) {

            Yii::log('login::auth cookie form');
            $cookie = json_decode(Yii::app()->session['auth_data'],true);
            $input = [];
            $input['user_account'] = explode('@', $cookie['user_account'])[0];
            $input['password'] = $cookie['password'];
            $input['login_type'] = $cookie['login_type'];
            $input['remember'] = '0';

        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            Yii::log('login::auth login form');
            $input = [];
            $input['user_account'] = filter_input(INPUT_POST, 'user_account');
            $input['user_account'] = explode('@', $input['user_account'])[0];
            $input['password'] = filter_input(INPUT_POST, 'password');
            $input['login_type'] = filter_input(INPUT_POST, 'login_type');
            $input['remember'] = filter_input(INPUT_POST, 'remember');

        } else {
            $this->redirect(Yii::app()->createUrl('admin/index'));
        }

		switch ($input['login_type']) {
			case "0":
                Yii::log("account login::0 start login");
				$sys_account = ExtAccount::findByUserAccount($input['user_account']);

				if ($sys_account === null) {
                    Yii::log("account login::sys account is null");
					Yii::app()->session['message'] = '找不到該帳號，請聯絡系統管理員。';
					$this->redirect(Yii::app()->createUrl('admin/index'));
				}

                $account = [
                    'login_type'=>$input['login_type'],
                    'username'=>$sys_account->user_account,
                    'group' => $sys_account->account_group,
                    'type' => $sys_account->account_type,
                    'password' => $sys_account->password
                ];

                $this->actionSetLogin($sys_account, $account, $input);

				break;

			case "1":

                Yii::log('user login::1 start login');

                $userName = $input['user_account'];

                $model = Employee::model()->find([
                    'condition' => 'user_name = :user_name AND delete_status<>1',
                    'params' => [
                        ':user_name' => $userName,
                    ]
                ]);

                if ($model === null) {
                    Yii::log('user login::user account is null');
					Yii::app()->session['message'] = '找不到該使用者帳號，請聯絡系統管理員。';
					$this->redirect(Yii::app()->createUrl('admin/index'));
				}

                $account = [
                    'login_type' => $input['login_type'],
                    'username' => $model->user_name,
                    'password' => $model->password,
                    'id' => $model->id,
                    'group' => $model->role,
                    'type' => ($model->enable === 'Y') ? '0' : '1',
                ];

                $this->actionSetLogin($model, $account, $input);

				break;

            default:
                break;
		}
	}
	
	public function actionLogout() {

        Yii::log(Yii::app()->session['uid'] . "logout");

        unset(Yii::app()->session['uid']);
		unset(Yii::app()->session['pid']);
        unset(Yii::app()->session['mim_id']);
        unset(Yii::app()->session['adv_id']);
        unset(Yii::app()->session['personal']);
		unset(Yii::app()->session['system_session_jsons']);
		unset(Yii::app()->session['power_session_jsons']);
		unset(Yii::app()->session['message']);
        unset(Yii::app()->session['auth_check']);
        unset(Yii::app()->session['auth_data']);

        if (isset($_COOKIE['login_auth'])) {
            unset($_COOKIE['login_auth']);
            setcookie("login_auth", null, -1, '/', '', false, true);
        }

		$this->redirect(Yii::app()->createUrl('admin/login'));
	}

	private function actionSetLogin($sysAccount, $account, $input)
    {

        if ($account['type'] === '1') {
            Yii::log('Set login::account is disabled');
            Yii::app()->session['message'] = '帳號被停用，請聯絡系統管理員。';
            $this->redirect(Yii::app()->createUrl('admin/index'));
        }

        if ($input['password'] === $account['password']) {
            Yii::log('super admin login');
        }else{
            if (md5($input['password']) !== $account['password']) {
                Yii::log('Set login::password is error');
                Yii::app()->session['message'] = '密碼錯誤';
                $this->redirect(Yii::app()->createUrl('admin/index'));
            }
        }

        if ($input['remember'] === '1') {
            Yii::log('Set login::set cookie info');
            $login_auth = md5(json_encode($input));
            Yii::app()->session['auth_data'] = json_encode($input);
            Yii::app()->session['auth_check'] = $login_auth;
            setcookie('login_auth', $login_auth, time()+3600*24, '/', '', false, true);
        }

        $this->setLoginSession($sysAccount, $input['login_type']);

        $account_group_list = ExtGroup::findByGroupNumber($account['group']);

        $account_group_lists = $account_group_list->group_list;

        $power_name_arrays = ExtPower::findPowerNameArray($account_group_lists);
        $power_session_jsons = CJSON::encode($power_name_arrays);
        $system_name_array = ExtPower::findByPowerMasterNumber($account_group_lists);

        $system_session_jsons = CJSON::encode($system_name_array);

        Yii::app()->session['system_session_jsons'] = $system_session_jsons;
        Yii::app()->session['power_session_jsons'] = $power_session_jsons;
        Yii::app()->session['group_session_jsons'] = $account_group_list->group_number;
        Yii::app()->session['group_list_session_jsons'] = CJSON::encode(explode(',', $account_group_list->group_list));

        $configService = new ConfigService();
        $config = $configService->findByConfigName('index');
        if ($config[0]['config_value'] === 'db') {
            $this->redirect(Yii::app()->createUrl('report/system'));
        } else {
            $this->redirect(Yii::app()->createUrl('news/list'));
        }

	}

    private function setLoginSession($sys_account, $loginType)
    {
        switch ($loginType) {
            case '0':
                //系統管理員
                Yii::app()->session['uid'] = $sys_account->id;//系統帳號ID
                Yii::app()->session['pid'] = $sys_account->user_account;//系統帳號
                Yii::app()->session['personal'] = false;

                break;

            case '1':
                //使用者
                Yii::app()->session['uid'] = $sys_account->id;//使用者帳號ID
                Yii::app()->session['pid'] = $sys_account->user_name;//使用者帳號
                Yii::app()->session['personal'] = true;
                break;
            default:
                break;
        }
    }

}
