<?php
class MailService
{
    public function findMailById($id)
    {
        $model = Mail::model()->findByPk($id);
        return $model;
    }

    /**
     * @param array $input
     * @return contact
     */
    public function create(array $inputs)
    {
        $model = new Specialcase();
        $model->title = $inputs['title'];
        $model->member_id = (int)$inputs['member_id'];
        $model->application_time = $inputs['application_time'];
        $model->category = $inputs['category'];
        $model->approval_status = $inputs['approval_status'];
        $model->approval_time = $inputs['approval_time'];
        $model->approval_account_id = $inputs['approval_account_id'];
        $model->member_ip = $inputs['member_ip'];
        $model->msg = $inputs['msg'];

        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            $success = $model->save();
        }

        if ($success === false) {
            $model->addError('save_fail', '新增失敗');
            return $model;
        }

        return $model;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateMail(array $inputs)
    {
        $model = Mail::model()->findByPk($inputs["id"]);

        $model->id = $model->id;
        $model->mail_server = $inputs['mail_server'];
        $model->sender = $inputs['sender'];
        $model->addressee_1 = $inputs['addressee_1'];
        $model->addressee_2 = $inputs['addressee_2'];
        $model->addressee_3 = $inputs['addressee_3'];

        if ($model->validate()) {
            $model->update();
        }

        return $model;
    }
}