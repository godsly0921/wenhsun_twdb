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
        $siteService = new SiteService();
        $result = $siteService->findPhoto("", $keyword, $category_id, $filming_date, $object_name, $page, $limit);
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
        $siteService = new SiteService();
        $category_service = new CategoryService();
        $filming_date_range = $siteService->findPhotoFilmingRange();
        $distinct_object_name = $siteService->findPhotoObjectname();        
        $category_data = $category_service->findCategoryMate();
        $total_result = $siteService->findPhotoCount("", $keyword, $category_id, $filming_date, $object_name);
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
}
?>