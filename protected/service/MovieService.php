<?php

class MovieService
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

    public function insert(){

    }

    public function updateDate(array $inputs)
    {

        $eivdate = Eivdate::model()->findByPk($inputs["id"]);
        $eivdate->id = $inputs['id'];
        $eivdate->start = $inputs['start'];
        $eivdate->end = $inputs['end'];

        if (!$eivdate->validate()) {
            return $eivdate;
        }

        $eivdate->update();

        return $eivdate;
    }

    public function updateLove(array $love)
    {
        foreach($love as $key){
            $eivlove = Eivlove::model()->findByPk($key["id"]);
            $eivlove->id = $key['id'];
            $eivlove->name  = $key['name'];
            $eivlove->full_name = $key['full_name'];
            $eivlove->code = $key['code'];
            $eivlove->unified_number = $key['unified_number'];

            if (!$eivlove->validate()) {
                return $eivlove;
            }
            $eivlove->update();

        }
        return $eivlove;
    }
    public function donated($user)
    {

        $eivinfo = Eivdonate::model()->find('donate_u=:user', array(':user'=>$user));

        return count($eivinfo);
        /*if (!$eivinfo->validate()) {
            return $eivinfo;
        }
        $eivinfo->update();*/


        //return $eivinfo;
    }

    public function update(array $inputs)
    {
        $model = Mvinfo::model()->findByPk($inputs['id']);

        if ($model === null) {
            $model = new Mvinfo();
            $model->addError('pk_not_found', '系統主鍵不存在');
            return $model;
        }

        $model->name = $inputs['name'];
        $model->sort  = $inputs['sort'];
        $model->actor = $inputs['actor'];
        $model->status = $inputs['status'];
        $model->move_length = $inputs['move_length'];
        $model->move_size = $inputs['move_size'];
        $model->album = $inputs['album'];
        $model->scenes = $inputs['scenes'];

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

        $upload_gray_thumbnail = $inputs['gray_thumbnail'];
        if($upload_gray_thumbnail['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_gray_thumbnail['name']);
            $ext = end($tmp);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            move_uploaded_file($upload_gray_thumbnail['tmp_name'],$image_path);
            //ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $model->gray_thumbnail = $image_show_path;
        }else{
            $model->gray_thumbnail = $inputs["gray_thumbnail_old"];
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
            $model->addError('update_failed', '影片更新時驗證失敗');
            return $model;
        }

        if ($model === false) {
            $model->addError('update_failed', '系統更新失敗');
            return $model;
        }


    }

    public function create(array $inputs)
    {
        $data = new Mvinfo();

        $data->name = $inputs["name"];
        $data->sort = $inputs["sort"];
        $data->status = $inputs["status"];
        $data->move_length = $inputs["move_length"];
        $data->move_size = $inputs["move_size"];
        $data->move_view = 0;
        $data->album = $inputs["album"];
        $data->actor = $inputs["actor"];
        $data->scenes = $inputs["scenes"];
        $data->url = $inputs["url"];
        $data->thumbnail = $inputs["thumbnail"];
        $data->create_date = date("Y-m-d H:i:s");
        $data->edit_date = '0000-00-00 00:00:00';

        $upload_image = $inputs['thumbnail'];
        if($upload_image['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'],IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            $image_show_path = IMAGES_SHOW_DIR.$uuid_name.'.'.$ext;
            $data->thumbnail = $image_show_path;
        }

        if (!$data->validate()) {
            return $data;
        }

        if (!$data->hasErrors()) {
            $data->save();
        }

        return $data;
    }


}
