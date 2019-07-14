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
        // 0) 設定 client 端的 id, secret
        $client = new Google_Client;
        $client->setClientId(GOOGLE_CLINT_ID);
        $client->setClientSecret(GOOGLE_CLINT_SECRET);
         
        // 2) 使用者認證後，可取得 access_token 
        if (isset($_GET['code'])){
            $client->setRedirectUri("http://web.taiwanblacktea.com.tw/site/login");
            $result = $client->authenticate($_GET['code']);
         
            if (isset($result['error'])) 
            {
                die($result['error_description']);
            }
            Yii::app()->session['google'] = $result;
            header("Location:http://web.taiwanblacktea.com.tw/site/login?action=profile");
        }         
        // 3) 使用 id_token 取得使用者資料。另有 setAccessToken()、getAccessToken() 可以設定與取得 token
        elseif (isset($_GET['action']) && $_GET['action'] == "profile")
        {
            $profile = $client->verifyIdToken(Yii::app()->session['google']['id_token']);
            echo json_encode($profile,true);//使用者個人資料
        }
        // 1) 前往 Google 登入網址，請求用戶授權
        else 
        {
            $client->revokeToken();       
            // 添加授權範圍，參考 https://developers.google.com/identity/protocols/googlescopes
            $client->addScope(['https://www.googleapis.com/auth/userinfo.profile','https://www.googleapis.com/auth/userinfo.email']);
            $client->setRedirectUri("http://web.taiwanblacktea.com.tw/site/login");
            $url = $client->createAuthUrl();
            header("Location:{$url}");
        }
    }

    public function ActionMy_points() {
        $this->render('my_points');
    }

    public function ActionMy_favorite() {
        $this->render('my_favorite');
    }
}
?>
