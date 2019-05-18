<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class OrderService
{
    public static $product_type = array(
        '1' => '點數',
        '2' => '自由載 30 天',
        '3' => '自由載 90 天',
        '4' => '自由載 360 天',
    );
    public static $order_status = array(
        '0' => '取消訂單',
        '1' => '未結帳',
        '2' => '已付款',
        '3' => '已開通',
        '4' => '已退款',
    );
    public static $pay_type = array(
        '1' => '信用卡',
        '2' => '超商繳款',
        '3' => '超商代碼',
        '4' => 'ATM 轉帳',
    );

    public function findAllOrders(){
        $order_data = array();
        $sql = "SELECT o.order_id,p.product_name,oi.cost_total,o.order_status,o.order_datetime FROM `orders` o JOIN orders_item oi on o.order_id=oi.order_id LEFT JOIN product p on oi.product_id=p.product_id";
        $all_order = Yii::app()->db->createCommand($sql)->queryAll();
        if($all_order){
            foreach ($all_order as $key => $value) {
                $order_data[] = array(
                    'order_id' => $value['order_id'],
                    'product_name' => $value['product_name'],
                    'cost_total' => $value['cost_total'],
                    'order_status' => OrderService::$order_status[$value['order_status']],
                    'creatorder_datetimee_time' => $value['order_datetime']
                );
            }
        }
        return $order_data;
    }

    public function getCompanyInfo(){
        $company_info = array(
            'name' => '文訊雜誌社',
            'address' => '10048 台北市中山南路11號B2',
            'phone' => '02-23433142',
            'email' => 'wenhsun7@ms19.hinet.net',
        );
        return $company_info;
    }

    public function getOrderMessage($order_id){
        $order_message = Ordermessage::model()->findAll(array(
            'condition'=>'order_id=:order_id',
            'params'=>array(
                ':order_id' => $order_id,
            ),
            'order' => 'create_time asc'
        ));
        return $order_message;
    }
    public function getImgDownload($orders_item_id){
        $sql = "SELECT i.*,s.description FROM `img_download` i JOIN single s on i.single_id = s.single_id where orders_item_id=".$orders_item_id;
        $img_download = Yii::app()->db->createCommand($sql)->queryAll();
        return $img_download;
    }
    public function getOrderInfo($order_id){
        $productService = new ProductService();
        $product = $order_data = $result = $order_message_data = array();
        $sql = "SELECT o.*,oi.*,m.* FROM `orders` o JOIN orders_item oi on o.order_id=oi.order_id JOIN member_address_book m on m.address_book_id=o.address_book_id where o.order_id = '" . $order_id . "'";
        $order_info = Yii::app()->db->createCommand($sql)->queryAll();
        $order_info = $order_info[0];
        $order_message = $this->getOrderMessage($order_id);
        $img_download = $this->getImgDownload($order_info['orders_item_id']);
        if($order_message){
            foreach ($order_message as $key => $value) {
                $order_message_data[] = $value['message'];
            }
        }
        
        $member_address_book = array(
            'name' => $order_info['name'],
            'address' => $order_info['address'],
            'mobile' => $order_info['mobile'],
            'email' => $order_info['email'],
            'invoice_number' => $order_info['invoice_number'],
        );
        if($order_info['product_id'] != ''){
            $product = $productService->findById($order_info['product_id']);
            $order_item = array(
                'product_id' => $product->product_id ,
                'product_name' => $product->product_name,
                'cost_total' => $order_info['cost_total'],
                'discount' => $order_info['discount'],               
            );
        }else{
            $order_item = array(
                'single_id' => $order_info['single_id '],
                'size_type' => $order_info['size_type'],
                'cost_total' => $order_info['cost_total'],
                'discount' => $order_info['discount'], 
            );
        }
        
        $order_data = array(
            'order_id' => $order_info['order_id'],
            'order_datetime' => $order_info['order_datetime'],
            'pay_type' => $order_info['pay_type'],
            'order_category' => $order_info['order_category'],
            'cost_total' => $order_info['cost_total'],
            'discount' => $order_info['discount'],
            'tax' => round($order_info['cost_total'] * 0.05),
            'no_tax_cost_total' => $order_info['cost_total'] - round($order_info['cost_total'] * 0.05)
        );

        $result = array(
            'member_address_book' => $member_address_book,
            'order_item' => $order_item,
            'order_data' => $order_data,
            'pay_type' => self::$pay_type,
            'order_message_data' => $order_message_data,
            'img_download' => $img_download
        );

        return $result;
    }

    public function create_order_message($message,$order_id){
        $model = new Ordermessage();
        $model->order_id = $order_id;
        $model->message = $message;
        $model->create_time = date('Y-m-d H:i:s');
        $model->reply_account_id = Yii::app()->session['uid'];
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