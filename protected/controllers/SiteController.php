<?php
class SiteController extends CController{
    // layout
    public $layout = "//layouts/front_end";
    private const PERPAGE = 5;
    private $ECPAY_HashKey     = "8FgzpAJbBEHPB1lq";
    private $ECPAY_HashIV      = "9NtfkektI11H2JY9";
    private $ECPAY_MerchantID  = "3100529";
    public $product_type = array(
        '1' => '點數',
        '2' => '自由載 30 天',
        '3' => '自由載 90 天',
        '4' => '自由載 360 天',
    );
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
        $member_point = $member_plan = 0;
        if (!Yii::app() -> user -> isGuest){
            $memberService = new MemberService();
            $memberplanService = new MemberplanService();
            $member = $memberService->findByMemId(Yii::app()->session['member_id']);
            $memberplan = $memberplanService->findByMemberPlanEnable(Yii::app()->session['member_id']);
            $member_point = $member->active_point;
            if($memberplan) $member_plan = $memberplan[0]['remain_amount'];
        }
        $this->render('image_info',array('photograph_data'=>$photograph_data,'category_service'=>$category_service,'same_category'=>$same_category,'member_point'=>$member_point,'member_plan'=>$member_plan));
    }

    public function ActionDownload_image(){
        if (Yii::app() -> user -> isGuest){
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $single_id = $_POST['single_id'];
            $size_type = $_POST['size_type'];
            $download_method = $_POST['download_method'];
            $photographService = new PhotographService();
            $single_data = $photographService->findSinglesize($single_id, $size_type);
            if($download_method == 1){  
                $this->doPointDownloadImage($single_id, $size_type, $single_data);
            }
            if($download_method == 2){
                $this->doPlanDownloadImage($single_id, $size_type, $single_data);
            }
        }
    }

    public function doPointDownloadImage($single_id, $size_type, $single_data){
        $memberService = new MemberService();
        $orderService = new OrderService();
        $imgdownloadService = new ImgdownloadService();
        $total_cost = $imgdownloadService->findMemberDownloadPoint();
        
        if(!$total_cost){
            $total_cost = 0;
        }else{
            if(isset($total_cost[0]['total_cost']))
                $total_cost = $total_cost[0]['total_cost'];
            else
                $total_cost = 0;
        } 
        $order_point = $orderService->findMemberOrderPoint();
        $order_point_data = array();
        $order_total_point = 0;
        if($order_point){
            foreach ($order_point as $key => $value) {
                $order_total_point += $value['pic_point'];
                $order_point_data[$value['orders_item_id']] = $order_total_point;
            }
        }
        $member = $memberService->findByMemId(Yii::app()->session['member_id']);
        $member_point = $member->active_point;
        $sale_point = $single_data[0]['sale_point'];
        if(($order_total_point-$total_cost-$sale_point) > 0){
            $img_download_orders_item_id = 0;
            foreach ($order_point_data as $key => $value) {
                if($value-$total_cost>0){
                    $img_download_orders_item_id = $key;
                    break;
                }
            }
            $date = date('Ymd');
            $cnt = $imgdownloadService->findAuthorizationNo();
            $authorization_no_Count = count($cnt) + 1;                        
            $authorization_no = $date;
            if (count($cnt) == 0) {
                $authorization_no .= str_pad(($authorization_no_Count), 4, '0', STR_PAD_LEFT);
            } else {
                $latestAuthorization_no = substr($cnt[0]->authorization_no, -4);
                $orderCount = (int) $latestAuthorization_no + 1;
                $authorization_no .= str_pad(($orderCount), 4, '0', STR_PAD_LEFT);
            }
            $img_download_data = array(
                "member_plan_id" => "",
                "orders_item_id" => $img_download_orders_item_id,
                "download_method" => 1,
                "single_id" => $single_id,
                "size_type" => $size_type,
                "cost" => (float)$single_data[0]['sale_point'],
                "authorization_no" => $authorization_no
            );
            //var_dump($img_download_data);exit();
            $img_download_create = $imgdownloadService->create($img_download_data);
            if($img_download_create[0]){
                echo json_encode(array('status'=>true,'error_msg'=>"",'ext'=>$single_data[0]['ext']));exit();
            }
        }else{
            echo json_encode(array('status'=>false,'error_msg'=>"點數餘額不足"));
            exit();
        }
    }
    public function doPlanDownloadImage($single_id, $size_type, $single_data){
        $memberplanService = new MemberplanService();
        $imgdownloadService = new ImgdownloadService();
        $memberplan = $memberplanService->findByMemberPlanEnable(Yii::app()->session['member_id']);
        if( $memberplan && $memberplan[0]['remain_amount']>0){
            $member_plan_id = $memberplan[0]['member_plan_id'];
            $order_item_id = $memberplan[0]['order_item_id'];
            $date = date('Ymd');
            $cnt = $imgdownloadService->findAuthorizationNo();
            $authorization_no_Count = count($cnt) + 1;                        
            $authorization_no = $date;
            if (count($cnt) == 0) {
                $authorization_no .= str_pad(($authorization_no_Count), 4, '0', STR_PAD_LEFT);
            } else {
                $latestAuthorization_no = substr($cnt[0]->authorization_no, -4);
                $orderCount = (int) $latestAuthorization_no + 1;
                $authorization_no .= str_pad(($orderCount), 4, '0', STR_PAD_LEFT);
            }
            $img_download_data = array(
                "member_plan_id" => $member_plan_id,
                "orders_item_id" => $order_item_id,
                "download_method" => 2,
                "single_id" => $single_id,
                "size_type" => $size_type,
                "cost" => 1,
                "authorization_no" => $authorization_no
            );
            $img_download_create = $imgdownloadService->create($img_download_data);
            if($img_download_create[0]){
                echo json_encode(array('status'=>true,'error_msg'=>"",'ext'=>$single_data[0]['ext']));exit();
            }
        }else{
            echo json_encode(array('status'=>false,'error_msg'=>"方案餘額不足"));
            exit();
        }
    }
    public function ActionGetimage(){
        $size_type = isset($_GET['size_type']) ? $_GET['size_type'] : "";
        $single_id =isset($_GET['single_id']) ? $_GET['single_id'] : "";
        $ext = isset($_GET['ext']) ? $_GET['ext'] : "";
        if($size_type != "" && $single_id != "" && $ext != ""){
            $photographService = new PhotographService();
            $single_data = $photographService->findSinglesize($single_id, $size_type);
            $download_name=Yii::app()->createUrl('/'). "/image_storage/".$size_type."/".$single_id.".".$ext;
            $filename = PHOTOGRAPH_STORAGE_DIR . $size_type . "/" . $single_id . "." . $ext;       
            if(isset($filename)){
                header("Content-Length: ". filesize($filename));
                header('Pragma: no-cache');
                header('Expires: 0');
                header('Cache-Control: no-store, no-cache,must-revalidate, post-check=0, pre-check=0');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($download_name).'"');
                header('Content-Transfer-Encoding: binary');
                readfile($filename);
                exit();
            }
        }else{
            exit();
        }
        
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
        require_once( dirname(__FILE__) . '/../../vendor/google/recaptcha/src/autoload.php');
        // _GOOGLE_RECAPTCHA_SEC_KEY 就是 google 給的 Secret Key
        $recaptcha = new \ReCaptcha\ReCaptcha('6LdxkAYTAAAAAK5e5Ya6xva3naFgDAxtI_vLTgz8');
        $gRecaptchaResponse = $_POST['g-recaptcha-response'];
        $remoteIp = $_SERVER['REMOTE_ADDR'];
        $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
        if(!$resp->isSuccess()){
            Yii::app()->session['error_msg'] = '請先證明您不是機器人';
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
        $useridentity = new UserIdentity($input['account'],$input['password']);
        $is_login = $useridentity->authenticate(1);
        if (!$is_login) {
            Yii::app()->session['error_msg'] = '帳號密碼錯誤';
            $this->redirect(Yii::app()->createUrl('site/login'));
        }else{
            $duration = 3600 * 24 * 30; // 30 days
            Yii::app()->user->login($useridentity, $duration);
            $this->redirect(Yii::app()->user->returnUrl);
            //$this->redirect(Yii::app()->createUrl('site'));
        }
    }
    public function actionLogout() {   
        Yii::app ()->user->logout ();
        unset(Yii::app()->session['member_id']);
        unset(Yii::app()->session['member_account']);
        unset(Yii::app()->session['member_name']);
        $this->redirect(Yii::app()->createUrl('site'));
    }
    //fb 註冊登入
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
            if($inputs['fb_user_id'] == $model->fb_user_id){
                $useridentity = new UserIdentity($model->account,"");
                $is_login = $useridentity->authenticate();
                Yii::app()->session['member_id'] = $model->id;//會員帳號ID
                Yii::app()->session['member_account'] = $model->account;//會員帳號
                Yii::app()->session['member_name'] = $model->name;//會員名稱
                $duration = 3600 * 24 * 30; // 30 days
                Yii::app()->user->login($useridentity, $duration);
                $this->redirect(Yii::app()->user->returnUrl);
                //$this->redirect(Yii::app()->createUrl('site'));
            }else{
                $this->redirect(Yii::app()->createUrl('site/login'));
            }
        }else{
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
    }

    //google 註冊登入
    public function ActionGoogleLogin(){
        // 0) 設定 client 端的 id, secret
        $client = new Google_Client;
        $client->setClientId(GOOGLE_CLINT_ID);
        $client->setClientSecret(GOOGLE_CLINT_SECRET);
         
        // 2) 使用者認證後，可取得 access_token 
        if (isset($_GET['code'])){
            $client->setRedirectUri(DOMAIN."site/googlelogin");
            $result = $client->authenticate($_GET['code']);
         
            if (isset($result['error'])) 
            {
                die($result['error_description']);
            }
            Yii::app()->session['google'] = $result;
            header("Location:".DOMAIN."site/googlelogin?action=profile");
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
                if($profile['sub'] == $model->google_sub){
                    $useridentity = new UserIdentity($model->account,"");
                    $is_login = $useridentity->authenticate();
                    Yii::app()->session['member_id'] = $model->id;//會員帳號ID
                    Yii::app()->session['member_account'] = $model->account;//會員帳號
                    Yii::app()->session['member_name'] = $model->name;//會員名稱
                    $duration = 3600 * 24 * 30; // 30 days
                    Yii::app()->user->login($useridentity, $duration);
                    $this->redirect(Yii::app()->user->returnUrl);
                    //$this->redirect(Yii::app()->createUrl('site'));
                }else{
                    $this->redirect(Yii::app()->createUrl('site/login'));
                }
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
            $client->setRedirectUri(DOMAIN."site/googlelogin");
            $url = $client->createAuthUrl();
            header("Location:{$url}");
        }
    }

    //前台註冊，驗證帳號, 驗證通過啟用帳號才能登入
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
    //前台註冊頁面
    public function ActionRegister() {
        if(Yii::app()->request->isPostRequest) {
            $this->doPostRegister();
        }else{
            $this->doGetRegister();
        }
    }
    //前台註冊，寫入會員資料發送帳號驗證信
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
        }else{
            Yii::app()->session['error_msg'] = '帳號已存在';
            $this->render('register');
        }
    }
    //前台註冊頁面
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
    //前台忘記密碼，帳號驗證，驗證通過修改會員密碼為臨時密碼
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
    //前台忘記密碼頁面
    public function ActionForget() {
        if(Yii::app()->request->isPostRequest) {
            $this->doPostForget();
        }else{
            $this->doGetForget();
        }        
    }
    //前台忘記密碼頁面
    public function doGetForget(){
        $this->render('forget');
    }
    //前台忘記密碼，產生臨時密碼並發送驗證信，驗證通過才會修改會員密碼
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
    //會員專區
    public function ActionMy_account() {
        if (Yii::app() -> user -> isGuest){
            Yii::app()->user->returnUrl = Yii::app()->request->urlReferrer;
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
        if(Yii::app()->request->isPostRequest) {
            $this->doPostMyaccount();
        }else{
            $this->doGetMyaccount();
        }        
    }

    //會員專區-會員資料修改
    public function doPostMyaccount(){
        $inputs['id'] = Yii::app()->session['member_id'];
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
        $inputs['active'] = "Y";
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
            Yii::app()->session['success_msg'] = '修改成功';
            $this->redirect(Yii::app()->createUrl('site/my_account',['data' => $inputs]));
        }
    }
    //會員專區-會員資料
    public function doGetMyaccount(){
        $member = Member::model()->findByPk(Yii::app()->session['member_id']);
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
    //會員專區-我的點數
    public function ActionMy_points() {
        if (Yii::app() -> user -> isGuest){
            Yii::app()->user->returnUrl = Yii::app()->request->urlReferrer;
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
        $memberplanService = new MemberplanService();
        $imgdownloadService = new ImgdownloadService();
        $member = Member::model()->findByPk(Yii::app()->session['member_id']);
        $member_plan = $memberplanService->findByMemberAllPlanEnable(Yii::app()->session['member_id']);
        $plan_data = array('2'=>0,'3'=>0,'4'=>0);
        if($member_plan){
            foreach ($member_plan as $key => $value) {
                $plan_data[$value['product_type']] += $value['remain_amount'];
            }
        }
        $image_download = $imgdownloadService->findMemberDownloadImage(Yii::app()->session['member_id']);
        $this->render('my_points',array('member' => $member,'member_plan' => $plan_data, 'image_download'=>$image_download));
    }
    //會員專區-我的收藏
    public function ActionMy_favorite() {
        $this->render('my_favorite');
    }
    //會員專區-購買紀錄
    public function ActionMy_record() {
        $orderService = new OrderService();
        $member_order_data = $orderService->findMemberOrderData(Yii::app()->session['member_id'],3);
        $this->render('my_record',array('member_order_data' => $member_order_data));
    }

    public function ActionPrivacy(){
        $data = Sitepolicy::model()->findByPk(1);
        $this->render('privacy',array('data'=>$data));
    }

    public function ActionMember_rule(){
        $data = Sitepolicy::model()->findByPk(2);
        $this->render('member_rule',array('data'=>$data));
    }

    public function ActionPlan(){
        $productService = new ProductService();
        $data = $productService->findWithStatus(1);
        $this->render('plan', array('data'=>$data, 'product_type'=> $this->product_type));
    }
    public function ActionCheck_order(){
        if (Yii::app() -> user -> isGuest){
            Yii::app()->user->returnUrl = Yii::app()->request->urlReferrer;
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
        if(isset($_GET['product_id'])){
            $product_id = $_GET['product_id'];
            $productService = new ProductService();
            $memberService = new MemberService();
            $memberaddressbook = $memberService->findMemberAddressBook(Yii::app()->session['member_id']);

            $data = $productService->findById($product_id);
            if( $data->product_type == 1 ){
                $product_name = $this->product_type[$data->product_type] . ' ( ' . $data->pic_point . ' 點 )';
            }else{
                $product_name = $this->product_type[$data->product_type] . ' ( ' . $data->pic_number . ' 張 )';
            }
            $data->product_name = $data->product_name . $product_name;
            $this->render('check_order', array('data'=>$data, 'memberaddressbook'=>$memberaddressbook, 'product_type'=> $this->product_type));
        }else{

        } 
    }

    public function ActionSend_order(){
        if (Yii::app() -> user -> isGuest){
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
        //var_dump($_POST);exit();
        if(isset($_POST['product_id'])){
            $product_id = $_POST['product_id'];
            $productService = new ProductService();
            $memberService = new MemberService();
            $orderService = new OrderService();
            $product_data = $productService->findById($product_id);
            if( $product_data->product_type == 1 ){
                $product_name = $product_data->product_name . $this->product_type[$product_data->product_type] . ' ( ' . $product_data->pic_point . ' 點 )';
            }else{
                $product_name = $product_data->product_name . $this->product_type[$product_data->product_type] . ' ( ' . $product_data->pic_number . ' 張 )';
            }
            $order_data = array(
                "product_id" => $product_id,
                "pay_method" => $_POST['pay_method'],
                "member_id" => Yii::app()->session['member_id'],
                "mobile" => isset($_POST['mobile'])?$_POST['mobile']:"",
                "nationality" => isset($_POST['nationality'])?$_POST['nationality']:"",
                "county" => isset($_POST['county'])?$_POST['county']:"",
                "town" => isset($_POST['town'])?$_POST['town']:"",
                "zipcode" => isset($_POST['zipcode'])?$_POST['zipcode']:"",
                "address" => isset($_POST['mobile'])?$_POST['address']:"",
                "invoice_category" => isset($_POST['invoice_category'])?$_POST['invoice_category']:"",
                "invoice_number" => isset($_POST['invoice_number'])?$_POST['invoice_number']:"",
                "invoice_title" => isset($_POST['invoice_title'])?$_POST['invoice_title']:"",
                "cost_total" => $product_data->price,
                "order_category"=>$product_data->product_type,
                "order_detail_status" => 3,
                "product_name" => $product_name
            );

            $memberaddressbook_update_insert = $memberService->updateMemberAddressBook(Yii::app()->session['member_id'],$order_data);
            $order_create = $orderService->create_order($order_data);
            if($order_create[0]){
                $this->redirect(Yii::app()->createUrl('/site/Ecpayapi'));
            }else{
                $this->redirect(Yii::app()->createUrl('/site/plan'));
            }
        }else{
            $this->redirect(Yii::app()->createUrl('/site/plan'));
        } 
    }

    public function actionEcpayapi(){

        if( empty(Yii::app()->session['order'])){
           echo '尚未填寫表單資訊,無法進行結帳動作';
           exit;
        }
        require_once( dirname(__FILE__) . '/../components/ECPay.Payment.Integration.php');
        try {
            $obj = new ECPay_AllInOne();       
            //服務參數
            $obj->ServiceURL  = "https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5";      //服務位置
            $obj->HashKey     = $this->ECPAY_HashKey;//測試用Hashkey，請自行帶入ECPay提供的HashKey
            $obj->HashIV      = $this->ECPAY_HashIV;//測試用HashIV，請自行帶入ECPay提供的HashIV
            $obj->MerchantID  = $this->ECPAY_MerchantID;//測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $obj->EncryptType = '1';//CheckMacValue加密類型，請固定填入1，使用SHA256加密
            $igt_amount = (int)(Yii::app()->session['order']['cost_total']);

            $obj->Send['OrderResultURL'] = DOMAIN . 'site/index';//基本參數(請依系統規劃自行調整)
            $obj->Send['ReturnURL'] = DOMAIN . "site/osuccess" ;     //付款完成通知回傳的網址
            $obj->Send['MerchantTradeNo'] = Yii::app()->session['order']['order_id'];//訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');//交易時間 一定要用 "/" 很重要
            $obj->Send['TotalAmount'] = (int)(Yii::app()->session['order']['cost_total']);//交易金額
            $obj->Send['TradeDesc'] = Yii::app()->session['order']['product_name']; //交易描述
            $obj->Send['ChoosePayment']     = ECPay_PaymentMethod::ALL;                  //付款方式:全功能
            //$obj->Send['IgnorePayment'] = implode("#", json_decode($company->ignore_payment)); //不顯示的付款方式，多項的話以 # 分隔

            //訂單的商品資料
            array_push($obj->Send['Items'], 
                array('Name' => Yii::app()->session['order']['product_name'], 
                      'Price' => (int)(Yii::app()->session['order']['cost_total']),
                      'Currency' => "元", 
                      'Quantity' => (int)1,
                      'URL' => "dedwed")
            );
            /*
            $obj->SendExtend['CreditInstallment'] = '' ;    //分期期數，預設0(不分期)，信用卡分期可用參數為:3,6,12,18,24
            $obj->SendExtend['InstallmentAmount'] = 0 ;    //使用刷卡分期的付款金額，預設0(不分期)
            $obj->SendExtend['Redeem'] = false ;           //是否使用紅利折抵，預設false
            $obj->SendExtend['UnionPay'] = false;          //是否為聯營卡，預設false;
            */
            unset(Yii::app()->session['order']);
            //產生訂單(auto submit至ECPay)
            $obj->CheckOut();
        } catch (Exception $e) {
            echo $e->getMessage();
        } 
    }

    public function actionOsuccess(){
        require_once( dirname(__FILE__) . '/../components/ECPay.Payment.Integration.php');
        try {
            // 收到綠界科技的付款結果訊息，並判斷檢查碼是否相符
            $AL = new ECPay_AllInOne();
            $AL->HashKey     = $this->ECPAY_HashKey;//測試用Hashkey，請自行帶入ECPay提供的HashKey
            $AL->HashIV      = $this->ECPAY_HashIV;//測試用HashIV，請自行帶入ECPay提供的HashIV
            $AL->MerchantID  = $this->ECPAY_MerchantID;//測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $AL->EncryptType = '1'; 
            //正式要開啟 - 很重要
            //$backval = $AL->CheckOutFeedback();//正式要開啟
            //正式要開啟 - 很重要

            //測試用
            $backval['MerchantTradeNo'] = 'P201909240003';
            $backval['RtnCode'] = '1';
            //測試用
        
            // 只有RtnCode == 1 時才是交易成功
            if($backval['RtnCode'] == 1){            
                // 修改對應訂單
                $tmpser     = new OrderService();
                $mailService     = new MailService();
                $tmpres     = $tmpser->chanage_pay_status( $backval['MerchantTradeNo'] );

                $order = Orders::model()->findByPk($backval['MerchantTradeNo']);
                if( $tmpres[0] == true){
                    $open_order_plan = $tmpser->open_order_plan($backval['MerchantTradeNo']);
                }
                // 抓取有關會員帳戶的一切資料
                $member = Member::model()->findByPk($order->member_id);
                $today = date('Y-m-d') ;
                if( $tmpres[0] == true){
                    $to        = $member->account; //收件者
                
                    $subject   = "付款成功通知書"; //信件標題
                    
                    if( $to ){
                        $msg    = "親愛的顧客您好,<br/>
                        感謝您於本平台訂購商品,我們已收到商品款項<br/>
                        <a href='" . DOMAIN . "site/index'>點此進入平台</a><br/>
                        如有任何問題,歡迎與本平台客服聯繫<br/>";
                    }
                    $headers   = "From: wenhsun7@ms19.hinet.net\r\n"; //寄件者
                    $headers  .= "Content-Type: text/html; charset=UTF-8\r\n";
            
                    if($mailService->sendImageMail($to,$member->name,$msg,$subject)){
                        Yii::log(date('Y-m-d H:i:s') . 'Order mail success.', CLogger::LEVEL_INFO);
                    }else{
                        Yii::log(date('Y-m-d H:i:s') . 'Order mail fail.', CLogger::LEVEL_INFO);
                        echo '0|';
                    }
                }
            }
            $this->redirect(Yii::app()->createUrl('/site/index'));
        } catch(Exception $e) {
            echo '0|' . $e->getMessage();
        }  
    
    }//success end
}
?>
