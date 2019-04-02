<?php

class UsergrpService{   
  
  // 抓出所有分類
  public function getall(){
    
    return ( Usergrp::model()->findAll() );

  }

  // 抓出所有第一層分類
  public function getLevelOneAll(){

    $result =Usergrp::model()->findAll(
        array('order' => 'create_date ASC',
            'condition'=>"isroot=:isroot",
            'params'=>[':isroot'=>'1'],
        ));

    if(count($result)>0){
      return $result;
    }else{
      return false;
    }

  }

  // 抓出所有第二層分類
  public function getLevelTwoAll(){

    $result =Usergrp::model()->findAll(
        array('order' => 'create_date ASC',
            'condition'=>"isroot=:isroot and layer=:layer",
            'params'=>[':layer'=>'2',':isroot'=>'0'],
        ));

    if(count($result)>0){
      return $result;
    }else{
      return false;
    }

  }

  // 新增分類
  public function create( $name , $parents ){
    
    // 推算層數
    $temp_parents = 1;
    
    if( $parents!= 0 ){

      $tmpres = Usergrp::model()->findbyPk($parents);

      if( count($tmpres) > 0 ){

        $temp_parents = ( $tmpres->layer+1 );

      }else{
        
        return array(false,'父分類有誤,請重新確認');
      }

    } 
    
    $post = new Usergrp;
    $post->name     = $name;
    
    if( $parents == 0){
      $post->isroot = 1;
    }else{
      $post->isroot = 0;
    }
    $post->parents  = $parents;
    $post->builder  = Yii::app()->session['uid'];
    $post->sort     = 0;
    $post->layer    = $temp_parents;
    $post->create_date    = date("Y-m-d H:i:s");
    $post->edit_date      = date("Y-m-d H:i:s");
    if( $post->save() ){

      return array(true,'類別新增成功');

    }else{

      return array(false,'新增過程發生錯誤,請稍後再試');
    }

  }
  
  // 抓出單一分類資料
  public function get_one_class( $id ){

    return( Usergrp::model()->findbyPk($id) );

  }
  
  // 更新
  public function update( $name , $parents , $cid ){
    
    $post            = Usergrp::model()->findByPk( $cid );
    $post->name      = $name;
    $post->parents   = $parents;
    $post->edit_date = date("Y-m-d H:i:s");
    if( $post->save() ){

      return array(true,'類別更新成功');

    }else{
    
      return array(false,'類別更新過程發生錯誤,請稍後再試');
    }

  }

  // 刪除
  public function del( $id ){

    $post = Usergrp::model()->findByPk( $id ); 
    $post->delete();
    
  }

  // 找出子分類
  public function getchild( $grp_father ){
      
      $criteria=new CDbCriteria();
      $criteria->addCondition("parents = $grp_father");
      $data = Usergrp::model()->findAll($criteria);
      return $data;
  }

  // 找出會員的分類群
  public function get_grp_lv( $grp_id ){

      $nowgrp  = Usergrp::model()->findByPk( $grp_id ); 
      $parents = $nowgrp->parents;
      $data    = $this->getchild( $parents );
      return $data;

  }

  // 整理群組分類
  public function store_user_grp(){
    $sql = "SELECT a.id as grp1_id,a.name as grp1_name,b.id as grp2_id,b.name as grp2_name,m.id as professor_id,m.name as professor_name FROM `user_grp` a join user_grp b on a.id = b.parents left join member m on a.id = m.grp_lv1 and b.id=grp_lv2 where m.professor=0";
    $data = Yii::app()->db->createCommand($sql)->queryAll();
    $result = array();
    $professor = array();
    foreach ($data as $key => $value) {
      if(!isset($result[$value['grp1_id']])){
        $result[$value['grp1_id']] = array();
        $result[$value['grp1_id']]['name'] = $value['grp1_name'];
      }
      if(!isset($result[$value['grp1_id']]['grp2'])){
        $result[$value['grp1_id']]['grp2'] = array();       
      }
      if(!isset($result[$value['grp1_id']]['grp2'][$value['grp2_id']])){
        $result[$value['grp1_id']]['grp2'][$value['grp2_id']] = array();
        $result[$value['grp1_id']]['grp2'][$value['grp2_id']] = array('grp2_name'=>$value['grp2_name']);
      }
      if(!isset($result[$value['grp1_id']]['grp2'][$value['grp2_id']]['professor'])){
        $result[$value['grp1_id']]['grp2'][$value['grp2_id']]['professor'] = array();
        $result[$value['grp1_id']]['grp2'][$value['grp2_id']]= array('grp2_name'=>$value['grp2_name']);
      }
      $result[$value['grp1_id']]['grp2'][$value['grp2_id']]['professor'][] = array('professor_id' => $value['professor_id'],'professor_name'=>$value['professor_name']);
    }
    return $result;
  }
}
?>