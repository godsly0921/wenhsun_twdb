<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 下午 05:07
 */
class ServiceService
{
    public function findServices()
    {
        $result = Service::model()->findAll(
            array(
                'select'=>'id,ser_language,ser_name,ser_content,ser_image,sub_image,ser_createtime,ser_type,ser_order',
                'order'=>'ser_language DESC , ser_order ASC'));
        return $result;

    }

    /**
     * @param array $input
     * @return Services
     */
    public function create(array $inputs)
    {

        $service = new Service();
        $service->ser_language = $inputs['ser_language'];
        $service->ser_name = $inputs['ser_name'];
        $service->ser_content = $inputs['ser_content'];


        $upload_image = $inputs['image'];
        if($upload_image['name']['ser_image']!=="") {
            $uuid_name = date("YmdHis") . uniqid();
            $tmp = explode('.', $upload_image['name']['ser_image']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name']['ser_image'], IMAGES_STORAGE_DIR . $uuid_name . '.' . $ext);
            $image_path = IMAGES_STORAGE_DIR . $uuid_name . '.' . $ext;
            chmod($image_path, 0777);
            ImageResize::resize($image_path, $image_path, 456, 310);
            $image_show_path = IMAGES_SHOW_DIR.$uuid_name.'.'.$ext;
            $service->ser_image = $image_show_path;
        }

        if($upload_image['name']['sub_image']!==""){
            $uuid_name = 'sub'.date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']['sub_image']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name']['sub_image'],IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext);
            $sub_image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            chmod($sub_image_path, 0777);
            ImageResize::resize($sub_image_path,$sub_image_path,695,385);
            $sub_image_show_path = IMAGES_SHOW_DIR.$uuid_name.'.'.$ext;
            $service->sub_image = $sub_image_show_path;
        }

        $service->ser_createtime = date("Y-m-d H:i:s");
        $service->ser_type = $inputs['ser_type'];
        $service->ser_order = $inputs['ser_order'];



        if (!$service->validate()) {
            return $service;
        }

        if (!$service->hasErrors()) {
            $success = $service->save();
        }

        if ($service === false) {
            $service->addError('save_fail', '新增服務失敗');
            return $service;
        }

        return $service;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateService(array $inputs)
    {
        $service = Service::model()->findByPk($inputs["id"]);

        $service->id = $service->id;
        $service->ser_language = $inputs['ser_language'];
        $service->ser_name = $inputs['ser_name'];
        $service->ser_content = $inputs['ser_content'];

        $upload_image = $inputs['image'];
        if($upload_image['name']['ser_image']!=="") {
            $uuid_name = date("YmdHis") . uniqid();
            $tmp = explode('.', $upload_image['name']['ser_image']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name']['ser_image'], IMAGES_STORAGE_DIR . $uuid_name . '.' . $ext);
            $image_path = IMAGES_STORAGE_DIR . $uuid_name . '.' . $ext;
            chmod($image_path, 0777);
            ImageResize::resize($image_path, $image_path, 456, 310);
            $image_show_path = IMAGES_SHOW_DIR.$uuid_name.'.'.$ext;
            $service->ser_image = $image_show_path;
        }

        if($upload_image['name']['sub_image']!==""){
            $uuid_name = 'sub'.date("YmdHis").uniqid();
            $tmp = explode('.',$upload_image['name']['sub_image']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name']['sub_image'],IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext);
            $sub_image_path = IMAGES_STORAGE_DIR.$uuid_name.'.'.$ext;
            chmod($sub_image_path, 0777);
            ImageResize::resize($sub_image_path,$sub_image_path,695,385);
            $sub_image_show_path = IMAGES_SHOW_DIR.$uuid_name.'.'.$ext;
            $service->sub_image = $sub_image_show_path;
        }

        $service->ser_createtime = date("Y-m-d H:i:s");
        $service->ser_type = $inputs['ser_type'];
        $service->ser_order = $inputs['ser_order'];

        if ($service->validate()) {
            $service->update();
        }

        return $service;
    }

    /**
     * @param $lang
     * @return array|mixed|null
     */
    public function findByLang($lang)
    {
        $result = Service::model()->findAllByAttributes(
            ['ser_language' => $lang, 'ser_type' => '1'],
            ['order' => 'ser_createtime ASC']
        );

        return $result;
    }
}