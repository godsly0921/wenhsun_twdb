<?php
class OrderController extends Controller{
    // layout
    public $layout = "//layouts/back_end";
    
    // 登入驗證
    protected function needLogin(): bool
    {
        return true;
    }

    public function Actionnew(){
        $product = array();
        $couponService = new CouponService();
        $productService = new ProductService();
        if( Yii::app()->request->isPostRequest ){
            if (!CsrfProtector::comparePost()) {
                $this->redirect('index');
            }
            $inputs = [];
            $inputs["product_name"] = filter_input(INPUT_POST, "product_name");
            $inputs["coupon_id"] = filter_input(INPUT_POST, "coupon_id");
            $inputs["pic_point"] = filter_input(INPUT_POST, "pic_point");
            $inputs["product_type"] = filter_input(INPUT_POST, "product_type");
            $inputs["pic_number"] = filter_input(INPUT_POST, "pic_number");
            $inputs["price"] = filter_input(INPUT_POST, "price");
            $inputs["status"] = filter_input(INPUT_POST, "status");
            $product_create = $productService -> create( $inputs );
            if( $product_create[0] === true ){
                Yii::app()->session['success_msg'] = $product_create[1];
            }else{
                Yii::app()->session['error_msg'] = $product_create[1];
            }
            $coupon = array();
            $this->redirect(array('product/list'));
        }else{
            $coupon = $couponService->findAllCouponWithStatus(1);
            $this->render('new',['coupon'=>$coupon]);
        }
    }

    public function Actionlist(){
        $orderService = new OrderService();
        $order_data = $orderService->findAllOrders();
        $this->render('list',['order_data'=>$order_data]);
    }

    public function Actiondetail($id){
        $orderService = new OrderService();
        $company_info = $orderService->getCompanyInfo();
        $order_info = $orderService->getOrderInfo($id);
        $this->render('detail',array('company_info' => $company_info,'order_info' => $order_info));
    }

    public function Actionreply_order_message(){
        $orderService = new OrderService();
        $order_message = $_POST['order_message'];
        $order_id = $_POST['order_id'];
        $create_order_message = $orderService->create_order_message($order_message,$order_id);
       if( $create_order_message[0] === true ){
            Yii::app()->session['success_msg'] = $create_order_message[1];
            return true;
        }else{
            Yii::app()->session['error_msg'] = $create_order_message[1];
            return false;
        }
    }
    public function Actiondelete(){
        $product_id = $_POST['id'];
        $post = Orders::model()->findByPk( $product_id );
        $post->delete();
        $this->redirect(Yii::app()->createUrl('order/list'));
    }
}
?>