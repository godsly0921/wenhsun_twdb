<?php
class ProfessorService{   
  
  // 抓出所有分類
  public function getall(){

    $res = Yii::app()->db->createCommand()
    ->select('p.*, u.name as uname,a.account_name as aname')
    ->from('professor p')
    ->join('user_grp  u', 'p.grp = u.id')
    ->join('account  a', 'p.builder = a.id')
    ->queryAll();
    return ($res);

  }
  
  // 新增教授
  public function create( $name , $parents ){
    
    $post = new Professor;
    $post->name     = $name;
    $post->builder  = Yii::app()->session['uid'];
    $post->grp      = $parents;
    $post->status   = 1;
    $post->create_date    = date("Y-m-d H:i:s");
    $post->edit_date      = date("Y-m-d H:i:s");

    if( $post->save() ){

      return array(true,'指導教授新增成功');

    }else{

      return array(false,'指導教授新增過程發生錯誤,請稍後再試');
    }

  }
  
  // 抓出單一分類資料
  public function get_one_professor( $id ){

    return( Professor::model()->findbyPk($id) );

  }
  
  // 更新
  public function update( $name , $parents , $cid ){
    
    $post            = Professor::model()->findByPk( $cid );
    $post->name      = $name;
    $post->grp       = $parents;
    $post->edit_date = date("Y-m-d H:i:s");
    if( $post->save() ){

      return array(true,'指導教授更新成功');

    }else{
    
      return array(false,'指導教授更新過程發生錯誤,請稍後再試');
    }

  }

  // 刪除
  public function del( $id ){

    $post = Professor::model()->findByPk( $id ); 
    $post->delete();
    
  }

  public function allprofessor(){

     return Professor::model()->findAll();
     
  }
}
?>