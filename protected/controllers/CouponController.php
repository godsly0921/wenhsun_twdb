<?php
class CouponController extends Controller{
    // layout
    public $layout = "//layouts/back_end";
    
    // 登入驗證
    protected function needLogin(): bool
    {
        return true;
    }

    public function Actionnew(){
        $coupon = array();
        $couponService = new CouponService();
        if( Yii::app()->request->isPostRequest ){
            if (!CsrfProtector::comparePost()) {
                $this->redirect('index');
            }
            $inputs = [];
            $inputs["coupon_name"] = filter_input(INPUT_POST, "coupon_name");
            $inputs["coupon_code"] = filter_input(INPUT_POST, "coupon_code");
            $inputs["coupon_pic"] = filter_input(INPUT_POST, "coupon_pic");
            $inputs["start_time"] = filter_input(INPUT_POST, "start_time");
            $inputs["end_time"] = filter_input(INPUT_POST, "end_time");
            $inputs["status"] = filter_input(INPUT_POST, "status");
            $coupon_create = $couponService -> create( $inputs );
            if( $coupon_create[0] === true ){
                Yii::app()->session['success_msg'] = $coupon_create[1];                
            }else{
                Yii::app()->session['error_msg'] = $coupon_create[1];
            }
            $coupon = array();
            $this->redirect(array('coupon/list'));
        }else{
            $coupon = array();
            $this->render('new',['coupon'=>$coupon]);
        }
    }

    public function Actionlist(){
        $couponService = new CouponService();
        $coupon_data = $couponService->findAllCoupon();
        $this->render('list',['coupon_data'=>$coupon_data]);
    }

    public function ActionUpdate($id){
        $couponService = new CouponService();
        $operationlogService = new operationlogService();
        if( Yii::app()->request->isPostRequest ){
            if (!CsrfProtector::comparePost()) {
                $this->redirect('index');
            }
            $inputs = [];
            $inputs["id"] = $id;
            $inputs["coupon_name"] = filter_input(INPUT_POST, "coupon_name");
            $inputs["coupon_code"] = filter_input(INPUT_POST, "coupon_code");
            $inputs["coupon_pic"] = filter_input(INPUT_POST, "coupon_pic");
            $inputs["start_time"] = filter_input(INPUT_POST, "start_time");
            $inputs["end_time"] = filter_input(INPUT_POST, "end_time");
            $inputs["status"] = filter_input(INPUT_POST, "status");
            $coupon_data = $couponService -> update( $inputs );
            if( $coupon_data[0] === true ){
                Yii::app()->session['success_msg'] = $coupon_data[1];                
            }else{
                Yii::app()->session['error_msg'] = $coupon_data[1];
            }
            $this->render('update',array( 'coupon_data' => $coupon_data[2]));
        }else{
            $coupon_data = $couponService->findById($id);
            $this->render('update',array( 'coupon_data' => $coupon_data ));
        }
    }

    public function Actiondelete(){
        $coupon_id = $_POST['id'];
        $couponService = new CouponService();
        $coupon_delete = $couponService->delete($coupon_id);
        if( $coupon_delete[0] === true ){
            Yii::app()->session['success_msg'] = $coupon_delete[1];                
        }else{
            Yii::app()->session['error_msg'] = $coupon_delete[1];
        }
        $this->redirect(Yii::app()->createUrl('coupon/list'));
    }
}
?>