<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class CategoryService
{
    public function findCategoryMate(){
        $sql = "SELECT b.category_id,a.name as parents_name,b.name as child_name from `category` a JOIN category b on a.category_id = b.parents order by b.sort";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        return $data;
    }

    public function findAllCategory(){
        $accountService = new AccountService();
        $category_data = $rootcategory_data = array();
        $rootcategory = $this->findRootCategorys();
        foreach ($rootcategory as $key => $value) {
            $rootcategory_data[$value->category_id] = $value->name;
        }
        $all_data = Category::model()->findAll();
        foreach ($all_data as $key => $value) {
            $account = $accountService -> findAccountData($value->builder);
            $root ='';
            if($value->isroot == 0){
                $root = isset($rootcategory_data[$value->parents])?$rootcategory_data[$value->parents]:'';
            }else{
                $root = $value->name;
            }
            $category_data[] = array(
                'category_id' => $value->category_id,
                'root' => $root,
                'name' => $value->isroot == 1 ?'':$value->name,
                'builder' => $account->account_name,
                'create_date' => $value->create_date,
                'layer' => $value->layer,
                'status' => $value->status == 1 ? '是' : '否',
            );
        }
        return $category_data;
    }

    public function findCategoryByParents($parents){
        $model = Category::model()->findAll(array(
            'condition'=>'parents=:parents',
            'params'=>array(
                ':parents' => $parents,
            )
        ));
        if(count($model)!=0){
            return $model;
        }else{
            return false;
        }
    }

    public function findRootCategorys(){
        $model = Category::model()->findAll(array(
            'condition'=>'isroot=:isroot',
            'params'=>array(
                ':isroot' => 1,
            )
        ));
        if(count($model)!=0){
            return $model;
        }else{
            return false;
        }
    }

    public function create( $name, $parents, $sort, $status ){
        $operationlogService = new operationlogService();
        $model = new Category();
        $model->name = $name;    
        $model->isroot = $parents == 0 ? 1:0;
        $model->parents = $parents;
        $model->builder = Yii::app()->session['uid'];
        $model->sort = $sort;
        $model->layer = $parents == 0?1:2;
        $model->status = $status;
        $model->create_date = date('Y-m-d H:i:s');
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                $motion = "建立分類";
                $log = "建立 分類名稱 = " . $name;
                $operationlogService->create_operationlog( $motion, $log );
                return array(true,'新增成功');         
            }else{       
                $motion = "建立分類";
                $log = "建立 分類名稱 = " . $name;
                $operationlogService->create_operationlog( $motion, $log, 0 );
                return array(false,$model->getErrors());
            }
        }
        
        return $model;
    }

    public function update( $id, $name, $parents, $sort, $status ){
        $operationlogService = new operationlogService();
        $model = Category::model()->findByPk($id);
        $model->name = $name;    
        $model->isroot = $parents == 0 ? 1:0;
        $model->parents = $parents;
        $model->builder = Yii::app()->session['uid'];
        $model->sort = $sort;
        $model->layer = $parents == 0?1:2;
        $model->status = $status;
        $model->edit_date = date('Y-m-d H:i:s');
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                $motion = "更新分類";
                $log = "更新 分類名稱 = " . $name;
                $operationlogService->create_operationlog( $motion, $log );
                return array(true,'更新成功');         
            }else{       
                $motion = "更新分類";
                $log = "更新 分類編號 = " . $id . "；分類名稱 = " . $name;
                $operationlogService->create_operationlog( $motion, $log, 0 );
                return array(false,$model->getErrors());
            }
        }
        
        return $model;
    }

    public function findById($id){
        $model = Category::model()->findByPk($id);
        return $model;
    }

}