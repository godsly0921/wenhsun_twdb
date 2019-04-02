<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class CategoryService
{
    public function findCategorys()
    {
        $model = Category::model()->findAll();

        if(count($model)!=0){
            return $model;
        }else{
            return false;
        }

    }

    public function findCategorysPages($limit,$offset)
    {
        $Criteria = new CDbCriteria();
        $Criteria->offset = $offset;
        $Criteria->limit = $limit;
        $model = Category::model()->findAll($Criteria);
        return $model;

    }

    public function create(array $inputs)
    {
        $model = new Category();
        $model->name = $inputs["name"];
        $model->sort = $inputs["sort"];

        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            $model->save();
        }

        return $model;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateCategory(array $inputs)
    {
        $model = Category::model()->findByPk($inputs["id"]);

        $model->id = $inputs["id"];
        $model->name = $inputs["name"];
        $model->sort = $inputs["sort"];
        $upload_image = $inputs['image'];
        if($upload_image['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'],IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            ImageResize::resize($image_path,$image_path,500,500,100);
            $image_show_path = IMAGES_SHOW_DIR.$uuid_name.'.'.$ext;
            $model->image = $image_show_path;
        }

        if ($model->validate()) {
            $model->update();
        }

        return $model;
    }

    public function findByLang($lang)
    {
        $model = Category::model()->findAllByAttributes(
            ['language' => $lang],
            ['order' => 'create_time DESC']
        );

        return $model;
    }

    public function findByCategory($category)
    {
        $model = Category::model()->findAllByAttributes(
            ['category' => $category],
            ['order' => 'create_time DESC']
        );

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