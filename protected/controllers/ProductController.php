<?php
class ProductController extends Controller{
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
        $productService = new ProductService();
        $product_data = $productService->findAllProduct();
        #echo json_encode($category_data);exit();
        $this->render('list',['product_data'=>$product_data]);
    }

    public function ActionUpdate($id){
        $couponService = new CouponService();
        $productService = new ProductService();
        $operationlogService = new OperationlogService();
        $coupon = $couponService->findAllCouponWithStatus(1);
        if( Yii::app()->request->isPostRequest ){
            if (!CsrfProtector::comparePost()) {
                $this->redirect('index');
            }
            $inputs = [];
            $inputs["product_id"] = $id;
            $inputs["product_name"] = filter_input(INPUT_POST, "product_name");
            $inputs["coupon_id"] = filter_input(INPUT_POST, "coupon_id");
            $inputs["pic_point"] = filter_input(INPUT_POST, "pic_point");
            $inputs["product_type"] = filter_input(INPUT_POST, "product_type");
            $inputs["pic_number"] = filter_input(INPUT_POST, "pic_number");
            $inputs["price"] = filter_input(INPUT_POST, "price");
            $inputs["status"] = filter_input(INPUT_POST, "status");
            $product_data = $productService -> update( $inputs );
            if( $product_data[0] === true ){
                Yii::app()->session['success_msg'] = $product_data[1];
            }else{
                Yii::app()->session['error_msg'] = $product_data[1];
            }
            $coupon = array();
            $this->render('update',array( 'product_data' => $product_data[2], 'coupon' => $coupon ));
        }else{
            $product_data = $productService->findById($id);
            $this->render('update',array( 'product_data' => $product_data, 'coupon' => $coupon ));
        }
    }

    public function Actiondelete(){
        $product_id = $_POST['id'];
        $productService = new ProductService();
        $product_delete = $productService->delete($product_id);
        if( $product_delete[0] === true ){
            Yii::app()->session['success_msg'] = $product_delete[1];                
        }else{
            Yii::app()->session['error_msg'] = $product_delete[1];
        }
        $this->redirect(Yii::app()->createUrl('product/list'));
    }
}
?>