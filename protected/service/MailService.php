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

    public function sendMail($email_type, $employee_email, $message, $id,$employee_name)
    {
        try {
            // 管理者信箱
            $admin_email = $this->findAllEmail();

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
            $mail->addAddress($employee_email);

            $mail->addCC(isset($admin_email->addressee_1) ? $admin_email->addressee_1 : 'godsly0921@gmail.com');
            $mail->addCC(isset($admin_email->addressee_2) ? $admin_email->addressee_2 : 'godsly0921@gmail.com');
            $mail->addCC(isset($admin_email->addressee_3) ? $admin_email->addressee_3 : 'godsly0921@gmail.com');

            $mail->IsHTML(true);
            if ($email_type == 0) {
                return true;//正常不寄信
                $mail->Subject = '出勤通知:出勤正常';
                $mail->Body =
                    '<h2>親愛的' . $employee_name . '您好:<h2>
                 <p>提醒您，您昨天的出勤是正常。<br>詳細資訊如以下'
                    . $message . '<br><br>' .
                    '文訊雜誌社人資系統敬啟<br><br>' .
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';
            } else if ($email_type == 1) {
                $mail->Subject = '出勤通知:出勤異常';
                $mail->Body =
                    '<h2>親愛的' . $employee_name . '您好:<h2>' .
                    '<p>提醒您，您昨天的出勤是異常。<br>詳細資訊如以下'
                    . $message . '<br><br>' .
                    '<a href="http://192.168.0.160/wenhsun_hr/attendancerecord/update/' . $id . '">請點擊回覆異常</a>' .
                    '文訊雜誌社人資系統敬啟<br>' .
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';
            }

            if($mail->Send()){
                return true;
            }else{
                return false;
            }


        } catch (Exception $e) {
            Yii::log(date('Y-m-d H:i:s') . " Email 01 error write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
        }

    }


    public function sendAdminMail($emailType, $message)
    {
        try {
            // 管理者信箱
            $admin_email = $this->findAllEmail();

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

            $mail->addCC(isset($admin_email->addressee_1) ? $admin_email->addressee_1 : 'godsly0921@gmail.com');
            $mail->addCC(isset($admin_email->addressee_2) ? $admin_email->addressee_2 : 'godsly0921@gmail.com');
            $mail->addCC(isset($admin_email->addressee_3) ? $admin_email->addressee_3 : 'godsly0921@gmail.com');

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

            if($mail->Send()){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            Yii::log(date('Y-m-d H:i:s') . " Email 02 error write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
        }
    }

}
