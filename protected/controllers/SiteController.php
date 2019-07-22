<?php
class SiteController extends CController{
    // layout
    public $layout = "//layouts/front_end";
    private const PERPAGE = 5;
    protected function needLogin(): bool
    {
        return true;
    }
    //首頁site
    public function ActionIndex(){
        $websiteService = new WebsiteService();
        $banner_data = $websiteService->findAllBanner();
        $ad_data = $websiteService->findAllAdInfo();
        $this->render('index',array('banner_data'=>$banner_data,'ad_data'=>$ad_data));
    }

    public function ActionFindPhoto(){
        $limit = self::PERPAGE;
        $keyword = isset($_GET["keyword"])?$_GET["keyword"]:"";
        $page = isset($_GET["page"])?$_GET["page"]:"";
        $category_id = isset($_GET["category_id"]) && $_GET["category_id"] != ''?explode(",",$_GET["category_id"]):"";
        $filming_date = isset($_GET["filming_date"]) && $_GET["filming_date"] != ''?$_GET["filming_date"]:"";
        $object_name = isset($_GET["object_name"]) && $_GET["object_name"] != ''?explode(",",$_GET["object_name"]):"";
        $single_id = isset($_GET["single_id"]) && $_GET["single_id"] != ''?explode(",",$_GET["single_id"]):"";
        $siteService = new SiteService();
        $result = $siteService->findPhoto($single_id, $keyword, $category_id, $filming_date, $object_name ,$page, $limit);
        echo json_encode($result);
        exit();
    }

    public function ActionSearch(){
        $limit = self::PERPAGE;
        $keyword = isset($_GET["keyword"])?$_GET["keyword"]:"";
        $page = isset($_GET["page"])?$_GET["page"]:"";
        $category_id = isset($_GET["category_id"])?explode(",",$_GET["category_id"]):"";
        $filming_date = isset($_GET["filming_date"])?$_GET["filming_date"]:"";
        $object_name = isset($_GET["object_name"])?explode(",",$_GET["object_name"]):"";
        $single_id = isset($_GET["single_id"]) && $_GET["single_id"] != ''?explode(",",$_GET["single_id"]):"";
        $siteService = new SiteService();
        $siteService = new SiteService();
        $category_service = new CategoryService();
        $filming_date_range = $siteService->findPhotoFilmingRange();
        $distinct_object_name = $siteService->findPhotoObjectname();        
        $category_data = $category_service->findCategoryMate();
        $total_result = $siteService->findPhotoCount($single_id, $keyword, $category_id, $filming_date, $object_name);
        $total_result = ceil($total_result / $limit );
        $this->render('search',array( 'total_result' => $total_result, 'filming_date_range' => $filming_date_range, 'distinct_object_name' => $distinct_object_name, 'category_data' => $category_data ));
    }

    public function ActionImageInfo($id){
        $siteService = new SiteService();
        $photographService = new PhotographService();
        $category_service = new CategoryService();
        $photograph_data = $photographService->findSingleAndSinglesize($id); 
        $category_data = $category_service->findCategoryMate();
        $photograph_data['photograph_info']['keyword'] = explode(",", $photograph_data['photograph_info']['keyword']);
        $same_category = $siteService->findSameCategory($photograph_data['photograph_info']['category_id'],$id);

        $this->render('image_info',array('photograph_data'=>$photograph_data,'category_service'=>$category_service,'same_category'=>$same_category));
    }

    public function ActionAbout(){
        $aboutService = new AboutService();
        $about = $aboutService->getAllAbout();
        $this->render('about', array('about' => $about));
    }
    public function ActionPiccolumn(){
        $websiteService = new WebsiteService();
        $banner_data = $websiteService->findAllBanner();
        $piccolumn_date = $websiteService->findAllPicColumn();
        //$recommend_single_id_data = $websiteService->findrecommend_single_id($piccolumn_date->recommend_single_id);
        $this->render('piccolumn',['piccolumn_date'=>$piccolumn_date,'banner_data'=>$banner_data]);
    }
    public function ActionPiccolumnInfo($id){
        $websiteService = new WebsiteService();
        $banner_data = $websiteService->findAllBanner();
        $piccolumn_data = $websiteService->findPiccolumnById($id);
        $recommend_single_id_data = $websiteService->findrecommend_single_id($piccolumn_data->recommend_single_id);
        $this->render('piccolumn_info',['piccolumn_data'=>$piccolumn_data,'banner_data'=>$banner_data,'recommend_single_id_data'=>$recommend_single_id_data]);
    }

    public function ActionNews() {
        $pageCount = isset($_GET['pageCount']) ? filter_input(INPUT_GET, 'pageCount') : 1;
        if (!is_numeric($pageCount)) {
            $pageCount = 1;
        }
        $activityNewsService = new ActivityNewsService();
        $newsAll = $activityNewsService->getAllAcitiveNews();
        $count = count($newsAll);
        $page = ceil($count / 10);
        $news = $activityNewsService->findAllByPaging($pageCount);
        $this->render('news', array('news' => $news, 'page' => $page, 'pageCount' => $pageCount));
    }

    public function ActionNews_detail($id) {
        $activityNewsService = new ActivityNewsService();
        $news = $activityNewsService->findById($id);
        $this->render('news_detail', array('news' => $news));
    }

    public function ActionLogin(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->doLogin();
        }else{
            $fb = new Facebook\Facebook([
                'app_id' => FB_APP_ID, // 把 {app_id} 換成你的應用程式編號
                'app_secret' => FB_APP_SECRET, // 把 {app_secret} 換成你的應用程式密鑰
                'default_graph_version' => FB_GRAPH_VERSION,
            ]);              
            $helper = $fb->getRedirectLoginHelper();              
            $permissions = ['email'];
            $fb_loginurl = $helper->getLoginUrl(DOMAIN.'site/fblogin', $permissions);
            $this->render('login',['fb_loginurl'=>$fb_loginurl]);
        }  
    }
    public function doLogin(){
        $input['account'] = filter_input(INPUT_POST, 'account');
        $input['password'] = filter_input(INPUT_POST, 'password');
        $useridentity = new UserIdentity($input['account'],$input['password']);
        $is_login = $useridentity->authenticate(1);
        if (!$is_login) {
            $this->redirect(Yii::app()->createUrl('site/login'));
        }else{
            $duration = 3600 * 24 * 30; // 30 days
            Yii::app()->user->login($useridentity, $duration);
            $this->redirect(Yii::app()->createUrl('site'));
        }
    }
    public function actionLogout() {   
        Yii::app ()->user->logout ();
        unset(Yii::app()->session['uid']);
        unset(Yii::app()->session['pid']);
        unset(Yii::app()->session['name']);
        $this->redirect(Yii::app()->createUrl('site'));
    }

    public function Actionfblogin(){
        $fb = new Facebook\Facebook([
            'app_id' => FB_APP_ID, // 把 {app_id} 換成你的應用程式編號
            'app_secret' => FB_APP_SECRET, // 把 {app_secret} 換成你的應用程式密鑰
            'default_graph_version' => FB_GRAPH_VERSION,
        ]);              
        $helper = $fb->getRedirectLoginHelper();
        if (isset($_GET['state'])) { 
            $helper->getPersistentDataHandler()->set('state', $_GET['state']); 
        }
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }
        // Logged in
        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();
          
        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);          
        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId(FB_APP_ID); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();
          
        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }
        }
        $fb->setDefaultAccessToken($accessToken);
        $response = $fb->get('/me?locale=en_US&fields=id,name,email');
        $userNode = $response->getGraphUser();
        $inputs = array();
        $inputs['email'] = $userNode->getField('email');
        $inputs['fb_user_id'] = $userNode->getField('id');
        $inputs['name'] = $userNode->getField('name');
        $memberService = new MemberService();
        $member = $memberService->findByAccount($inputs['email']);

        if(!$member){//新的帳號
            $model = $memberService->fb_account_create($inputs);
        }else{//舊有帳號綁定google帳號
            if($member[0]->fb_user_id ==""){
                $model = $memberService->google_account_update($member[0],$inputs);
            }else{
                $model = $member[0];
            }
        }
        if($model){
            $useridentity = new UserIdentity($model->account,"");
            $is_login = $useridentity->authenticate();
            Yii::app()->session['uid'] = $model->id;//會員帳號ID
            Yii::app()->session['pid'] = $model->account;//會員帳號
            Yii::app()->session['name'] = $model->name;//會員名稱
            $duration = 3600 * 24 * 30; // 30 days
            Yii::app()->user->login($useridentity, $duration);
            $this->redirect(Yii::app()->createUrl('site'));
        }else{
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
    }

    public function ActionGoogleLogin(){
        // 0) 設定 client 端的 id, secret
        $client = new Google_Client;
        $client->setClientId(GOOGLE_CLINT_ID);
        $client->setClientSecret(GOOGLE_CLINT_SECRET);
         
        // 2) 使用者認證後，可取得 access_token 
        if (isset($_GET['code'])){
            $client->setRedirectUri("https://web.taiwanblacktea.com.tw/site/googlelogin");
            $result = $client->authenticate($_GET['code']);
         
            if (isset($result['error'])) 
            {
                die($result['error_description']);
            }
            Yii::app()->session['google'] = $result;
            header("Location:https://web.taiwanblacktea.com.tw/site/googlelogin?action=profile");
        }         
        // 3) 使用 id_token 取得使用者資料。另有 setAccessToken()、getAccessToken() 可以設定與取得 token
        elseif (isset($_GET['action']) && $_GET['action'] == "profile"){
            $profile = $client->verifyIdToken(Yii::app()->session['google']['id_token']);
            $memberService = new MemberService();
            $member = $memberService->findByAccount($profile['email']);
            if(!$member){//新的帳號
                $model = $memberService->google_account_create($profile);
            }else{//舊有帳號綁定google帳號
                if($member[0]->google_sub ==""){
                    $model = $memberService->google_account_update($member[0],$profile);
                }else{
                    $model = $member[0];
                }
            }
            if($model){
                $useridentity = new UserIdentity($model->account,"");
                $is_login = $useridentity->authenticate();
                Yii::app()->session['uid'] = $model->id;//會員帳號ID
                Yii::app()->session['pid'] = $model->account;//會員帳號
                Yii::app()->session['name'] = $model->name;//會員名稱
                $duration = 3600 * 24 * 30; // 30 days
                Yii::app()->user->login($useridentity, $duration);
                $this->redirect(Yii::app()->createUrl('site'));
            }else{
                $this->redirect(Yii::app()->createUrl('site/login'));
            }
        }
        // 1) 前往 Google 登入網址，請求用戶授權
        else 
        {
            $client->revokeToken();       
            // 添加授權範圍，參考 https://developers.google.com/identity/protocols/googlescopes
            $client->addScope(['https://www.googleapis.com/auth/userinfo.profile','https://www.googleapis.com/auth/userinfo.email']);
            $client->setRedirectUri("https://web.taiwanblacktea.com.tw/site/googlelogin");
            $url = $client->createAuthUrl();
            header("Location:{$url}");
        }
    }
    public function ActionVerification(){
        $verification_code = isset($_GET['verification_code'])?$_GET['verification_code']:"";
        if($verification_code){
            $memberService = new MemberService();
            $verification = $memberService->findByVerificationCode($verification_code); 
            //var_dump($verification);exit();
            if ($verification->hasErrors()) {
                Yii::app()->session['error_msg'] = '帳號驗證失敗';
                $this->redirect(Yii::app()->createUrl('site/register'));
            } else {
                Yii::app()->session['success_msg'] = '帳號驗證成功';
                $this->redirect(Yii::app()->createUrl('site/login'));
            }
        }else{
            Yii::app()->session['error_msg'] = "帳號驗證失敗";
            $this->redirect(Yii::app()->createUrl('site/register'));
        }
    }
    public function ActionRegister() {
        if(Yii::app()->request->isPostRequest) {
            $this->doPostRegister();
        }else{
            $this->doGetRegister();
        }
    }
    public function doPostRegister(){
        $memberService = new MemberService();
        $member = $memberService->findByAccount(filter_input(INPUT_POST, 'account'));
        if(!$member){//新的帳號
            $inputs['account'] = filter_input(INPUT_POST, 'account');
            $inputs['password'] = filter_input(INPUT_POST, 'password');
            $inputs['password_confirm'] = filter_input(INPUT_POST, 'password_confirm');
            $inputs['name'] = filter_input(INPUT_POST, 'name');
            $inputs['gender'] = filter_input(INPUT_POST, 'gender');
            $inputs['birthday'] = filter_input(INPUT_POST, 'birthday');
            $inputs['phone'] = filter_input(INPUT_POST, 'phone');
            $inputs['mobile'] = filter_input(INPUT_POST, 'mobile');
            $inputs['member_type'] = 1;
            $inputs['account_type'] = 2;
            $inputs['active'] = "N";
            $inputs['verification_code'] = substr(md5(uniqid(rand(), true)),0,20);
            $inputs['nationality'] = filter_input(INPUT_POST, 'nationality');
            $inputs['county'] = filter_input(INPUT_POST, 'county');
            $inputs['town'] = filter_input(INPUT_POST, 'town');
            $inputs['address'] = filter_input(INPUT_POST, 'address');
            $service = new MemberService();
            $model = $service->create($inputs);
            if ($model->hasErrors()) {
                foreach ($model->getErrors() as $error){
                    Yii::log(date("Y-m-d H:i:s")."account=>".$inputs['account'].", register error：".$error[0],  CLogger::LEVEL_INFO);
                }
                Yii::app()->session['error_msg'] = '註冊失敗';
                $this->render('register', ['data' => $inputs]);
                return;
            } else {
                Yii::app()->session['success'] = '註冊成功';
                $mail = new MailService();
                $mail_type = $mail->sendRegisterMail($inputs);
                if($mail_type){
                    Yii::log(date("Y-m-d H:i:s").'Register sendRegisterMail success account => '.$inputs['account'], CLogger::LEVEL_INFO);
                }else{
                    Yii::log(date("Y-m-d H:i:s").'Register sendRegisterMail error account => '.$inputs['account'],  CLogger::LEVEL_INFO);
                }
                $this->redirect(Yii::app()->createUrl('site/login'));
            }
        }
    }
    public function doGetRegister(){
        $inputs = array();
        $inputs['account'] = '';
        $inputs['name'] = '';
        $inputs['gender'] = '';
        $inputs['birthday'] = '';
        $inputs['phone'] = '';
        $inputs['mobile'] = '';
        $inputs['nationality'] = 'TW';
        $inputs['county'] = '';
        $inputs['town'] = '';
        $inputs['address'] = '';
        $this->render('register', ['data' => $inputs]);
    }

    public function Actionforgetverification(){
        $verification_code = isset($_GET['verification_code'])?$_GET['verification_code']:"";
        if($verification_code){
            $memberService = new MemberService();
            $verification = $memberService->findByForgetVerificationCode($verification_code); 
            //var_dump($verification);exit();
            if ($verification->hasErrors()) {
                Yii::app()->session['error_msg'] = '帳號驗證失敗';
                $this->redirect(Yii::app()->createUrl('site/forget'));
            } else {
                Yii::app()->session['success_msg'] = '帳號驗證成功';
                $this->redirect(Yii::app()->createUrl('site/login'));
            }
        }else{
            Yii::app()->session['error_msg'] = "帳號驗證失敗";
            $this->redirect(Yii::app()->createUrl('site/forget'));
        }
    }
    public function ActionForget() {
        if(Yii::app()->request->isPostRequest) {
            $this->doPostForget();
        }else{
            $this->doGetForget();
        }        
    }
    public function doGetForget(){
        $this->render('forget');
    }
    public function doPostForget(){
        $inputs['account'] = filter_input(INPUT_POST, 'account');
        $inputs['email'] = filter_input(INPUT_POST, 'email');
        $inputs['verification_code'] = substr(md5(uniqid(rand(), true)),0,20);
        $memberService = new MemberService();
        $forgetVerification = $memberService->forgetVerification($inputs);

        if (!$forgetVerification->hasErrors() && $forgetVerification){
            $mail = new MailService();
            $mail_type = $mail->sendForgetPwdMail($inputs);
            if($mail_type){
                Yii::log(date("Y-m-d H:i:s").'forget sendForgetPwdMail success account => '.$inputs['account'], CLogger::LEVEL_INFO);
            }else{
                Yii::log(date("Y-m-d H:i:s").'forget sendForgetPwdMail error account => '.$inputs['account'],  CLogger::LEVEL_INFO);
            }
            $this->redirect(Yii::app()->createUrl('site/login'));
        }else{
            Yii::app()->session['error_msg'] = "帳號與 Email 不存在";
            $this->redirect(Yii::app()->createUrl('site/forget'));
        }
    }
    public function ActionMy_account() {
        if(Yii::app()->request->isPostRequest) {
            $this->doPostMyaccount();
        }else{
            $this->doGetMyaccount();
        }        
    }

    public function doPostMyaccount(){
        $inputs['id'] = Yii::app()->session['uid'];
        $inputs['account'] = filter_input(INPUT_POST, 'account');
        $inputs['password'] = filter_input(INPUT_POST, 'password');
        $inputs['password_confirm'] = filter_input(INPUT_POST, 'password_confirm');
        $inputs['name'] = filter_input(INPUT_POST, 'name');
        $inputs['gender'] = filter_input(INPUT_POST, 'gender');
        $inputs['birthday'] = filter_input(INPUT_POST, 'birthday');
        $inputs['phone'] = filter_input(INPUT_POST, 'phone');
        $inputs['mobile'] = filter_input(INPUT_POST, 'mobile');
        $inputs['member_type'] = 2;
        $inputs['account_type'] = 1;
        $inputs['active'] = "N";
        $inputs['verification_code'] = substr(md5(uniqid(rand(), true)),0,20);
        $inputs['nationality'] = filter_input(INPUT_POST, 'nationality');
        $inputs['county'] = filter_input(INPUT_POST, 'county');
        $inputs['town'] = filter_input(INPUT_POST, 'town');
        $inputs['address'] = filter_input(INPUT_POST, 'address');
        $service = new MemberService();
        $model = $service->update($inputs);
        if ($model->hasErrors()) {
            foreach ($model->getErrors() as $error){
                Yii::log(date("Y-m-d H:i:s")."account=>".$inputs['account'].", register error：".$error[0],  CLogger::LEVEL_INFO);
            }
            Yii::app()->session['error_msg'] = '修改失敗';
            $this->redirect(Yii::app()->createUrl('site/my_account',['data' => $inputs]));
        } else {
            Yii::app()->session['success'] = '修改成功';
            $this->redirect(Yii::app()->createUrl('site/my_account',['data' => $inputs]));
        }
    }

    public function doGetMyaccount(){
        $member = Member::model()->findByPk(Yii::app()->session['uid']);
        $inputs = array();
        $inputs['account'] = $member->account;
        $inputs['name'] = $member->name;
        $inputs['gender'] = $member->gender;
        $inputs['birthday'] = $member->birthday;
        $inputs['phone'] = $member->phone;
        $inputs['mobile'] = $member->mobile;
        $inputs['nationality'] = $member->nationality;
        $inputs['county'] = $member->county;
        $inputs['town'] = $member->town;
        $inputs['address'] = $member->address;
        $this->render('my_account',['data' => $inputs]);
    }
    public function ActionMy_points() {
        $this->render('my_points');
    }

    public function ActionMy_favorite() {
        $this->render('my_favorite');
    }

    public function ActionMy_record() {
        $this->render('my_record');
    }
}
?>
