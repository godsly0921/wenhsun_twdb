<?php
class PhotographService{
	 public function findAllPhotograph(){
        $model = Single::model()->findAll();
        if(count($model)!=0){
            return $model;
        }else{
            return array();
        }
    }
}
?>