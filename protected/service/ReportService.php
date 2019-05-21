<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class ReportService
{
    public function countEachdayUpload(){
        $sql = "select count(*) as each_day_count,DATE_FORMAT(create_time,'%Y-%m-%d') as create_day from single group by DATE_FORMAT(create_time,'%Y-%m-%d')";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $date = array();
        foreach ($result as $key => $value) {
            $date[] = array(strtotime($value['create_day']." 00:00:00"),(int)$value['each_day_count']);
        }
        return json_encode($date);
    }

    public function update( $input ){
        $model = Product::model()->findByPk($input['product_id']);
        $model->product_name = $input['product_name'];
        $model->coupon_id = $input['coupon_id'];
        $model->pic_point = $input['pic_point'];
        $model->product_type = $input['product_type'];
        $model->pic_number = $input['pic_number'];
        $model->price = $input['price'];
        $model->status = $input['status'];
        $model->update_time = date('Y-m-d H:i:s');
        $model->update_account_id = Yii::app()->session['uid'];
        // var_dump($model->getErrors());exit();
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                return array(true,'修改成功',$model);         
            }else{       
                return array(false,$model->getErrors());
            }
        }
        
        return $model;
    }

    public function create( $input ){
        $model = new Product();
        $model->product_name = $input['product_name'];
        $model->coupon_id = $input['coupon_id'];
        $model->pic_point = $input['pic_point'];
        $model->product_type = $input['product_type'];
        $model->pic_number = $input['pic_number'];
        $model->price = $input['price'];
        $model->status = $input['status'];
        $model->create_time = date('Y-m-d H:i:s');
        $model->create_account_id = Yii::app()->session['uid'];
        // var_dump($model->getErrors());exit();
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
        $model = Product::model()->findByPk($id);
        return $model;
    }

}