<?php
class WebsiteController extends Controller{
    // layout
    public $layout = "//layouts/back_end";
    
    // 登入驗證
    protected function needLogin(): bool
    {
        return true;
    }

    public function ActionBanner_list(){
        $websiteService = new WebsiteService();
        $banner_data = $websiteService->findAllBanner();
        $this->render('banner_list',['banner_data'=>$banner_data]);
    }

    public function ActionBanner_new(){    
        if( Yii::app()->request->isPostRequest ){
            if (!CsrfProtector::comparePost()) {
                $this->redirect('index');
            }
            $websiteService = new WebsiteService();
            $inputs = [];
            $inputs["link"] = filter_input(INPUT_POST, "link");
            $inputs["title"] = filter_input(INPUT_POST, "title");
            $inputs["alt"] = filter_input(INPUT_POST, "alt");
            $inputs["sort"] = filter_input(INPUT_POST, "sort");
            $inputs["image"] = $_FILES["image"];
            $banner_create = $websiteService -> banner_create( $inputs );
            if( $banner_create[0] === true ){
                Yii::app()->session['success_msg'] = $banner_create[1];                
            }else{
                Yii::app()->session['error_msg'] = $banner_create[1];
            }
            $this->redirect(array('website/banner_list'));
        }else{
            $this->render('banner_new');
        }
    }

    public function ActionBanner_update($id){
        $websiteService = new WebsiteService();
        $operationlogService = new OperationlogService();
        
        if( Yii::app()->request->isPostRequest ){
            if (!CsrfProtector::comparePost()) {
                $this->redirect('index');
            }
            $inputs = [];
            $inputs["home_banner_id"] = $id;
            $inputs["link"] = filter_input(INPUT_POST, "link");
            $inputs["title"] = filter_input(INPUT_POST, "title");
            $inputs["alt"] = filter_input(INPUT_POST, "alt");
            $inputs["sort"] = filter_input(INPUT_POST, "sort");
            $inputs["image"] = $_FILES["image"];
            $banner = $websiteService -> banner_update( $inputs );
            if( $banner[0] === true ){
                Yii::app()->session['success_msg'] = $banner[1];
            }else{
                Yii::app()->session['error_msg'] = $banner[1];
            }
            $banner = $websiteService->findBannerById($id);
            $this->render('banner_update',array( 'banner' => $banner ));
        }else{
            $banner = $websiteService->findBannerById($id);
            $this->render('banner_update',array( 'banner' => $banner ));
        }
    }

    public function ActionBanner_delete(){
        $home_banner_id = $_POST['id'];
        $websiteService = new WebsiteService();
        $banner_delete = $websiteService->banner_delete($home_banner_id);
        if( $banner_delete[0] === true ){
            Yii::app()->session['success_msg'] = $banner_delete[1];                
        }else{
            Yii::app()->session['error_msg'] = $banner_delete[1];
        }
        $this->redirect(Yii::app()->createUrl('website/banner_list'));
    }

    public function ActionAd_list(){
        $websiteService = new WebsiteService();
        $ad_data = $websiteService->findAllAd();
        $this->render('ad_list',['ad_data'=>$ad_data]);
    }

    public function Actionfindsingle(){
        $single_id = isset($_GET['single_id'])?$_GET['single_id']:'';
        $category_id = isset($_GET['category_id'])?$_GET['category_id']:'';
        $keyword = isset($_GET['keyword'])?$_GET['keyword']:'';
        $websiteService = new WebsiteService();
        $result = $websiteService->findPhotoPublishAndCopyright( $single_id, $category_id, $keyword );
        echo json_encode(array('status'=>true,'data'=>iterator_to_array($result)));
        exit();
    }

    public function ActionAd_new(){
        if( Yii::app()->request->isPostRequest ){
            if (!CsrfProtector::comparePost()) {
                $this->redirect('index');
            }
            $websiteService = new WebsiteService();
            $inputs = [];
            $inputs["single_id"] = $_POST["single_id"];
            $inputs["sort"] = $_POST["sort"];

            $ad_create = $websiteService -> ad_create( $inputs );
            if( $ad_create[0] === true ){
                Yii::app()->session['success_msg'] = $ad_create[1];                
            }else{
                Yii::app()->session['error_msg'] = $ad_create[1];
            }
            $this->redirect(array('website/ad_list'));
        }else{
            $category_service = new CategoryService();
            $category_data = $category_service->findCategoryMate();
            $this->render('ad_new',array('category_data'=>$category_data));
        }
    }

    public function ActionAd_update($id){
        $websiteService = new WebsiteService();
        $operationlogService = new OperationlogService();
        
        if( Yii::app()->request->isPostRequest ){
            if (!CsrfProtector::comparePost()) {
                $this->redirect('index');
            }
            $inputs = [];
            $inputs["single_id"] = $id;
            $inputs["sort"] = filter_input(INPUT_POST, "sort");
            $ad = $websiteService -> ad_update( $inputs );
            if( $ad[0] === true ){
                Yii::app()->session['success_msg'] = $ad[1];
            }else{
                Yii::app()->session['error_msg'] = $ad[1];
            }
            $ad = $websiteService->findAdById($id);
            $this->render('ad_update',array( 'ad' => $ad ));
        }else{
            $ad = $websiteService->findAdById($id);
            $this->render('ad_update',array( 'ad' => $ad ));
        }
    }

    public function ActionAd_delete(){
        $home_ad_id = $_POST['id'];
        $websiteService = new WebsiteService();
        $ad_delete = $websiteService->ad_delete($home_ad_id);
        if( $ad_delete[0] === true ){
            Yii::app()->session['success_msg'] = $ad_delete[1];                
        }else{
            Yii::app()->session['error_msg'] = $ad_delete[1];
        }
        $this->redirect(Yii::app()->createUrl('website/ad_list'));
    }

    public function ActionAbout_list() {
        $aboutService = new AboutService();
        $about = $aboutService->getAllAbout();
        $this->render('about_list', array('about' => $about));
    }

    public function ActionAbout_update($id){
        $_SERVER['REQUEST_METHOD'] === "POST" ? $this->doPostAboutUpdate($id) : $this->doGetAboutUpdate($id);
    }

    public function doGetAboutUpdate($id) {
        $aboutService = new AboutService();
        $about = $aboutService->findById($id);
        $this->render('about_update', array('about' => $about));
    }

    public function doPostAboutUpdate($id) {
        $aboutService = new AboutService();
        if(Yii::app()->request->isPostRequest) {
            if (!CsrfProtector::comparePost()) {
                $this->redirect('about_list');
            }
            $inputs = [];
            $inputs['id'] = $id;
            $inputs['description'] = filter_input(INPUT_POST, 'description');
            $inputs['paragraph'] = filter_input(INPUT_POST, 'paragraph');
            $inputs['image'] = $_FILES['image'];
            $result = $aboutService->update($inputs);

            if ($result[0]) {
                Yii::app()->session['success_msg'] = '更新成功';
            } else {
                Yii::app()->session['error_msg'] = $result[1];
            }
        }

        $about = $aboutService->findById($id);
        $this->render('about_update', array('about' => $about));
    }
}
?>