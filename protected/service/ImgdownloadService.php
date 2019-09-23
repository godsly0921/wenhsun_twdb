<?php
class ImgdownloadService
{
    public static function create(array $data){
        $datetime_now = date('Y-m-d H:i:s');
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $post=new Imgdownload;
            $post->member_id = Yii::app()->session['member_id'];
            $post->orders_item_id = $data['orders_item_id'];
            $post->download_method  = $data['download_method'];
            $post->single_id = $data['single_id'];
            $post->size_type = $data['size_type'];
            $post->cost = $data['cost'];
            $post->authorization_no = $data['authorization_no'];
            $post->download_datetime = $datetime_now;
            if (!$post->save() ) {
                Yii::log(date('Y-m-d H:i:s') . " img_download create fail", CLogger::LEVEL_INFO);
              throw new Exception();
            }
            if($data['download_method'] == 2){ //方案下載
                $member_plan = Memberplan::model()->findByPk($data['member_plan_id']);
                $member_plan->remain_amount = $member_plan->remain_amount - 1;
                if($member_plan->remain_amount == 0){
                    $member_plan->status = 2;
                }
                if (!$member_plan->update() ) {
                    Yii::log(date('Y-m-d H:i:s') . " member_plan update fail", CLogger::LEVEL_INFO);
                  throw new Exception();
                }
            }
            if($data['download_method'] == 1){ //點數下載
                $member = Member::model()->findByPk(Yii::app()->session['member_id']);
                $member->active_point = $member->active_point - $data['cost'];
                if (!$member->update() ) {
                    Yii::log(date('Y-m-d H:i:s') . " member update fail", CLogger::LEVEL_INFO);
                  throw new Exception();
                }
            }
            Yii::log(date('Y-m-d H:i:s') . " img_download create success and member_plan update success", CLogger::LEVEL_INFO);
            $transaction->commit();

            return array(true,"下載記錄新增成功");

        }catch (Exception $e) {
            $transaction->rollback();
            Yii::log(date('Y-m-d H:i:s') . " img_download create fail and member_plan update fail", CLogger::LEVEL_INFO);
            return array(false,"下載記錄新增失誤,請稍後再試");
        } 
    }
    public static function findMemberDownloadPoint(){
        $total_cost = Imgdownload::model()->findAllBySql("select sum(cost) as total_cost from img_download where member_id = " . Yii::app()->session['member_id'] . " and download_method=1");
        return $total_cost;
    }

    public static function findAuthorizationNo(){
        $date = date('Ymd');
        $cnt = Imgdownload::model()->findAllBySql("select * from img_download where authorization_no like '" . $date . "%' order by authorization_no desc");
        return $cnt;
    }
    public static function findMemberDownloadImage($member_id){
        $result = Imgdownload::model()->findAll(array(
            'condition'=>'member_id=:member_id',
            'params'=>array(
                ':member_id' => $member_id,
            ),
            'group' => 'single_id'
        ));
        return $result;
    }
}
