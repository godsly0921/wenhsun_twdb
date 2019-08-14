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
            $mail->addCC(isset($admin_email->addressee_3) ? $admin_email->addressee_3 : 'yuyen103@gmail.com');

            $mail->IsHTML(true);
            if ($email_type == 0) {
                return true;//正常不寄信
                $mail->Subject = '昨日出勤通知:出勤正常';
                $mail->Body =
                    '<h2>親愛的' . $employee_name . '您好:<h2>
                 <p>提醒您，您昨天的出勤是正常。<br>詳細資訊如以下<br>'
                    . $message . '<br><br>' .
                    '文訊雜誌社人資系統敬啟<br><br>' .
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';
            } else if ($email_type == 1) {
                $mail->Subject = '昨日出勤通知:出勤異常';
                $mail->Body =
                    '<h2>親愛的' . $employee_name . '您好:<h2>' .
                    '<p>提醒您，您昨天的出勤是異常。<br>詳細資訊如以下<br>'
                    . $message . '<br><br>' .
                    '<a href="http://192.168.0.160/wenhsun_hr/attendancerecord/update/' . $id . '">請點擊回覆異常</a><br>' .
                    '文訊雜誌社人資系統敬啟<br>' .
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';
            }else if ($email_type == 2) { // 每日早上 9:30 執行排程，9:30 前未打卡發送異常信
                $mail->Subject = '今日出勤通知:出勤異常';
                $mail->Body =
                    '<h2>親愛的' . $employee_name . '您好:<h2>' .
                    '<p>提醒您，您今天的出勤是異常。<br>詳細資訊如以下<br>'.
                    '出勤日異常，9:30未打卡,遲到<br>' .
                    '請您及時告知主管與人事主管遲到或請假原因。<br>' .
                    '文訊雜誌社人資系統敬啟<br>' .
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';
            }else if ($email_type == 3) { // 每日早上 9:30 執行排程，9:30 前的打卡明細給人事主管／會計
                $mail->Subject = '今日出勤記錄明細';
                $mail->Body =
                    '<h2 style="color:black;">今日出勤記錄明細：</h2><br>'.
                    $message .
                    '<br><p style="color:black;">備註：此信箱為公告用信箱，請勿回信。謝謝。</p>';
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
                    '<h2>親愛的' . '管理員' . '您好:<h2>
                     <p>提醒您，有異常狀況。<br>詳細資訊如以下<br>'
                    . $message . '<br><br>' .
                    '請善待妥善處理，謝謝。<br><br>' .
                    '文訊雜誌社人資系統敬啟<br><br>' .
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';
            }

            if ($emailType == 1) {
                $mail->Subject = '意外異常';
                $mail->Body =
                    '<h2>親愛的' . '管理員' . '您好:<h2>
                     <p>提醒您，有異常狀況。<br>詳細資訊如以下<br>'
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

    public function sendNewsMail($inputs)
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
            $mail->addAddress($inputs['email']);
            $mail->addCC(isset($admin_email->addressee_1) ? $admin_email->addressee_1 : 'godsly0921@gmail.com');
            $mail->addCC(isset($admin_email->addressee_2) ? $admin_email->addressee_2 : 'godsly0921@gmail.com');
            $mail->addCC(isset($admin_email->addressee_3) ? $admin_email->addressee_3 : 'godsly0921@gmail.com');
            $mail->IsHTML(true);

            $mail->Subject = $inputs['new_title'];

            if($inputs["new_image"]!=''){
                $mail->Body =
                    '<h2>親愛的' . $inputs["name"]  . '您好:<h2>
                     <p>提醒您，有公告通知。<br>詳細資訊如以下。<br>'
                    . nl2br($inputs["new_content"]) . '<br><br>' .
                    '請妥善處理，謝謝。<br><br>' .
                    '文訊雜誌社人資系統敬啟<br><br>' .
                    '<a href="'.ROOT_HTTP.Yii::app()->createUrl('news/download').'?id='.$inputs['id'].'">請下載附件</a><br>'.
                    '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽HR。謝謝。</p>';

            }elseif(empty($inputs["new_image"])) {
                $mail->Body =
                    '<h2>親愛的' . $inputs["name"] . '您好:<h2>
                     <p>提醒您，有公告通知。<br>詳細資訊如以下。<br>'
                    . nl2br($inputs["new_content"]) . '<br><br>' .
                    '請妥善處理，謝謝。<br><br>' .
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
            return false;
        }
    }

    public function sendForgetPwdMail($inputs){
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
            $mail->addAddress($inputs['account']);
            $mail->IsHTML(true);

            $mail->Subject = "台灣文學照片資料庫 - 忘記密碼驗證信";
            $mail->Body =
                '<h2>親愛的' . $inputs["account"] . '您好:<h2>
                 <p>請按下方連結以驗證您的帳號：<br>'. 
                '<a href="'.DOMAIN.Yii::app()->createUrl('site/forgetverification') . '?verification_code='.$inputs["verification_code"].'">'.DOMAIN.Yii::app()->createUrl('site/forgetverification') . '?verification_code='.$inputs["verification_code"].'</a><br><br>' .
                '驗證通過請以此組臨時密碼登入：'.$inputs["verification_code"].'<br><br>' .
                '並請盡速變更密碼<br><br>'.
                '台灣文學照片資料庫敬啟<br><br>'.
                '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽客服。謝謝。</p>';

            if($mail->Send()){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            Yii::log(date('Y-m-d H:i:s') . " Email 02 error write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
            return false;
        }
    }

    public function sendRegisterMail($inputs)
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
            $mail->addAddress($inputs['account']);
            $mail->IsHTML(true);

            $mail->Subject = "台灣文學照片資料庫 - 帳號驗證信";
            $mail->Body =
                '<h2>親愛的' . $inputs["name"] . '您好:<h2>
                 <p>請按下方連結以驗證您註冊的帳號：<br>'. 
                '<a href="'.DOMAIN.Yii::app()->createUrl('site/verification') . '?verification_code='.$inputs["verification_code"].'">'.DOMAIN.Yii::app()->createUrl('site/verification') . '?verification_code='.$inputs["verification_code"].'</a><br><br>' .
                '台灣文學照片資料庫敬啟<br><br>' .
                '備註：此信箱為公告用信箱，請勿回信，若有疑問，請洽客服。謝謝。</p>';

            if($mail->Send()){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            Yii::log(date('Y-m-d H:i:s') . " Email 02 error write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
            return false;
        }
    }

    public function sendApproveMail($inputs)
    {
        try {
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
            $mail->addAddress($inputs['to']);
            if ($inputs['agent'] != '') {
                $mail->addAddress($inputs['agent']);
            }
            $mail->addAddress($inputs['manager']);
            $mail->addAddress('jenny@wenhsun.com.tw');
            $mail->addAddress('pinkfloydbigman@gmail.com');
            // $mail->addAddress('fdp.wenhsun@gmail.com');
            $mail->IsHTML(true);

            $mail->Subject = $inputs['subject'];
            $mail->Body = $inputs['body'];

            if ($mail->Send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Yii::log(date('Y-m-d H:i:s') . " Email 02 error write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
            return false;
        }
    }
}
