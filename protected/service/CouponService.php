<?php
class CouponService
{
    public function findCouponList()
    {
        $result = Coupon::model() -> findAll(array(
		'select' => '*',
		'order' => 'id ASC',
		));

        if(count($result)>=1){
            return $result;
        }else{
            return false;
        }

    }

    public function findCouponGuestList()
    {
        $result = Guestcoupons::model() -> findAll(array(
            'select' => '*',
            'order' => 'id ASC',
        ));

        if(count($result)>=1){
            return $result;
        }else{
            return false;
        }

    }

    public function findCouponGuestListByDate($start_time,$end_time)
    {
        $result = Guestcoupons::model() -> findAll(array(
            'select' => '*',
            'order' => 'id ASC',
            'condition'=>"create_time >= :start_time and create_time <= :end_time and status = :status",
            'params'=>[
                ':status'=>1,
                ':start_time'=>$start_time,
                ':end_time'=>$end_time,
            ]
        ));

        if(count($result)>=1){
            return $result;
        }else{
            return false;
        }

    }

    public function findCouponRecordList()
    {
        $userscoupons = Userscoupons::model()->findAll();

        $data2 = [];

        foreach($userscoupons as $key=>$value){

            $member = Umember::model()->find([
                'condition' => 'id=:id',
                'params' => [
                    ':id' => (int)$value->mem_id,
                ]
            ]);

            $coupon = Coupon::model()->find([
                'condition' => 'id=:id',
                'params' => [
                    ':id' => (int)$value->coupon_id,
                ]
            ]);


                $data2[] = [
                    'id'=>$value->id,
                    'name'=>$coupon['coupon_name'],
                    'mem_id'=>$value->mem_id,
                    'name'=>$member->name,
                    'phone'=>$member->phone,
                    'email'=>$member->email,
                    'address'=>$member->address,
                    'sex'=>$member->sex,
                    'coupon_id'=>$value->coupon_id,
                    'use_status'=>$value->use_status,
                    'create_time'=>$value->create_time,
                    'use_time'=>$value->use_time
                ];



        }

        if(count($data2)>=1){
            return $data2;
        }else{
            return false;
        }

    }

    public function findCouponRecordListByDate($start_time,$end_time)
    {
        $userscoupons = Userscoupons::model()->findAll(array(
                'select' => '*',
                'order' => 'id ASC',
                'condition'=>"create_time >= :start_time and create_time <= :end_time and use_time != :use_time",
                'params'=>[
                    ':use_time'=>'0000-00-00 00:00:00',
                    ':start_time'=>$start_time,
                    ':end_time'=>$end_time,
                ]
            )
        );

        $data2 = [];

        foreach($userscoupons as $key=>$value){

            $member = Umember::model()->find([
                'condition' => 'id=:id',
                'params' => [
                    ':id' => (int)$value->mem_id,
                ]
            ]);

            $coupon = Coupon::model()->find([
                'condition' => 'id=:id',
                'params' => [
                    ':id' => (int)$value->coupon_id,
                ]
            ]);


            $data2[] = [
                'id'=>$value->id,
                'name'=>$coupon['coupon_name'],
                'mem_id'=>$value->mem_id,
                'name'=>$member->name,
                'phone'=>$member->phone,
                'email'=>$member->email,
                'address'=>$member->address,
                'sex'=>$member->sex,
                'coupon_id'=>$value->coupon_id,
                'use_status'=>$value->use_status,
                'create_time'=>$value->create_time,
                'use_time'=>$value->use_time
            ];



        }

        if(count($data2)>=1){
            return $data2;
        }else{
            return false;
        }

    }

    public function genPowerId()
    {
        $result = Power::model() -> findAll(array(
            'select' => 'id,power_number,power_name,power_controller,power_master_number,power_range,power_display',
            'order' => 'power_number ASC ,power_master_number ASC , power_range ASC',
        ));

        $p_id = 0;
        foreach($result as $key=>$value){
            if($value->power_number > $p_id){
                $p_id = $value->power_number;
            }
        }
        $p_id = $p_id+1;
        return $p_id;
    }

    /**
     * @param array $input
     * @return Power
     */
    public function create(array $inputs)
    {
        $data = new Coupon();
        $data->coupon_name = $inputs['coupon_name'];
        $data->start_time = $inputs['start_time'];
        $data->end_time = $inputs['end_time'];
		$data->limit = $inputs['limit'];
        $data->shopping_popup_msg = $inputs['shopping_popup_msg'];

        $upload_image = $inputs['img_url'];
        if($upload_image['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'],IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $data->img_url = $image_show_path;
        }

        if (!$data->validate()) {
            return $data;
        }

        if (!$data->hasErrors()) {
            $success = $data->save();
        }

        if ($success === false) {
            $data->addError('save_fail', '新增優待卷失敗');
            return $data;
        }else{
            return $data;
        }


    }


    public function update(array $inputs)
    {
        $data = Coupon::model()->findByPk($inputs["id"]);

        $data->coupon_name = $inputs["coupon_name"];
        $data->start_time = $inputs["start_time"];
        $data->end_time = $inputs["end_time"];
        $data->limit = $inputs["limit"];
        $data->shopping_popup_msg = $inputs['shopping_popup_msg'];

        $upload_image = $inputs['new_img_url'];
        if($upload_image['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'],IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            ImageResize::resize($image_path,$image_path);
            $image_show_path = image_url.$uuid_name.'.'.$ext;
            $data->img_url = $image_show_path;
        }else{
            $data->img_url = $inputs["img_url_old"];
        }

        if ($data->validate()) {
            $data->update();
        }

        return $data;
    }
}