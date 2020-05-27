<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class ProductService
{
    public static $product_type = array(
        '1' => '點數',
        '2' => '自由載 30 天',
        '3' => '自由載 90 天',
        '4' => '自由載 360 天',
    );
    public function findProductWithStatus($product_type, $status){
        $result = Product::model()->findAll(array(
            'condition'=>'product_type=:product_type and status=:status',
            'params'=>array(
                ':product_type' => $product_type,
                ':status' => $status,
            )
        ));
        return $result;
    }

    public function findWithStatus($status){
        $result = Product::model()->findAll(array(
            'condition'=>'status=:status',
            'params'=>array(
                ':status' => $status
            )
        ));
        return $result;
    }

    public function findAllProduct(){
        $accountService = new AccountService();
        $product_data = array();
        $all_product = Product::model()->findAll();
        if($all_product){
            foreach ($all_product as $key => $value) {
                $product_name = '';
                if( $value['product_type'] == 1 ){
                    $product_name = ProductService::$product_type[$value['product_type']] . ' ( ' . $value['pic_point'] . ' 點 )';
                }else{
                    $product_name = ProductService::$product_type[$value['product_type']] . ' ( ' . $value['pic_number'] . ' 張 )';
                }
                $product_data[] = array(
                    'product_id' => $value['product_id'],
                    'product_name' => $value['product_name'] . $product_name,
                    'price' => $value['price'],
                    'status' => $value['status'] == 1 ? '是' : '否',
                    'create_time' => $value['create_time']
                );
            }
        }
        return $product_data;
    }

    public function update( $input ){
        $operationlogService = new OperationlogService();
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
                $motion = "更新產品";
                $log = "更新 產品編號 = " . $input['product_id'] . "；產品名稱 = " . $input["product_name"];
                $operationlogService->create_operationlog( $motion, $log );
                return array(true,'修改成功',$model);         
            }else{      
                $motion = "更新產品";
                $log = "更新 產品編號 = " . $input['product_id'] . "；產品名稱 = " . $input["product_name"];
                $operationlogService->create_operationlog( $motion, $log, 0 ); 
                return array(false,$model->getErrors());
            }
        }
        
        return $model;
    }

    public function create( $input ){
        $operationlogService = new OperationlogService();
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
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                $motion = "建立產品";
                $log = "建立 產品名稱 = " . $input["product_name"];
                $operationlogService->create_operationlog( $motion, $log );
                return array(true,'新增成功');         
            }else{       
                $motion = "建立產品";
                $log = "建立 產品名稱 = " . $input["product_name"];
                $operationlogService->create_operationlog( $motion, $log, 0 );
                return array(false,$model->getErrors());
            }
        }
        
        return $model;
    }

    public function delete($id){
        $operationlogService = new OperationlogService();
        $post = Product::model()->findByPk( $id );
        if($post->delete()){
            $motion = "刪除產品";
            $log = "刪除 產品編號 = " . $id;
            $operationlogService->create_operationlog( $motion, $log );
            return array(true,'刪除成功');
        }else{
            $motion = "刪除產品";
            $log = "刪除 產品編號 = " . $id;
            $operationlogService->create_operationlog( $motion, $log, 0 );
            return array(false,$post->getErrors());
        }
    }

    public function findById($id)
    {
        $model = Product::model()->findByPk($id);
        return $model;
    }

}