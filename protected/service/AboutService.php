<?php

class AboutService
{
    public function findAll()
    {
        $about = About::model()->findAll();

        return $about;
    }

    public function getAllAbout()
    {
        $about = About::model()->findAll();
        $result = array();

        foreach ($about as $value) {
            foreach ($value as $key => $v) {
                if ($key === 'paragraph') {
                    $result[$value->title][$key] = nl2br($v);
                } else {
                    $result[$value->title][$key] = $v;
                }
            }
        }

        return $result;
    }

    public function findById($id)
    {
        $about = About::model()->findByPk($id);

        return $about;
    }

    public function update($inputs)
    {

        $about = About::model()->findByPk($inputs['id']);

        $about->description = $inputs['description'];
        $about->paragraph = $inputs['paragraph'];
        $about->update_time = date('Y-m-d H:i:s');
        $about->update_account_id = Yii::app()->session['uid'];
        $upload_image = $inputs['image'];
        if ($upload_image['name'] !== "") {
            $uuid_name = date("YmdHis") . uniqid();
            $tmp = explode('.', $upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'], ABOUT_IMAGE_UPLOAD . $uuid_name . '.' . $ext);
            $image_show_path = ABOUT_IMAGE . $uuid_name . '.' . $ext;
            $about->image = $image_show_path;
        } else {
            $about->image = '';
        }
        $operationlogService = new OperationlogService();

        if ($about->validate()) {
            $about->save();
            $motion = "更新關於我們";
            $log = "更新項目 = " . $about->title;
            $operationlogService->create_operationlog($motion, $log);
            return array(true, '更新成功');
        } else {
            $motion = "更新關於我們";
            $log = "更新項目 = " . $about->title;
            $operationlogService->create_operationlog($motion, $log, 0);
            return array(false, $about->getErrors());
        }
    }
}
