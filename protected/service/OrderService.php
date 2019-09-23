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
        $sql = "SELECT o.order_id,p.product_name,oi.cost_total,o.order_status,o.order_datetime,oi.single_id,oi.size_type FROM `orders` o JOIN orders_item oi on o.order_id=oi.order_id LEFT JOIN product p on oi.product_id=p.product_id";
        $all_order = Yii::app()->db->createCommand($sql)->queryAll();
        if($all_order){
            foreach ($all_order as $key => $value) {
                $product_name = '';
                if($value['product_name']!=''){
                    $product_name = $value['product_name'];
                }else{
                    $product_name = "單圖：" . $value['single_id'] . "；尺寸：" . $value['size_type'];
                }
                $order_data[] = array(
                    'order_id' => $value['order_id'],
                    'product_name' => $product_name,
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

    public function findOrderNo(){
        $date = date('Ymd');
        $cnt = Orders::model()->findAllBySql("select * from orders where order_id like 'P" . $date . "%' order by order_id desc");
        return $cnt;
    }
    public function chanage_pay_status($order_id){
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $order = Orders::model()->findByPk($order_id);
            $order->order_status = 2;
            $order->receive_date = date("Y-m-d H:i:s");
            if($order->save()){
                Yii::log(date('Y-m-d H:i:s') . " order_id => " . $order_id. " order_status update success", CLogger::LEVEL_INFO);
            }else{
                Yii::log(date('Y-m-d H:i:s') . " order_id => " . $order_id. " order_status update fail", CLogger::LEVEL_INFO);
                throw new Exception(date('Y-m-d H:i:s') . " order_id => " . $order_id. "訂單狀態更新失敗");
                return array(false,"0|");
            }
            $transaction->commit();
            return array(true,"order_status update success");
        }catch (Exception $e) {
            $transaction->rollback();
            Yii::log(date('Y-m-d H:i:s') . " order open fail. Message =>" . $e->getMessage(), CLogger::LEVEL_INFO);
            return array(false,"訂單狀態更新失敗,請稍後再試");
        }   
    }
    public function open_order_plan($order_id){
        $datetime_now = date('Y-m-d H:i:s');
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $order = Orders::model()->findByPk($order_id);
            $order_item = Ordersitem::model()->findByAttributes([
                'order_id' => $order_id
            ]);
            $productService = new ProductService();
            $product_data = $productService->findById($order_item->product_id);
            if($order_item->order_category == 1){
                $member = Member::model()->findByPk($order->member_id);
                $member->active_point = $member->active_point + $product_data->pic_point;
                if($member->save()){
                    Yii::log(date('Y-m-d H:i:s') . " order_id => " . $order_id. " member point add success", CLogger::LEVEL_INFO);
                }else{
                    Yii::log(date('Y-m-d H:i:s') . " order_id => " . $order_id. " member point add fail", CLogger::LEVEL_INFO);
                    throw new Exception(date('Y-m-d H:i:s') . " order_id => " . $order_id. "會員點數更新失敗");
                    return array(false,"0|");
                }
            }
            if($order_item->order_category == 2 || $order_item->order_category == 3 || $order_item->order_category == 4 ){
                if( $order_item->order_category == 2 ) $period = 1;
                if( $order_item->order_category == 3 ) $period = 3;
                if( $order_item->order_category == 4 ) $period = 12;
                for ($i=1; $i <= $period; $i++) { 
                    if($i ==1)
                        $date_start = date('Y-m-d H:i:s',strtotime('+0 day'));
                    else
                        $date_start = $date_end;
                    $date_end = date('Y-m-d H:i:s',strtotime('+30 day',strtotime($date_start)));
                    $member_plan = new Memberplan();
                    $member_plan->member_id = $order->member_id;
                    $member_plan->order_item_id = $order_item->orders_item_id;
                    $member_plan->date_start = $date_start;
                    $member_plan->date_end = $date_end;
                    $member_plan->amount = $product_data->pic_number;
                    $member_plan->remain_amount = $product_data->pic_number;
                    $member_plan->status = 1;
                    if($member_plan->save()){
                        Yii::log(date('Y-m-d H:i:s') . " order_id => " . $order_id. " member plan add success", CLogger::LEVEL_INFO);
                    }else{
                        var_dump($member_plan);exit();
                        Yii::log(date('Y-m-d H:i:s') . " order_id => " . $order_id. " member plan add fail", CLogger::LEVEL_INFO);
                        throw new Exception(date('Y-m-d H:i:s') . " order_id => " . $order_id. "會員方案更新失敗");
                        return array(false,"0|");
                    }
                }

            }
            $order_item->order_detail_status = 1;
            if($order_item->save()){
                Yii::log(date('Y-m-d H:i:s') . " order_id => " . $order_id. " order item update success", CLogger::LEVEL_INFO);
            }else{
                var_dump($order_item);exit();
                Yii::log(date('Y-m-d H:i:s') . " order_id => " . $order_id. " order item update fail", CLogger::LEVEL_INFO);
                throw new Exception(date('Y-m-d H:i:s') . " order_id => " . $order_id. "訂單詳細狀態更新失敗");
                return array(false,"0|");
            }
            $order->order_status = 3;
            $order->receive_date = date("Y-m-d H:i:s");
            if($order->save()){
                Yii::log(date('Y-m-d H:i:s') . " order_id => " . $order_id. " order_status update success", CLogger::LEVEL_INFO);
            }else{
                Yii::log(date('Y-m-d H:i:s') . " order_id => " . $order_id. " order_status update fail", CLogger::LEVEL_INFO);
                throw new Exception(date('Y-m-d H:i:s') . " order_id => " . $order_id. "訂單狀態更新失敗");
                return array(false,"0|");
            }
            $transaction->commit();
            return array(true,"訂單開通成功");
        }catch (Exception $e) {
            $transaction->rollback();
            Yii::log(date('Y-m-d H:i:s') . " order open fail. Message =>" . $e->getMessage(), CLogger::LEVEL_INFO);
            return array(false,"訂單開通失誤,請稍後再試");
        } 
    }
    public function create_order($inputs){
        $datetime_now = date('Y-m-d H:i:s');
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $date = date('Ymd');
            $cnt = $this->findOrderNo();
            $order_no_Count = count($cnt) + 1;                        
            $order_no = "P" . $date;
            if (count($cnt) == 0) {
                $order_no .= str_pad(($order_no_Count), 4, '0', STR_PAD_LEFT);
            } else {
                $latestAuthorization_no = substr($cnt[0]->order_id, -4);
                $orderCount = (int) $latestAuthorization_no + 1;
                $order_no .= str_pad(($orderCount), 4, '0', STR_PAD_LEFT);
            }
            $order_data = array();
            $order_data['order_id'] = $order_no;
            $order_data['product_id'] = $inputs['product_id'];
            $order_data['member_id'] = Yii::app()->session['member_id'];
            $order_data['order_datetime'] = $datetime_now;
            $order_data['order_status'] = 1;
            //$order_data['name'] = $inputs['name'];
            $order_data['mobile'] = $inputs['mobile'];
            //$order_data['email'] = $inputs['email'];
            $order_data['nationality'] = $inputs['nationality'];
            $order_data['country'] = $inputs['county'];
            $order_data['town'] = $inputs['town'];
            $order_data['codezip'] = $inputs['zipcode'];
            $order_data['address'] = $inputs['address'];
            $order_data['invoice_category'] = $inputs['invoice_category'];
            $order_data['invoice_number'] = $inputs['invoice_number'];
            $order_data['invoice_title'] = $inputs['invoice_title'];
            $order_data['cost_total'] = $inputs['cost_total'];
            $order_data['order_category'] = $inputs['order_category'];
            $order_data['order_detail_status'] = $inputs['order_detail_status'];
            $order_data['product_name'] = $inputs['product_name'];
            $order = new Orders();
            $order->order_id = $order_data['order_id'];
            $order->member_id = $order_data['member_id'];
            $order->order_datetime = $order_data['order_datetime'];
            $order->order_status = $order_data['order_status'];
            //$order->name = $order_data['name'];
            $order->mobile = $order_data['mobile'];
            //$order->email = $order_data['email'];
            $order->nationality = $order_data['nationality'];
            $order->country = $order_data['country'];
            $order->town = $order_data['town'];
            $order->codezip = $order_data['codezip'];
            $order->address = $order_data['address'];
            $order->invoice_category = $order_data['invoice_category'];
            $order->invoice_number = $order_data['invoice_number'];
            $order->invoice_title = $order_data['invoice_title'];
            if (!$order->save() ) {
                Yii::log(date('Y-m-d H:i:s') . " order create fail", CLogger::LEVEL_INFO);
                throw new Exception(date('Y-m-d H:i:s') . " order create fail");
            }
            
            $Ordersitem = new Ordersitem();
            $Ordersitem->product_id = $order_data['product_id'];
            $Ordersitem->order_id = $order_data['order_id'];
            $Ordersitem->cost_total = $order_data['cost_total'];
            $Ordersitem->order_category = $order_data['order_category'];
            $Ordersitem->order_detail_status = $order_data['order_detail_status'];
            if (!$Ordersitem->save() ) {
                Yii::log(date('Y-m-d H:i:s') . " Ordersitem create fail", CLogger::LEVEL_INFO);
                throw new Exception(date('Y-m-d H:i:s') . " Ordersitem create fail");
            }
            $transaction->commit();
            Yii::app()->session['order'] = $order_data;
            return array(true,"訂單記錄新增成功");
        }catch (Exception $e) {
            $transaction->rollback();
            Yii::log(date('Y-m-d H:i:s') . " orders create fail and Ordersitem create fail. Message =>" . $e->getMessage(), CLogger::LEVEL_INFO);
            return array(false,"訂單記錄新增失誤,請稍後再試");
        } 
    }

    public function findMemberOrderPoint(){
        $sql = "SELECT oi.*,p.pic_point FROM `orders` o JOIN orders_item oi on o.order_id=oi.order_id JOIN product p on oi.product_id=p.product_id where member_id=" . Yii::app()->session['member_id'] . " and oi.order_category=1 order by o.order_id asc";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        return $data;
    }
    public function findMemberOrderData($member_id,$status){
        $sql = "SELECT * FROM `orders` o JOIN orders_item oi on o.order_id=oi.order_id JOIN product p on oi.product_id=p.product_id where member_id=" . Yii::app()->session['member_id'] . " and o.order_status=" . $status . " order by o.order_id asc";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        $result = array();
        foreach ($data as $key => $value) {
            if( $value['product_type'] == 1 ){
                $product_name = $value['product_name'] . $this::$product_type[$value['product_type']] . ' ( ' . $value['pic_point'] . ' 點 )';
            }else{
                $product_name = $value['product_name'] .$this::$product_type[$value['product_type']] . ' ( ' . $value['pic_number'] . ' 張 )';
            }
            $result[] = array(
                "order_id" => $value['order_id'],
                "product_id" => $value['product_id'],
                "product_name" => $product_name,
                "product_type" => $value['product_type'],
                "cost_total" => $value['cost_total'],
            );
        }
        return $result;
    }

    public function findById($id)
    {
        $model = Product::model()->findByPk($id);
        return $model;
    }

}