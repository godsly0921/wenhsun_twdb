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
        $member_point = $member_plan = 0;
        if (!Yii::app() -> user -> isGuest){
            $memberService = new MemberService();
            $memberplanService = new MemberplanService();
            $member = $memberService->findByMemId(Yii::app()->session['uid']);
            $memberplan = $memberplanService->findByMemberPlanEnable(Yii::app()->session['uid']);
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
        $member = $memberService->findByMemId(Yii::app()->session['uid']);
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
        $memberplan = $memberplanService->findByMemberPlanEnable(Yii::app()->session['uid']);
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
                Yii::app()->session['uid'] = $model->id;//會員帳號ID
                Yii::app()->session['pid'] = $model->account;//會員帳號
                Yii::app()->session['name'] = $model->name;//會員名稱
                $duration = 3600 * 24 * 30; // 30 days
                Yii::app()->user->login($useridentity, $duration);
                $this->redirect(Yii::app()->createUrl('site'));
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
                    Yii::app()->session['uid'] = $model->id;//會員帳號ID
                    Yii::app()->session['pid'] = $model->account;//會員帳號
                    Yii::app()->session['name'] = $model->name;//會員名稱
                    $duration = 3600 * 24 * 30; // 30 days
                    Yii::app()->user->login($useridentity, $duration);
                    $this->redirect(Yii::app()->createUrl('site'));
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
        if(Yii::app()->request->isPostRequest) {
            $this->doPostMyaccount();
        }else{
            $this->doGetMyaccount();
        }        
    }

    //會員專區-會員資料修改
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
    //會員專區-會員資料
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
    //會員專區-我的點數
    public function ActionMy_points() {
        $this->render('my_points');
    }
    //會員專區-我的收藏
    public function ActionMy_favorite() {
        $this->render('my_favorite');
    }
    //會員專區-購買紀錄
    public function ActionMy_record() {
        $this->render('my_record');
    }

    public function ActionPrivacy(){
        $this->render('privacy');
    }

    public function ActionMember_rule(){
        $this->render('member_rule');
    }

    public function ActionPlan(){
        $this->render('plan');
    }
}
?>
