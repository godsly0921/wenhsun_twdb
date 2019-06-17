<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 下午 05:07
 */
class NewsService
{
    public function findNews()
    {
        $result = News::model()->findAll([
            'condition'=>"new_type=:new_type",
            'params'=>[':new_type'=>1],
            'order' => 'sort ASC,new_createtime DESC',
        ]);
        return $result;
    }

    public function findAdminNews()//管理使用
    {
        $result = News::model()->findAll([
        ]);
        return $result;
    }

    /**
     * @param array $input
     * @return News
     */
    public function create(array $inputs)
    {
        $news = new News();
        $news->new_title = $inputs['new_title'];
        $news->new_content = $inputs['new_content'];
        $news->builder = Yii::app()->session['uid'];

        $news->sort =  $inputs['sort'];

        $upload_image = $inputs['new_image'];

        if($upload_image['error']== 1){
            $news->addError('save_fail', '上傳失敗，檔案超過2MB');
            return $news;
        }

        if($upload_image['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'],IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            $image_show_path = IMAGES_SHOW_DIR.$uuid_name.'.'.$ext;
            $news->new_image = $image_show_path;
        }
        $news->image_name =  $upload_image['name'];
        $news->new_createtime = date("Y-m-d H:i:s");
        $news->new_type = $inputs['new_type'];

        if (!$news->validate()) {
            return $news;
        }

        if (!$news->hasErrors()) {
            $success = $news->save();
        }

        if ($success === false) {
            $news->addError('save_fail', '新增公告失敗');
            return $news;
        }

        return $news;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateNews(array $inputs)
    {   /*var_dump($inputs);
        exit;*/
        $news = News::model()->findByPk($inputs["id"]);

        $news->id = $news->id;
        $news->new_title = $inputs['new_title'];
        $news->new_content = $inputs['new_content'];
        $news->builder = Yii::app()->session['uid'];
        $news->sort =  $inputs['sort'];

        $upload_image = $inputs['new_image'];
        if($upload_image['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'],IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            //ImageResize::resize($image_path,$image_path);
            $image_show_path = IMAGES_SHOW_DIR.$uuid_name.'.'.$ext;
            $news->new_image = $image_show_path;
        }
        $news->new_createtime = date("Y-m-d H:i:s");
        $news->new_type = $inputs['new_type'];

        if ($news->validate()) {
            $news->update();
        }

        return $news;
    }

    /**
     * @param $id
     * @return array|mixed|null
     */
    public function findById($id)
    {
        if ($id === null) {
            $result = null;
        } else {
            $result = News::model()->findByAttributes(
                ['id' => $id]
            );
        }

        return $result;
    }

    public function findNewestNews($lang, $limit)
    {
        $result = News::model()->findAllByAttributes(
            ['new_language' => $lang, 'new_type' => '1'],
            ['order' => 'new_createtime DESC'],
            ['limit' => $limit]
        );

        return $result;
    }

    public function addview( $news , $mid ){
        
        $ex = News_view::model()->findByAttributes(
        array('news_id'=>$news,'member_id'=>$mid));
        
        if( count($ex) < 1){
            
            $post=new News_view;
            $post->news_id     = $news;
            $post->member_id   = $mid;
            $post->create_time = date("Y-m-d H:i:s");
            $post->edit_time   = date("Y-m-d H:i:s");

            if($post->save()){
                return true;
            }else{
                var_dump( $post->getErrors() );
                return false;
            }

        }else{

            return true;
        }
    }

    public function havesaw($mid){

        $ex = News_view::model()->findAllByAttributes(
        array('member_id'=>$mid));

        return $ex;
    }

}