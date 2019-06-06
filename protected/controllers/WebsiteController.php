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
        $operationlogService = new operationlogService();
        
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
}
?>