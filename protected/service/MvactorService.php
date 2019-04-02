<?php

class MvactorService
{

    public function findMovielist(){
        $datas = Mvinfo::model()->findAll(array(
            'select' => '*',
            'order' => 'create_date ASC,status ASC',
        ));

        if($datas==null){
            $datas == false;
        }
        return $datas;
    }

    public function findMvactorlist(){
        $datas = Mvactor::model()->findAll(array(
            'select' => '*',
            'order' => 'id ASC,create_date ASC,status ASC',
            'condition'=>"status=:status",
            'params'=>[':status'=>'1']
        ));

        if($datas==null){
            $datas == false;
        }
        return $datas;
    }

    public function findSceneslist(){
        $datas = Scenes::model()->findAll(array(
            'select' => '*',
            'order' => 'id ASC,create_time ASC,status ASC',
            'condition'=>"status=:status",
            'params'=>[':status'=>'1']
        ));

        if($datas==null){
            $datas == false;
        }
        return $datas;
    }

    public function findMvalbumlist(){
        $datas = Mvalbum::model() -> findAll(array(
            'select' => 'id,name,status,number,catalog,create_date,edit_date',
            'order' => 'id ASC' ,
        ));

        if($datas==null){
            $datas == false;
        }
        return $datas;
    }

    public function mobile_a(){
        $user = Yii::app()->db->createCommand()
            ->select('count(mobile)')
            ->from('movie_view')
            ->group('mobile')
            ->order('mobile ASC')
            ->queryall();

        return $user;
    }

    public function type_click(){

        $data = Yii::app()->db->createCommand()
            ->select('id,name')
            ->from('movie_catalog')
            ->queryall();


      foreach ($data as $key => $value) {
        $user = Yii::app()->db->createCommand()
        ->select('id,name')
        ->from('movie_album')
        ->where("catalog = {$value['id']}")
        ->queryall();

       
        $data[$key]['album']=$user;
        
      }
      
      foreach ($data as $key => $value) {
        
            $nowab =array();
            if(count($value['album'])>0){
            foreach ($value['album'] as $key2 => $value2) {

                array_push($nowab, $value2['id']);
            }

                foreach ($data as $key => $value) {
                    $user = Yii::app()->db->createCommand()
                        ->select('id,name')
                        ->from('movie_album')
                        ->where("catalog = {$value['id']}")
                        ->queryall();


                    $data[$key]['album']=$user;



                //var_dump($user);
                //$data[$key]['view'] = $user[0]["count(id)"];
                }
            }
      }
      //var_dump($data);
      return $data;

        foreach ($data as $key => $value) {


            $nowab =array();
            if(count($value['album'])>0){
                foreach ($value['album'] as $key2 => $value2) {

                    array_push($nowab, $value2['id']);
                }

                $output=implode(",",$nowab);
                $user = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from('movie_info')
                    ->where("album in($output)")
                    ->queryall();

                $nownm = array();
                foreach ($user as $key3 => $value3) {
                    array_push($nownm, $value3['id']);
                }
                $outnownm=implode(",",$nownm);

                $user = Yii::app()->db->createCommand()
                    ->select('count(id)')
                    ->from('movie_view')
                    ->where("movie in($outnownm)")
                    ->queryall();

                //var_dump($user);
                $data[$key]['view'] = $user[0]["count(id)"];
            }
        }
        //var_dump($data);
        return $data;
    }

    public function stoptime(){

        $data =[];

        $user = Yii::app()->db->createCommand()
            ->select('count(id)')
            ->from('movie_stop')
            ->where("stop_time > '00:00:00'")
            ->andwhere("stop_time < '00:10:00'")
            ->queryall();

        array_push($data, $user[0]["count(id)"]);

        $user = Yii::app()->db->createCommand()
            ->select('count(id)')
            ->from('movie_stop')
            ->where("stop_time > '00:10:00'")
            ->andwhere("stop_time < '00:20:00'")
            ->queryall();

        array_push($data, $user[0]["count(id)"]);
        $user = Yii::app()->db->createCommand()
            ->select('count(id)')
            ->from('movie_stop')
            ->where("stop_time > '00:20:00'")
            ->andwhere("stop_time < '00:30:00'")
            ->queryall();

        array_push($data, $user[0]["count(id)"]);
        $user = Yii::app()->db->createCommand()
            ->select('count(id)')
            ->from('movie_stop')
            ->where("stop_time > '00:30:00'")
            ->andwhere("stop_time < '00:40:00'")
            ->queryall();

        array_push($data, $user[0]["count(id)"]);

        $user = Yii::app()->db->createCommand()
            ->select('count(id)')
            ->from('movie_stop')
            ->where("stop_time > '00:40:00'")
            ->andwhere("stop_time < '00:50:00'")
            ->queryall();

        array_push($data, $user[0]["count(id)"]);

        $user = Yii::app()->db->createCommand()
            ->select('count(id)')
            ->from('movie_stop')
            ->where("stop_time > '00:50:00'")
            ->andwhere("stop_time < '00:60:00'")
            ->queryall();

        array_push($data, $user[0]["count(id)"]);

        return $data;

    }
    public function paychk($ono){

        $Preorder = Preorder::model()->find('tra_num=:tra_num', array(':tra_num'=>$ono));
        $Preorder->status = 1;
        /*
        if (!$Preorder->validate()) {
            return $Preorder;
        }*/

        if( $Preorder->update()==true){
            return true;
        }else{
            return false;
        }

    }
    public function getod($ono){
        $Preorder = Preorder::model()->find('tra_num=:tra_num', array(':tra_num'=>$ono));
        return $Preorder;
    }

    public function getnum(){
        $results = Preorder::model()->findAll();
        return count($results);
    }


    public function update(array $inputs)
    {
        $model = Mvactor::model()->findByPk($inputs['id']);

        if ($model === null) {
            $model = new Mvactor();
            $model->addError('pk_not_found', '系統主鍵不存在');
            return $model;
        }

        $model->name = $inputs['name'];
        $model->tag  = $inputs['tag'];
        $model->sex = $inputs['sex'];
        $model->age = $inputs['age'];
        $model->status = $inputs['status'];
        $model->des = $inputs['des'];

        $upload_thumbnail = $inputs['thumbnail'];
        if($upload_thumbnail['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_thumbnail['name']);
            $ext = end($tmp);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            move_uploaded_file($upload_thumbnail['tmp_name'],$image_path);
            //ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $model->thumbnail = $image_show_path;
        }else{
            $model->thumbnail = $inputs["thumbnail_old"];
        }

        $img1 = $inputs['img1'];
        if($img1['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$img1['name']);
            $ext = end($tmp);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            move_uploaded_file($img1['tmp_name'],$image_path);
            //ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $model->img1 = $image_show_path;
        }else{
            $model->img1 = $inputs["img1_old"];
        }

        $img2 = $inputs['img2'];
        if($img2['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$img2['name']);
            $ext = end($tmp);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            move_uploaded_file($img2['tmp_name'],$image_path);
            //ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $model->img2 = $image_show_path;
        }else{
            $model->img2 = $inputs["img2_old"];
        }

        $img3 = $inputs['img3'];
        if($img3['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$img3['name']);
            $ext = end($tmp);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            move_uploaded_file($img3['tmp_name'],$image_path);
            //ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $model->img3 = $image_show_path;
        }else{
            $model->img3 = $inputs["img3_old"];
        }

        if ($model->validate()) {
            $model->update();
            return $model;
        }else{
            $model->addError('update_failed', '演員更新時驗證失敗');
            return $model;
        }

        if ($model === false) {
            $model->addError('update_failed', '系統更新失敗');
            return $model;
        }


    }

    public function create(array $inputs)
    {
        $model = new Mvactor();

        $model->tag = $inputs['tag'];
        $model->name = $inputs['name'];
        $model->des = $inputs['des'];
        $model->sex = $inputs['sex'];
        $model->age = $inputs['age'];

        $upload_thumbnail = $inputs['thumbnail'];
        if($upload_thumbnail['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_thumbnail['name']);
            $ext = end($tmp);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            move_uploaded_file($upload_thumbnail['tmp_name'],$image_path);
            //ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $model->thumbnail = $image_show_path;
        }

        $img1 = $inputs['img1'];
        if($img1['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$img1['name']);
            $ext = end($tmp);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            move_uploaded_file($img1['tmp_name'],$image_path);
            //ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $model->img1 = $image_show_path;
        }else{
            $model->img1 = '';
        }

        $img2 = $inputs['img2'];
        if($img2['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$img2['name']);
            $ext = end($tmp);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            move_uploaded_file($img2['tmp_name'],$image_path);
            //ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $model->img2 = $image_show_path;
        }else{
            $model->img2 = '';
        }

        $img3 = $inputs['img3'];
        if($img3['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$img3['name']);
            $ext = end($tmp);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            move_uploaded_file($img3['tmp_name'],$image_path);
            //ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $model->img3 = $image_show_path;
        }else{
            $model->img3 = '';
        }

        $model->status = $inputs["status"];
        $model->create_date = date("Y-m-d H:i:s");
        $model->edit_date = '0000-00-00 00:00:00';

        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            $success = $model->save();
        }

        if ($success === false) {
            $model->addError('save_fail', '新增演員失敗');
            return $model;
        }

        return $model;





    }


}
