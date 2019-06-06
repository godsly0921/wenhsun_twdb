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

    public function banner_update( $input ){
        $operationlogService = new operationlogService();
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
        $operationlogService = new operationlogService();
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
        $operationlogService = new operationlogService();
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

}