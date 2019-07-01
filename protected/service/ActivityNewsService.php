<?php

class ActivityNewsService
{
    public function findAll()
    {
        $news = ActivityNews::model()->findAll();

        return $news;
    }

    public function getAllAcitiveNews()
    {
        $news = ActivityNews::model()->findAllByAttributes(array('active' => 'T'));

        return $news;
    }

    public function findById($id)
    {
        $news = ActivityNews::model()->findByPk($id);

        return $news;
    }

    public function create($inputs)
    {

        $news = new ActivityNews();

        $news->title = $inputs['title'];
        $news->second_title = $inputs['second_title'];
        $news->content = $inputs['content'];
        $news->main_content = $inputs['main_content'];
        $news->create_at = date('Y-m-d H:i:s');
        $news->update_at = date('Y-m-d H:i:s');
        $news->create_by = Yii::app()->session['uid'];
        $news->update_by = Yii::app()->session['uid'];
        $upload_image = $inputs['image'];
        if ($upload_image['name'] !== "") {
            $uuid_name = date("YmdHis") . uniqid();
            $tmp = explode('.', $upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'], NEWS_IMAGE_UPLOAD . $uuid_name . '.' . $ext);
            $image_show_path = NEWS_IMAGE . $uuid_name . '.' . $ext;
            $news->image = $image_show_path;
        } else {
            $news->image = '';
        }
        $operationlogService = new OperationlogService();

        if ($news->validate()) {
            $news->save();
            $motion = "新增最新消息";
            $log = "標題 = " . $news->title;
            $operationlogService->create_operationlog($motion, $log);
            return array(true, '更新成功');
        } else {
            $motion = "新增最新消息";
            $log = "標題 = " . $news->title;
            $operationlogService->create_operationlog($motion, $log, 0);
            return array(false, $news->getErrors());
        }
    }

    public function update($inputs)
    {

        $news = ActivityNews::model()->findByPk($inputs['id']);

        $news->title = $inputs['title'];
        $news->second_title = $inputs['second_title'];
        $news->content = $inputs['content'];
        $news->main_content = $inputs['main_content'];
        $news->update_at = date('Y-m-d H:i:s');
        $news->update_by = Yii::app()->session['uid'];
        $news->active = $inputs['active'];
        $upload_image = $inputs['image'];
        if ($upload_image['name'] !== "") {
            $uuid_name = date("YmdHis") . uniqid();
            $tmp = explode('.', $upload_image['name']);
            $ext = end($tmp);
            move_uploaded_file($upload_image['tmp_name'], NEWS_IMAGE_UPLOAD . $uuid_name . '.' . $ext);
            $image_show_path = NEWS_IMAGE . $uuid_name . '.' . $ext;
            $news->image = $image_show_path;
        }
        $operationlogService = new OperationlogService();

        if ($news->validate()) {
            $news->save();
            $motion = "更新關於我們";
            $log = "更新項目 = " . $news->title;
            $operationlogService->create_operationlog($motion, $log);
            return array(true, '更新成功');
        } else {
            $motion = "更新關於我們";
            $log = "更新項目 = " . $news->title;
            $operationlogService->create_operationlog($motion, $log, 0);
            return array(false, $news->getErrors());
        }
    }

    public function findAllByPaging($page)
    {
        if ($page == 1) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * 10;
        }

        $command = Yii::app()->db->createCommand()
        ->select('*')
        ->from('activity_news')
        ->where("active = 'T'")
        ->order('create_at DESC')
        ->limit(10, $offset);

        $news = $command->queryAll();

        $result = array();
        $count = 0;

        foreach ($news as $content) {
            foreach ($content as $key => $value) {
                $result[$count][$key] = $value;
                $result[$count]['count'] = $count;
            }
            $count++;
        }

        if (count($news) % 2 == 1) {
            $result[] = array('title' => '', 'count' => count($news));
        }

        return $result;
    }
}
