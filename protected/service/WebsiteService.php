<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class WebsiteService
{
    public function findAllBanner(){
        $all_banner = array();
        $all_banner = Homebanner::model()->findAll();
        return $all_banner;
    }

    public function findAllAd(){
        $all_ad = array();
        $all_ad = Homead::model()->findAll(array('order'=>'sort asc'));
        return $all_ad;
    }

    public function findAllAdInfo(){
        $all_ad = array();
        $all_ad = Yii::app()->db->createCommand()
        ->select('*')
        ->from('home_ad ad')
        ->leftjoin('single s', 'ad.single_id = s.single_id')
        ->order('ad.sort asc')
        ->queryAll();
        return $all_ad;
    }
    public function banner_update( $input ){
        $operationlogService = new OperationlogService();
        $model = $this->findBannerById($input['home_banner_id']);
        $model->link = $input['link'];
        $model->title = $input['title'];
        $model->alt = $input['alt'];
        $model->sort = $input['sort'];
        $model->update_time = date('Y-m-d H:i:s');
        $model->update_account_id = Yii::app()->session['uid'];
        $upload_image = $input['image'];
        if($upload_image['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'],HOMEBANNER.$uuid_name.'.'.$ext);
            $image_show_path = HOMEBANNER_SHOW.$uuid_name.'.'.$ext;
            $model->image = $image_show_path;
        }
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                $motion = "更新輪播圖";
                $log = "更新 輪播圖編號 = " . $input['home_banner_id'] . "；輪播圖名稱 = " . $model->image;
                $operationlogService->create_operationlog( $motion, $log );
                return array(true,'修改成功',$model);         
            }else{      
                $motion = "更新輪播圖";
                $log = "更新 輪播圖編號 = " . $input['home_banner_id'] . "；輪播圖名稱 = " . $model->image;
                $operationlogService->create_operationlog( $motion, $log, 0 ); 
                return array(false,$model->getErrors());
            }
        }       
        return $model;
    }

    public function banner_create( $input ){
        $operationlogService = new OperationlogService();
        $model = new Homebanner();
        $model->link = $input['link'];
        $model->title = $input['title'];
        $model->alt = $input['alt'];
        $model->sort = $input['sort'];
        $upload_image = $input['image'];
        if($upload_image['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'],HOMEBANNER.$uuid_name.'.'.$ext);
            $image_show_path = HOMEBANNER_SHOW.$uuid_name.'.'.$ext;
            $model->image = $image_show_path;
        }
        $model->update_time = date('Y-m-d H:i:s');
        $model->update_account_id = Yii::app()->session['uid'];
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                $motion = "建立輪播圖";
                $log = "建立 輪播圖名稱 = " . $uuid_name . "." . $ext;
                $operationlogService->create_operationlog( $motion, $log );
                return array(true,'新增成功');         
            }else{       
                $motion = "建立輪播圖";
                $log = "建立 輪播圖名稱 = " . $upload_image['name'];
                $operationlogService->create_operationlog( $motion, $log, 0 );
                return array(false,$model->getErrors());
            }
        }
        
        return $model;
    }

    public function banner_delete($id){
        $operationlogService = new OperationlogService();
        $post = Homebanner::model()->findByPk( $id );
        if($post->delete()){
            $motion = "刪除輪播圖";
            $log = "刪除 輪播圖編號 = " . $id;
            $operationlogService->create_operationlog( $motion, $log );
            return array(true,'刪除成功');
        }else{
            $motion = "刪除輪播圖";
            $log = "刪除 輪播圖編號 = " . $id;
            $operationlogService->create_operationlog( $motion, $log, 0 );
            return array(false,$post->getErrors());
        }
    }

    public function findBannerById($id)
    {
        $model = Homebanner::model()->findByPk($id);
        return $model;
    }

    public function findPhotoPublishAndCopyright( $single_id, $category_id, $keyword ){
        $filter = $store_filter = $option = $result = array();
        $mongo = new Mongo();
        if( $single_id != '' ){
            $store_filter['single_id'] = $single_id;          
        }
        if( $category_id != '' ){
            $store_filter['category_id'] = array( '$in' => $category_id );
        }
        if( $keyword != '' ){
            $store_filter['keyword'] = array( '$regex' => '/' . $keyword . '/' );
        }
        if(count($store_filter) > 1){
            $filter['$or'] = array(); 
            foreach ($store_filter as $key => $value) {
                array_push($filter['$or'], array($key=>$value));
            }
        }else{
            $filter = $store_filter; 
        }
        $filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'));
        $option['projection'] = array('single_id'=>1,'people_info'=>1,'object_name'=>1,'filming_date'=>1,'filming_location'=>1);
        $result = $mongo->search_record('wenhsun', 'single', $filter, $option);
        return $result;
    }

    public function ad_create( $input ){
        $operationlogService = new OperationlogService();
        foreach ($input['single_id'] as $key => $value) {
            if(!$this->findAdById($value)){
                $model = new Homead();
                $model->single_id = $value;
                $model->sort = $input['sort'][$key];
                $model->update_time = date('Y-m-d H:i:s');
                $model->update_account_id = Yii::app()->session['uid'];
                if (!$model->validate()) {
                    return $model;
                }

                if (!$model->hasErrors()) {
                    if( $model->save() ){
                        $motion = "建立廣告圖";
                        $log = "建立 廣告圖編號 = " . $value;
                        $operationlogService->create_operationlog( $motion, $log );
                                 
                    }else{       
                        $motion = "建立廣告圖";
                        $log = "建立 廣告圖編號 = " . $value;
                        $operationlogService->create_operationlog( $motion, $log, 0 );
                    }
                }
            }         
        }
        return array(true,'新增成功');
    }

    public function ad_update( $input ){
        $operationlogService = new OperationlogService();
        $model = $this->findAdById($input['single_id']);
        $model->sort = $input['sort'];
        $model->update_time = date('Y-m-d H:i:s');
        $model->update_account_id = Yii::app()->session['uid'];
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                $motion = "更新廣告圖";
                $log = "更新 廣告圖編號 = " . $input['single_id'];
                $operationlogService->create_operationlog( $motion, $log );
                return array(true,'修改成功',$model);         
            }else{      
                $motion = "更新廣告圖";
                $log = "更新 廣告圖編號 = " . $input['single_id'];
                $operationlogService->create_operationlog( $motion, $log, 0 ); 
                return array(false,$model->getErrors());
            }
        }       
        return $model;
    }

    public function ad_delete($id){
        $operationlogService = new OperationlogService();
        $post = Homead::model()->findByPk( $id );
        if($post->delete()){
            $motion = "刪除廣告圖";
            $log = "刪除 廣告圖編號 = " . $id;
            $operationlogService->create_operationlog( $motion, $log );
            return array(true,'刪除成功');
        }else{
            $motion = "刪除廣告圖";
            $log = "刪除 廣告圖編號 = " . $id;
            $operationlogService->create_operationlog( $motion, $log, 0 );
            return array(false,$post->getErrors());
        }
    }

    public function findAdById($id)
    {
        $model = Homead::model()->findByPk($id);
        return $model;
    }
}