<?php
class EventlistService
{
    public function findList()
    {
        $result = Eventlist::model() -> findAll(array(
		'select' => '*',
		'order' => 'id ASC',
		));

        if(count($result)>=1){
            return $result;
        }else{
            return false;
        }

    }

    /**
     * @param array $input
     * @return Power
     */
    public function create(array $inputs)
    {
        $data = new Eventlist();
        $data->vdo_id = $inputs['vdo_id'];
        $data->time_in = $inputs['time_in'];
        $data->time_out = $inputs['time_out'];
		$data->pos_x = $inputs['pos_x'];
        $data->pos_x_720 = $inputs['pos_x_720'];
        $data->pos_x_1080 = $inputs['pos_x_1080'];
        $data->pos_y = $inputs['pos_y'];
        $data->pos_y_720 = $inputs['pos_y_720'];
        $data->pos_y_1080 = $inputs['pos_y_1080'];
        $data->type = $inputs['type'];
        $data->type_id = $inputs['type_id'];
        $data->popup_msg = $inputs['popup_msg'];
        $data->create_date =  date("Y-m-d H:i:s");
        $data->edit_date = '0000-00-00 00:00:00';



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
            $data->addError('save_fail', '新增失敗');
            return $data;
        }else{
            return $data;
        }


    }


    public function update(array $inputs)
    {
        $data = Eventlist::model()->findByPk($inputs["id"]);

        $data->vdo_id = $inputs['vdo_id'];
        $data->time_in = $inputs['time_in'];
        $data->time_out = $inputs['time_out'];
        $data->pos_x = $inputs['pos_x'];
        $data->pos_x_720 = $inputs['pos_x_720'];
        $data->pos_x_1080 = $inputs['pos_x_1080'];
        $data->pos_y = $inputs['pos_y'];
        $data->pos_y_720 = $inputs['pos_y_720'];
        $data->pos_y_1080 = $inputs['pos_y_1080'];
        $data->type = $inputs['type'];
        $data->type_id = $inputs['type_id'];
        $data->popup_msg = $inputs['popup_msg'];
        $data->edit_date = date("Y-m-d H:i:s");


        $upload_image = $inputs['new_img_url'];
        if($upload_image['name']!==""){
            $uuid_name = date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'],IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext);
            $image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            ImageResize::resize($image_path,$image_path,'239.7289','239.7289');
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