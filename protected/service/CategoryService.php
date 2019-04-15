<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class CategoryService
{
    public function findAllCategory(){
        $accountService = new AccountService();
        $category_data = array();
        $rootcategory = $this->findRootCategorys();
        if($rootcategory){
            foreach ($rootcategory as $key => $value) {
                $account = $accountService -> findAccountData($value['builder']);
                $category_data[$value['category_id']]['info'] = array(
                    'name' => $value['name'],
                    'builder' => $account->account_name,
                    'create_date' => $value['create_date'],
                    'layer' => $value['layer'],
                    'status' => $value['status'] == 1 ? '是' : '否',
                );
                $child_category = $this -> findCategoryByParents($value['category_id']);
                if($child_category){
                    foreach ($child_category as $child_key => $child_value) {
                        $category_data[$value['category_id']]['child'][] = array(
                            'category_id' => $child_value['category_id'],
                            'name' => $child_value['name'],
                            'builder' => $account->account_name,
                            'create_date' => $child_value['create_date'],
                            'layer' => $child_value['layer'],
                            'status' => $child_value['status'] == 1 ? '是' : '否',
                        );
                    }
                }
            }
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
                return array(true,'新增成功');         
            }else{       
                return array(false,$model->getErrors());
            }
        }
        
        return $model;
    }

    public function findById($id)
    {
        $model = Category::model()->findByAttributes(
            ['id' => $id]
        );

        return $model;
    }

}