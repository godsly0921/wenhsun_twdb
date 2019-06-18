<?php
class CouponService
{
    public function findAllCoupon(){
        $accountService = new AccountService();
        $coupon_data = array();
        $all_coupon = Coupon::model()->findAll();
        if($all_coupon){
            foreach ($all_coupon as $key => $value) {
                $coupon_data[] = array(
                    'coupon_id' => $value['coupon_id'],
                    'coupon_name' => $value['coupon_name'],
                    'coupon_code' => $value['coupon_code'],
                    'coupon_pic' => $value['coupon_pic'],
                    'start_time' => $value['start_time'],
                    'end_time' => $value['end_time'],
                    'status' => $value['status'] == 1 ? '是' : '否'
                );
            }
        }
        return $coupon_data;
    }

    public function findAllCouponWithStatus( $status ){
        $accountService = new AccountService();
        $coupon_data = array();
        $all_coupon = Coupon::model()->findAll(array(
            'condition'=>'status=:status',
            'params'=>array(
                ':status' => $status,
            )
        ));
        if($all_coupon){
            foreach ($all_coupon as $key => $value) {
                $coupon_data[] = array(
                    'coupon_id' => $value['coupon_id'],
                    'coupon_name' => $value['coupon_name'],
                    'coupon_code' => $value['coupon_code'],
                    'coupon_pic' => $value['coupon_pic'],
                    'start_time' => $value['start_time'],
                    'end_time' => $value['end_time'],
                    'status' => $value['status'] == 1 ? '是' : '否'
                );
            }
        }
        return $coupon_data;
    }
    /**
     * @param array $input
     * @return Power
     */
    public function create(array $input)
    {
        $operationlogService = new OperationlogService();
        $model = new Coupon();
        $model->coupon_name = $input['coupon_name'];
        $model->coupon_code = $input['coupon_code'];
        $model->coupon_pic = $input['coupon_pic'];
        $model->start_time = $input['start_time'] . " 00:00:00";
        $model->end_time = $input['end_time'] . " 23:59:59";
        $model->status = $input['status'];
        $model->create_time = date('Y-m-d H:i:s');
        $model->create_account_id = Yii::app()->session['uid'];
        if (!$model->validate()) {
            return $model;
        }
        if (!$model->hasErrors()) {
            if( $model->save() ){
                $motion = "建立優惠";
                $log = "建立 產品名稱 = " . $inputs["coupon_name"];
                $operationlogService->create_operationlog( $motion, $log );
                return array(true,'新增成功');         
            }else{       
                $motion = "建立優惠";
                $log = "建立 產品名稱 = " . $inputs["coupon_name"];
                $operationlogService->create_operationlog( $motion, $log, 0 );
                return array(false,$model->getErrors());
            }
        }
        
        return $model;
    }


    public function update(array $input)
    {
        $operationlogService = new OperationlogService();
        $model = Coupon::model()->findByPk($input['id']);
        $model->coupon_name = $input['coupon_name'];
        $model->coupon_code = $input['coupon_code'];
        $model->coupon_pic = $input['coupon_pic'];
        $model->start_time = $input['start_time'] . " 00:00:00";
        $model->end_time = $input['end_time'] . " 23:59:59";
        $model->status = $input['status'];
        $model->update_time = date('Y-m-d H:i:s');
        $model->update_account_id = Yii::app()->session['uid'];
        if (!$model->validate()) {
            return $model;
        }
        if (!$model->hasErrors()) {
            if( $model->save() ){
                $motion = "更新優惠";
                $log = "更新 優惠編號 = " . $id . "；優惠名稱 = " . $inputs["coupon_name"];
                $operationlogService->create_operationlog( $motion, $log );
                return array(true,'修改成功',$model);         
            }else{       
                $motion = "更新優惠";
                $log = "更新 優惠編號 = " . $id . "；優惠名稱 = " . $inputs["coupon_name"];
                $operationlogService->create_operationlog( $motion, $log, 0 );
                return array(false,$model->getErrors());
            }
        }      
        return $model;
    }

    public function delete($id){
        $operationlogService = new OperationlogService();
        $post = Coupon::model()->findByPk( $id );
        if($post->delete()){
            $motion = "刪除優惠";
            $log = "刪除 優惠編號 = " . $id;
            $operationlogService->create_operationlog( $motion, $log );
            return array(true,'刪除成功');
        }else{
            $motion = "刪除優惠";
            $log = "刪除 優惠編號 = " . $id;
            $operationlogService->create_operationlog( $motion, $log, 0 );
            return array(false,$post->getErrors());
        }
    }
    public function findById($id)
    {
        $model = Coupon::model()->findByPk($id);
        return $model;
    }
}