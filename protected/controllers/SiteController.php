<?php
class SiteController extends CController{
    // layout
    public $layout = "//layouts/front_end";
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

    public function ActionSearch(){
        $keyword = isset($_GET["keyword"])?$_GET["keyword"]:"";
        $page = isset($_GET["page"])?$_GET["page"]:"";
        $category_id = isset($_GET["category_id"])?$_GET["category_id"]:"";
        $filming_date = isset($_GET["filming_date"])?$_GET["filming_date"]:"";
        $object_name = isset($_GET["object_name"])?$_GET["object_name"]:"";
        $siteService = new SiteService();
        $search_result = $siteService->findPhoto("", $keyword, $category_id, $filming_date, $object_name, 1, 10);
        echo json_encode(iterator_to_array($search_result));exit();
        var_dump($search_result);exit();
    }
}
?>