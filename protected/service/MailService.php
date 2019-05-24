<?php

class MailService
{
    public function findMailById($id)
    {
        $model = Mail::model()->findByPk($id);
        return $model;
    }

    public function findAllEmail()
    {
        $model = Mail::model()->findAll();
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

    public function sendMail($emailType, $employeeId, $message, $id)
    {
        try {
            // 管理者信箱
            $adminEmail = $this->findAllEmail();
            $user = EmployeeService::findEmployeeId($employeeId);

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->CharSet = 'utf-8';
            $mail->Username = 'wenhsun0509@gmail.com';
            $mail->Password = 'cute0921';
            $mail->From = 'wenhsun0509@gmail.com';
            $mail->FromName = '文訊雜誌社人資系統';
            $mail->addAddress($user->email);

            $mail->addCC(isset($adminEmail->addressee_1) ? $adminEmail->addressee_1 : 'godsly0921@gmail.com');
            $mail->addCC(isset($adminEmail->addressee_2) ? $adminEmail->addressee_2 : 'godsly0921@gmail.com');
            $mail->addCC(isset($adminEmail->addressee_3) ? $adminEmail->addressee_3 : 'godsly0921@gmail.com');

            $mail->IsHTML(true);
            if ($emailType == 0) {
                $mail->Subject = '出勤通知:出勤正常';
                $mail->Body =
                    '<h2>親愛的' . $user->name . '您好:<h2>
                 <p>提醒您，您昨天的出勤是正常。<br>詳細資訊如以下'
                    . $message . '<br><br>' .
                    '文訊雜誌社人資系統敬啟<br><br>' .
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';
            } else if ($emailType == 1) {
                $mail->Subject = '出勤通知:出勤異常';
                $mail->Body =
                    '<h2>親愛的' . $user->name . '您好:<h2>' .
                    '<p>提醒您，您昨天的出勤是異常。<br>詳細資訊如以下'
                    . $message . '<br><br>' .
                    '<a href="http://192.168.0.160/wenhsun_hr/attendancerecord/update/' . $id . '">請點擊回覆異常</a>' .
                    '文訊雜誌社人資系統敬啟<br>' .
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';
            }
            $mail->Send();
            return;

        } catch (Exception $e) {
            Yii::log(date('Y-m-d H:i:s') . " Email 01 error write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
        }

    }


    public function sendAdminMail($emailType, $message)
    {
        try {
            // 管理者信箱
            $adminEmail = $this->findAllEmail();

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->CharSet = 'utf-8';
            $mail->Username = 'wenhsun0509@gmail.com';
            $mail->Password = 'cute0921';
            $mail->From = 'wenhsun0509@gmail.com';
            $mail->FromName = '文訊雜誌社人資系統';
            $mail->addAddress('godsly0921@gmail.com');

            $mail->addCC(isset($adminEmail->addressee_1) ? $adminEmail->addressee_1 : 'godsly0921@gmail.com');
            $mail->addCC(isset($adminEmail->addressee_2) ? $adminEmail->addressee_2 : 'godsly0921@gmail.com');
            $mail->addCC(isset($adminEmail->addressee_3) ? $adminEmail->addressee_3 : 'godsly0921@gmail.com');

            $mail->IsHTML(true);
            if ($emailType == 0) {
                $mail->Subject = '用戶未設定，員工編號';
                $mail->Body =
                    '<h2>親愛的' . '管理員您好' . '您好:<h2>
                     <p>提醒您，有異常狀況。<br>詳細資訊如以下'
                    . $message . '<br><br>' .
                    '請善待妥善處理，謝謝。<br><br>' .
                    '文訊雜誌社人資系統敬啟<br><br>' .
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';
            }

            if ($emailType == 1) {
                $mail->Subject = '意外異常';
                $mail->Body =
                    '<h2>親愛的' . '管理員您好' . '您好:<h2>
                     <p>提醒您，有異常狀況。<br>詳細資訊如以下'
                    . $message . '<br><br>' .
                    '請善待妥善處理，謝謝。<br><br>' .
                    '文訊雜誌社人資系統敬啟<br><br>' .
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';
            }
            $mail->Send();
            return;
        } catch (Exception $e) {
            Yii::log(date('Y-m-d H:i:s') . " Email 02 error write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
        }
    }

}
