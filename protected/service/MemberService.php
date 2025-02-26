<?php
class MemberService
{
    private $memberValidate;

    public function __construct($memberValidate = null)
    {
        $this->memberValidate = $memberValidate ?: new MemberValidator();
    }

    public static function findMemberlist()
    {
        $datas = Member::model()->findAll(array(
            'select' => '*',
            'order' => 'create_date DESC ',
        ));

        if ($datas == null) {
            $datas = false;
        }

        return $datas;
    }

    public static function findMembers()
    {
        $datas = Member::model()->findAll(array(
            'select' => '*',
            'order' => 'create_date DESC ',
        ));
        $members = '';
        if (count($datas) > 0) {

            foreach ($datas as $key => $value) {
                $members[$value->id] = $value;
            }
            return $members;

        } else {
            return $members;
        }

    }

    public static function findMemberBlacklist($inputs)
    {
        $datas = Member::model()->findAll([
            'condition' => 'stop_card_datetime>=:start_time and stop_card_datetime<=:end_time and status=:status',
            'params' => [
                ':start_time' => $inputs['start_time'],
                ':end_time' => $inputs['end_time'],
                ':status' => 1,
            ]
        ]);

        if ($datas == null) {
            $datas = false;
        }

        return $datas;
    }

    public static function findProfessorMemberBlacklist(array $inputs)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '*';


        //查詢設定
        if ($inputs['grp1'] !== "0" && $inputs['grp2'] !== "0" && $inputs['grp3'] !== "0" && $inputs['start_date'] !== "" && $inputs['end_date'] !== "" && $inputs['status'] !== "") {
            $criteria->condition = 'id =:id and stop_card_datetime >=:start_date and stop_card_datetime <=:end_date and status=:status';
            $criteria->params = array(':id' => $inputs['grp3'], ':start_date' => $inputs['start_date'], ':end_date' => $inputs['end_date'], ':status' => $inputs['status']);
        }

        if ($inputs['grp1'] !== "0" && $inputs['grp2'] !== "0" && $inputs['grp3'] === "0" && $inputs['start_date'] !== "" && $inputs['end_date'] !== "" && $inputs['status'] !== "") {
            $criteria->condition = 'grp_lv2 =:grp_lv2 and stop_card_datetime >=:start_date and stop_card_datetime <=:end_date and status=:status';
            $criteria->params = array(':grp_lv2' => $inputs['grp2'], ':start_date' => $inputs['start_date'], ':end_date' => $inputs['end_date'], ':status' => $inputs['status']);
        }

        if ($inputs['grp1'] !== "0" && $inputs['grp2'] === "0" && $inputs['grp3'] === "0" && $inputs['start_date'] !== "" && $inputs['end_date'] !== "" && $inputs['status'] !== "") {
            $criteria->condition = 'grp_lv1 =:grp_lv1 and stop_card_datetime >=:start_date and stop_card_datetime <=:end_date and status=:status';
            $criteria->params = array(':grp_lv1' => $inputs['grp1'], ':start_date' => $inputs['start_date'], ':end_date' => $inputs['end_date'], ':status' => $inputs['status']);
        }

        //查詢設定
        if ($inputs['grp1'] === "0" && $inputs['grp2'] === "0" && $inputs['grp3'] === "0" && $inputs['start_date'] !== "" && $inputs['end_date'] !== "" && $inputs['status'] !== "") {
            $criteria->condition = 'stop_card_datetime >=:start_date and stop_card_datetime <=:end_date and status=:status';
            $criteria->params = array(':start_date' => $inputs['start_date'], ':end_date' => $inputs['end_date'], ':status' => $inputs['status']);
        }

        if ($inputs['grp1'] !== "0" && $inputs['grp2'] !== "0" && $inputs['grp3'] === "0" && $inputs['start_date'] !== "" && $inputs['end_date'] !== "" && $inputs['status'] !== "") {
            $criteria->condition = 'stop_card_datetime >=:start_date and stop_card_datetime <=:end_date and status=:status';
            $criteria->params = array(':start_date' => $inputs['start_date'], ':end_date' => $inputs['end_date'], ':status' => $inputs['status']);
        }

        if ($inputs['grp1'] !== "0" && $inputs['grp2'] === "0" && $inputs['grp3'] === "0" && $inputs['start_date'] !== "" && $inputs['end_date'] !== "" && $inputs['status'] !== "") {
            $criteria->condition = 'stop_card_datetime >=:start_date and stop_card_datetime <=:end_date and status=:status';
            $criteria->params = array(':start_date' => $inputs['start_date'], ':end_date' => $inputs['end_date'], ':status' => $inputs['status']);
        }

        //查詢設定
        if ($inputs['grp1'] === "0" && $inputs['grp2'] === "0" && $inputs['grp3'] === "0" && $inputs['start_date'] === "" && $inputs['end_date'] === "" && $inputs['status'] !== "") {
            $criteria->condition = 'status=:status';
            $criteria->params = array(':status' => $inputs['status']);
        }

        if ($inputs['grp1'] === "0" && $inputs['grp2'] === "0" && $inputs['grp3'] === "0" && $inputs['start_date'] !== "" && $inputs['end_date'] !== "" && $inputs['status'] !== "") {
            $criteria->condition = 'stop_card_datetime >=:start_date and stop_card_datetime <=:end_date and status=:status';
            $criteria->params = array(':start_date' => $inputs['start_date'], ':end_date' => $inputs['end_date'], ':status' => $inputs['status']);
        }

        if ($inputs['grp1'] !== "0" && $inputs['grp2'] !== "0" && $inputs['grp3'] !== "0" && $inputs['start_date'] !== "" && $inputs['end_date'] !== "" && $inputs['status'] !== "") {
            $criteria->condition = 'id =:id and stop_card_datetime >=:start_date and stop_card_datetime <=:end_date and status=:status';
            $criteria->params = array(':id' => $inputs['grp3']);
        }


        $datas = Member::model()->findAll($criteria);
        if (count($datas) == 0) {
            $datas = false;
        }

        return $datas;
    }


    public static function findProfessorMemberDetailList(array $inputs, $groups)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '*';

        //查詢設定
        if ($inputs['grp1'] !== "" && $inputs['grp2'] != "" && $inputs['grp3'] != "") {

            if ($inputs['grp1'] !== "0" && $inputs['grp2'] !== "0" && $inputs['grp3'] !== "0") {
                $criteria->condition = 'id =:id';
                $criteria->params = array(':id' => $inputs['grp3']);
            }

            if ($inputs['grp1'] !== "0" && $inputs['grp2'] !== "0" && $inputs['grp3'] == "0") {
                $criteria->condition = 'grp_lv2 = :grp_lv2';
                $criteria->params = array(':grp_lv2' => $inputs['grp2']);
            }

            if ($inputs['grp1'] !== "0" && $inputs['grp2'] == "0" && $inputs['grp3'] == "0") {
                $criteria->condition = 'grp_lv1 = :grp_lv1';
                $criteria->params = array(':grp_lv1' => $inputs['grp1']);
            }

        }

        $datas = Member::model()->findAll($criteria);

        if (count($datas) == 0) {
            $datas = false;
        }

        return $datas;
    }

    public static function findMemberDetailList(array $inputs, $groups)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '*';

        //查詢設定
        if ($inputs['keyword'] !== "" && $inputs['keyword_field'] != "" && $inputs['user_group'] != "") {

            if ($inputs['keyword'] != "" && $inputs['keyword_field'] == 1) {
                $criteria->condition = 'name like :keyword';
                $criteria->params = array(':keyword' => '%' . $inputs['keyword'] . '%');
            }

            if ($inputs['keyword'] != "" && $inputs['keyword_field'] == 2) {
                $criteria->condition = 'account like :keyword';
                $criteria->params = array(':keyword' => '%' . $inputs['keyword'] . '%');
            }

            if ($inputs['user_group'] !== "0") {
                $user_group = 'user_group=' . $inputs['user_group'];
                $criteria->addCondition($user_group);

            }

            if ($inputs['user_group'] === "0") {

                $status = '';
                foreach ($groups as $key => $value) {
                    if ($key == 0) {
                        $status = $value->group_number;
                    } else {
                        $status = $status . ',' . $value->group_number;
                    }

                }
                $outputs = explode(",", $status);
                $condition_in = ['user_group' => $outputs];

                if (count($condition_in) >= 1) {
                    foreach ($condition_in as $key => $value) {
                        $criteria->addInCondition($key, $value);
                    }
                }

            }

        }

        if ($inputs['keyword'] === "" && $inputs['user_group'] != "") {

            if ($inputs['user_group'] !== "0") {
                $criteria->condition = 'user_group=:user_group';
                $criteria->params = array(':user_group' => $inputs['user_group']);
            }

            if ($inputs['user_group'] === "0") {
                $status = '';
                foreach ($groups as $key => $value) {
                    if ($key == 0) {
                        $status = $value->group_number;
                    } else {
                        $status = $status . ',' . $value->group_number;
                    }

                }
                $outputs = explode(",", $status);
                $condition_in = ['user_group' => $outputs];

                if (count($condition_in) >= 1) {
                    foreach ($condition_in as $key => $value) {
                        $criteria->addInCondition($key, $value);
                    }
                }

            }

        }

        /*if(count($condition_in)>=1){
            foreach($condition_in as $key=>$value){
                $criteria->addInCondition($key, $value);
            }
        }*/
        $datas = Member::model()->findAll($criteria);

        if (count($datas) == 0) {
            $datas = false;
        }

        return $datas;
    }

    public function fb_account_create(array $inputs){
        $model = new Member();
        $model->name = $inputs['name'];
        $model->fb_user_id = $inputs['fb_user_id'];
        $model->email = $inputs['email'];
        $model->account = $inputs['email'];
        $model->active = "Y";
        $model->account_type = "4";
        $model->create_date = date('Y-m-d H:i:s');
        $model->update_date = date('Y-m-d H:i:s');
        if (!$model->validate()) {
            Yii::log(date("Y-m-d H:i:s").'FB account create validate false account'.$inputs['email'], CLogger::LEVEL_INFO);
            return false;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                Yii::log(date("Y-m-d H:i:s").'FB account create true account'.$inputs['email'], CLogger::LEVEL_INFO);
                return $model;      
            }else{       
                Yii::log(date("Y-m-d H:i:s").'FB account create false account'.$inputs['email'], CLogger::LEVEL_INFO);
                return false;
            }
        }
    }
    public function fb_account_update($member,array $inputs){
        $model = Member::model()->findByPk($member->id);
        if($model->fb_user_id ==""){
            $model->fb_user_id = $inputs['fb_user_id'];
            if (!$model->validate()) {
                Yii::log(date("Y-m-d H:i:s").'FB account update validate false account'.$member->account, CLogger::LEVEL_INFO);
                return false;
            }

            if (!$model->hasErrors()) {
                $success = $model->update();
                Yii::log(date("Y-m-d H:i:s").'FB account update true account'.$member->account, CLogger::LEVEL_INFO);
                return $model;
            } else {
                Yii::log(date("Y-m-d H:i:s").'FB account update false account'.$member->account, CLogger::LEVEL_INFO);
                return false;
            }
        }
    }
    public function google_account_create(array $inputs){
        $model = new Member();
        $model->name = $inputs['name'];
        $model->google_sub = $inputs['sub'];
        $model->google_locale = $inputs['locale'];
        $model->email = $inputs['email'];
        $model->account = $inputs['email'];
        $model->active = "Y";
        $model->account_type = "3";
        $model->create_date = date('Y-m-d H:i:s');
        $model->update_date = date('Y-m-d H:i:s');
        if (!$model->validate()) {
            Yii::log(date("Y-m-d H:i:s").'Google account create validate false account'.$inputs['email'], CLogger::LEVEL_INFO);
            return false;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                Yii::log(date("Y-m-d H:i:s").'Google account create true account'.$inputs['email'], CLogger::LEVEL_INFO);
                return $model;      
            }else{       
                Yii::log(date("Y-m-d H:i:s").'Google account create false account'.$inputs['email'], CLogger::LEVEL_INFO);
                return false;
            }
        }
    }

    public function google_account_update($member,array $inputs){
        $model = Member::model()->findByPk($member->id);
        if($model->google_sub ==""){
            $model->google_sub = $inputs['sub'];
            $model->google_locale = $inputs['locale'];
            if (!$model->validate()) {
                Yii::log(date("Y-m-d H:i:s").'Google account update validate false account'.$member->account, CLogger::LEVEL_INFO);
                return false;
            }

            if (!$model->hasErrors()) {
                $success = $model->update();
                Yii::log(date("Y-m-d H:i:s").'Google account update true account'.$member->account, CLogger::LEVEL_INFO);
                return $model;
            } else {
                Yii::log(date("Y-m-d H:i:s").'Google account update false account'.$member->account, CLogger::LEVEL_INFO);
                return false;
            }
        }
    }

    public function findByForgetVerificationCode($verification_code){
        $result = Member::model()->find([
            'condition' => 'verification_code=:verification_code',
            'params' => [
                ':verification_code' => $verification_code,
            ]
        ]);
        if($result){
            $model = Member::model()->findByPk($result->id);
            $model->password = md5($verification_code);
            if (!$model->update()) {
                $model->addError('update_fail', '忘記密碼變更失敗');
                foreach ($model->getErrors() as $error){
                    Yii::log(date("Y-m-d H:i:s")."account=>".$model->account.", forget password error：".$error[0],  CLogger::LEVEL_INFO);
                }
                return $model;
            } else {
                Yii::log(date("Y-m-d H:i:s")." forget password success account=>".$model->account,  CLogger::LEVEL_INFO);
                return $model;
            }
        }else{
            return array();
        }
    }
    public function findByVerificationCode($verification_code){
        $result = Member::model()->find([
            'condition' => 'verification_code=:verification_code',
            'params' => [
                ':verification_code' => $verification_code,
            ]
        ]);
        if($result){
            $model = Member::model()->findByPk($result->id);
            if($model->active == "N"){
                $model->active = "Y";
                if (!$model->update()) {
                    $model->addError('update_fail', '帳號啟用失敗');
                    foreach ($model->getErrors() as $error){
                        Yii::log(date("Y-m-d H:i:s")."account=>".$model->account.", account active error：".$error[0],  CLogger::LEVEL_INFO);
                    }
                    return $model;
                } else {
                    Yii::log(date("Y-m-d H:i:s")." account active success account=>".$model->account,  CLogger::LEVEL_INFO);
                    return $model;
                }
            }else{
                return $model;
            }           
        }else{
            return array();
        }
    }

    public function create(array $inputs)
    {
        $model = new Member();
        $operationlogService = new OperationlogService();
        $model->account = $inputs['account'];

        $member = $this->findByAccount($inputs['account']);
        if ($member != NULL) {
            $model->addError('account', '該帳號已有人使用');
            return $model;
        }

        if ($inputs["password_confirm"] === "" || $inputs["password"] !== $inputs["password_confirm"]) {
            $model->addError('password_confirm', '密碼不一致, 請重新輸入');
            return $model;
        }

        $model->password = $inputs['password'];
        $model->name = $inputs['name'];
        $model->email = $inputs['account'];
        $model->gender = $inputs['gender'];
        $model->birthday = $inputs['birthday']?$inputs['birthday']:"0000-00-00";
        $model->phone = $inputs['phone'];
        $model->mobile = $inputs['mobile'];
        $model->member_type = $inputs['member_type'];
        $model->account_type = $inputs['account_type'];
        $model->nationality = $inputs['nationality'];
        $model->county = $inputs['county'];
        $model->town = $inputs['town'];
        $model->address = $inputs['address'];
        $model->active_point = 0;
        $model->inactive_point = 0;
        $model->create_by = Yii::app()->session['uid'];
        $model->update_by = Yii::app()->session['uid'];
        $model->active = $inputs['active'];
        $model->verification_code = isset($inputs['verification_code'])?$inputs['verification_code']:"";

        $model->password = md5($inputs['password']);
        $model->create_date = date('Y-m-d H:i:s');
        $model->update_date = date('Y-m-d H:i:s');
        $model = $this->validate($model);

        if (!$model->save()) {
            $model->addError('update_fail', '新增使用者失敗');
            $motion = "建立會員";
            $log = "建立 會員帳號 = " . $inputs['account'] . "；會員名稱 = " . $inputs["name"];
            $operationlogService->create_operationlog( $motion, $log, 0 );
            return $model;
        } else {
            $motion = "建立會員";
            $log = "建立 會員帳號 = " . $inputs['account'] . "；會員名稱 = " . $inputs["name"];
            $operationlogService->create_operationlog( $motion, $log );
            return $model;
        }
    }

    public function createregister(array $inputs)
    {
        $model = new Membertw();
        
        $model->account = $inputs['account'];

        $member = $this->findByAccount($inputs['account']);

        if($member!=NULL){
            $model->addError('account', '該帳號已有人使用');
            return $model;
        }

        $model->name = $inputs['name'];
        $model->sex = $inputs['sex'];
        $model->phone1 = $inputs['phone1'];
        $model->phone2 = $inputs['phone2'];
        $model->tel_no1 = $inputs['tel_no1'];
        $model->tel_no2 = $inputs['tel_no2'];
        $model->email1 = $inputs['email1'];
        $model->email2 = $inputs['email2'];
        $model->user_group = $inputs['user_group'];
        $model->year = $inputs['year'];
        $model->month = $inputs['month'];
        $model->day = $inputs['day'];
        $model->status = 1;//$inputs['status'];
        $model->address = $inputs["address"];
        $model->grp_lv1 = $inputs['grp_lv1'];
        $model->grp_lv2 = $inputs['grp_lv2'];
        $model->professor = $inputs['professor'];
        $model->stop_card_datetime = date("Y-m-d H:i:s");
        $model->stop_card_remark = '帳號尚未啟用';
        $model->card_number = $inputs["card_number"];
        //$model->stop_card_people = $inputs['stop_card_people'];*/

        if( strlen($inputs["card_number"])!=10 || !is_numeric($inputs["card_number"]) ){
            $model->addError('card_confirm', '卡號格式錯誤, 請重新輸入');
            return $model;
        }

        if ($inputs["password_confirm"] === "" || $inputs["password"] != $inputs["password_confirm"]) {
            $model->addError('password_confirm', '確認密碼錯誤, 請重新輸入');

      
            return $model;
        }


        $model->password = md5($inputs['password']);
        $model->stop_card_datetime = date('0000-00-00 00:00:00');
        $model->create_date = date('Y-m-d H:i:s');
        $model->edit_date = date('Y-m-d H:i:s');
        $model->status = 0;

        $model = $this->validate($model);

        if (!$model->save()) {
            $model->addError('update_fail', '新增使用者失敗');
            return $model;
        } else {
            return $model;
        }

    }

    private function validate(Member $model)
    {

        if (!$this->memberValidate->validateEnglishName($model->account)) {
            $model->addError('mem_ename_invalid', '請正確輸入帳號名稱');
        }
        if (!$this->memberValidate->validatePhone($model->phone)) {
            $model->addError('mem_phone_invalid', '電話號碼格式不正確');
        }

        if (!$this->memberValidate->validatePhone($model->mobile)) {
            $model->addError('mem_mobile_invalid', '手機號碼格式不正確');
        }

        if (!$this->memberValidate->validateEmail($model->email)) {
            $model->addError('mem_email_invalid', 'E-Mail格式不正確');
        }

        if (!$this->memberValidate->validatePasswd($model->password)) {
            $model->addError('password_invalid', '密碼(6碼)最少包含一個英文字');
        }

        return $model;
    }

    private function validatePassword(member $model)
    {

        if (!$this->memberValidate->validatePasswd($model->password)) {
            $model->addError('mem_password_invalid', '密碼(6碼)最少包含一個英文字');
        }

        return $model;
    }

    private function genMemberId()
    {
        $sql = "SELECT auto_increment FROM information_schema.tables WHERE table_schema='gjftamcc_db' and table_name='member'";

        $row = Yii::app()->db->createCommand($sql)->queryRow();
        $seqId = substr(str_pad($row['auto_increment'], 5, '0', STR_PAD_LEFT), 0, 5);

        $date = new DateTime();

        $memberId = 'A' . substr($date->format('Y'), 2, 2) . $seqId;

        return $memberId;
    }

    /**
     * @param $mem_id
     * @return CActiveRecord
     */
    public static function findByMemId($mem_id)
    {
        $result = Member::model()->find([
            'condition' => 'id=:id',
            'params' => [
                ':id' => $mem_id,
            ]
        ]);

        return $result;
    }

    /**
     * @param $mem_id
     * @return CActiveRecord
     */
    public static function findByProfessor($grp2)
    {
        $result = Member::model()->findAll([
            'condition' => 'grp_lv2=:grp_lv2 and user_group=:user_group',
            'params' => [
                ':grp_lv2' => $grp2,
                ':user_group' => 2,
            ]
        ]);

        return $result;
    }
    public function forgetVerification($inputs){
        $result = Member::model()->find([
            'condition' => 'account=:account and email=:email',
            'params' => [
                ':account' => $inputs['account'],
                ':email' => $inputs['email'],
            ]
        ]);
        if($result){
            $model = Member::model()->findByPk($result->id);
            $model->verification_code = $inputs['verification_code'];
            if (!$model->update()) {
                $model->addError('update_fail', 'forget verification_code update error');
                foreach ($model->getErrors() as $error){
                    Yii::log(date("Y-m-d H:i:s")."account=>".$model->account.", forget verification_code update error：".$error[0],  CLogger::LEVEL_INFO);
                }
                return $model;
            } else {
                Yii::log(date("Y-m-d H:i:s")." forget verification_code update success account=>".$model->account,  CLogger::LEVEL_INFO);
                return $model;
            }         
        }else{
            return array();
        }
    }
    /**
     * @param $mem_id
     * @return CActiveRecord
     */
    public static function findByAccount($account)
    {
        $result = Member::model()->findAll([
            'condition' => 'account=:account',
            'params' => [
                ':account' => $account,
            ]
        ]);

        return $result;
    }

    public function update(array $inputs)
    {
        $service = new MemberService();
        $operationlogService = new OperationlogService();
        $model = $service->findByMemId($inputs['id']);

        if ($model === null) {
            $model = new Member();
            $model->addError('pk_not_found', '系統主鍵不存在');
            return $model;
        }

        $model->name = $inputs['name'];
        if(isset($inputs['password']))
            $model->password = md5($inputs['password']);
        if(isset($inputs['email']))
            $model->email = $inputs['email'];
        $model->gender = $inputs['gender'];
        $model->birthday = $inputs['birthday']?$inputs['birthday']:"0000-00-00";
        $model->phone = $inputs['phone'];
        $model->mobile = $inputs['mobile'];
        $model->member_type = $inputs['member_type'];
        $model->nationality = $inputs['nationality'];
        $model->county = $inputs['county'];
        $model->town = $inputs['town'];
        $model->address = $inputs['address'];
        $model->active = $inputs['active'];;
        $model->update_by = Yii::app()->session['uid'];
        $model->update_date = date('Y-m-d H:i:s');
        $model = $this->validate($model);

        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            $success = $model->update();
        } else {
            return $model;
        }

        if ($success === false) {
            $model->addError('update_fail', '修改失敗');
            $motion = "更新會員";
            $log = "更新 會員帳號 = " . $model->account . "；會員名稱 = " . $inputs["name"];
            $operationlogService->create_operationlog( $motion, $log, 0 );
            return $model;
        }
        $motion = "更新會員";
        $log = "更新 會員帳號 = " . $model->account . "；會員名稱 = " . $inputs["name"];
        $operationlogService->create_operationlog( $motion, $log );
        return $model;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateMemberPassword(array $inputs)
    {
        $operationlogService = new OperationlogService();
        $model = Member::model()->findByPk($inputs["id"]);
        $model->password = $inputs["password"];

        if (!$this->validatePassword($model)) {
            return $model;
        }

        if ($inputs["password_confirm"] === "" || $inputs["password"] !== $inputs["password_confirm"]) {
            $model->addError('password_confirm', '確認密碼錯誤, 請重新輸入');
            $motion = "更新會員密碼";
            $log = "更新 會員流水號 = " . $inputs["id"] ;
            $operationlogService->create_operationlog( $motion, $log, 0 );
            return $model;
        }

        if (!$model->hasErrors()) {
            $model->password = md5($model->password);
            $model->update();
            $motion = "更新會員密碼";
            $log = "更新 會員流水號 = " . $inputs["id"] ;
            $operationlogService->create_operationlog( $motion, $log );
        }

        return $model;
    }

    public function updateMemberStatus(array $inputs)
    {
        $model = Member::model()->findByPk($inputs["id"]);
        $model->status = 1;
        $model->stop_card_datetime = date('Y-m-d H:i:s');
        $model->stop_card_remark = '系統自動停權(佔用他人預約時段十次)';

        if (!$model->hasErrors()) {
            $model->update();
        }

        return $model;
    }

    public function updateDevicePermission(array $inputs)
    {
        $model = Member::model()->findByPk($inputs["id"]);
        $model->device_permission = json_encode($inputs["device_permission"]);
        $model->device_permission_type = json_encode($inputs["device_permission_type"]);

        if (!$model->hasErrors()) {
            $model->device_permission = $model->device_permission;
            $model->device_permission_type = $model->device_permission_type;
            $model->update();
        }

        return $model;
    }

    public function delete($id)
    {
        $operationlogService = new OperationlogService();
        $model = $this->findByMemId($id);

        if ($model !== null) {
            $success = $model->delete();
        } else {
            return false;
        }

        if ($success === false) {
            $motion = "刪除會員";
            $log = "刪除 會員流水號 = " . $id;
            $operationlogService->create_operationlog( $motion, $log, 0 );
            return false;
        } else {
            $motion = "刪除會員";
            $log = "刪除 會員流水號 = " . $id;
            $operationlogService->create_operationlog( $motion, $log );
            $success === true;
        }


    }

    public static function findByMemberAccount($account, $password)
    {
        return Member::model()->find([
            'condition' => 'account=:account and password=:password ',
            'params' => [
                ':account' => $account,
                ':password' => $password
            ]
        ]);
    }

    public static function findByMemberOfLevel($user_group)
    {
        return Member::model()->findAll([
            'condition' => 'user_group=:user_group',
            'params' => [
                ':user_group' => $user_group
            ]
        ]);
    }


    // 取出資料庫中,所有教授
    public function get_all_professor($group)
    {
        $result = Member::model()->findAll([
            'select' => '*',
            'condition' => 'user_group=:user_group',
            'params' => [
                ':user_group' => $group,
            ]
        ]);
        return $result;
    }

    // 取出特定條件會員:群組+教授
    public function get_mem_by_gp($grp, $pro)
    {
        if (empty($pro)) {

            $data = Yii::app()->db->createCommand()
                ->select('*')
                ->from('member ')
                ->where(array('in', 'grp_lv2', $grp))
                ->queryAll();

        } else {
            $data = Yii::app()->db->createCommand()
                ->select('*')
                ->from('member ')
                ->where(array('in', 'grp_lv2', $grp))
                ->andWhere("professor=$pro")
                ->queryAll();
        }
        return $data;
    }

    // 取出特定條件會員:群組+教授
    public function get_mem_by_grp_lv2($grp, $pro)
    {
        if (empty($pro) and empty($grp)) {
            $data = [];

        } else if(!empty($pro) and empty($grp)){
            $data = Yii::app()->db->createCommand()
                ->select('*')
                ->from('member ')
                ->where("professor=$pro")
                ->queryAll();
        } else if(empty($pro) and !empty($grp)){
            $data = Yii::app()->db->createCommand()
                ->select('*')
                ->from('member ')
                ->where(array('in', 'grp_lv1', $grp))
                ->queryAll();
        }else{
            $data = Yii::app()->db->createCommand()
                ->select('*')
                ->from('member ')
                ->where(array('in', 'grp_lv1', $grp))
                ->andWhere("professor=$pro")
                ->queryAll();
        }
        return $data;
    }

    // 取出特定條件會員:群組+教授
    public function get_mem_by_grp_lv1($grp, $pro)
    {
        if (empty($pro) and empty($grp)) {
            $data = [];

        } else if(!empty($pro) and empty($grp)){
            $data = Yii::app()->db->createCommand()
                ->select('*')
                ->from('member ')
                ->where("professor=$pro")
                ->queryAll();
        } else if(empty($pro) and !empty($grp)){
            $data = Yii::app()->db->createCommand()
                ->select('*')
                ->from('member ')
                ->where(array('in', 'grp_lv1', $grp))
                ->queryAll();
        }else{
            $data = Yii::app()->db->createCommand()
                ->select('*')
                ->from('member ')
                ->where(array('in', 'grp_lv1', $grp))
                ->andWhere("professor=$pro")
                ->queryAll();
        }
        return $data;
    }

    // 取出特定條件會員:群組+教授
    public function get_mem_by_gp_with_deviceRecord($grp, $pro, $date_start, $date_end)
    {
        if (empty($pro)) {

            $data = Yii::app()->db->createCommand()
                ->select('m.*')
                ->from('member m')
                ->leftjoin('device_record d','d.card = m.card_number')
                ->where(array('in', 'm.grp_lv2', $grp))
                ->andwhere('d.use_date>= :date_start',array(':date_start'=>$date_start))
                ->andwhere('d.use_date <= :date_end',array(':date_end'=>$date_end))
                ->queryAll();

        } else {
            $data = Yii::app()->db->createCommand()
                ->select('m.*')
                ->from('member m')
                ->leftjoin('device_record d','d.card = m.card_number')
                ->where(array('in', 'm.grp_lv2', $grp))
                ->andwhere('d.use_date>= :date_start',array(':date_start'=>$date_start))
                ->andwhere('d.use_date <= :date_end',array(':date_end'=>$date_end))
                ->andWhere("m.professor=$pro")
                ->group("m.id")
                ->queryAll();
        }
        return $data;
    }

    public static function findMemberlistWithDeviceRecord($date_start,$date_end){
        $data = Yii::app()->db->createCommand()
            ->select('m.*')
            ->from('member m')
            ->join('device_record d','d.card = m.card_number')
            ->where('d.use_date>= :date_start',array(':date_start'=>$date_start))
            ->andwhere('d.use_date <= :date_end',array(':date_end'=>$date_end))
            ->order('create_date desc')
            ->queryAll();
        return $data;
    }
    public static function findMemberlistarr(){

        $data = Yii::app()->db->createCommand()
              ->select('*')
              ->from('member ')
              ->order('create_date desc')
              ->queryAll();
        return $data;
    }


    public function updatecardnum( $id , $card ){

        $post=Member::model()->findByPk($id);
        $post->card_number=$card;
        if($post->save()){
            return true;
        }else{
            return false;
        }
    }
    public function get_mem_by_professor($pro){
        
        $user = Yii::app()->db->createCommand()
        ->select('*')
        ->from('member m')
        ->where('professor=:professor', array(':professor'=>$pro))
        ->queryAll();

        /*$redata = array();

        foreach ($user as $key => $value) {
            array_push($redata, $value['id']);
        }*/

        return $user;
    }    
    public function findAllProfessor(){
        return Member::model()->findAll([
            'condition' => 'professor=:professor',
            'params' => [
                ':professor' => 0
            ]
        ]);
    }
    public function findProfessorMember( $professor ){
        return Member::model()->findAll([
            'select' =>'id',
            'condition' => 'professor=:professor',
            'params' => [
                ':professor' => $professor,
            ]
        ]);
    }

    public function findUserDevicePermissionType($userId){
        $model = $this->findByMemId($userId);
        $permissionArr = [];
            $permissionArr = json_decode($model->device_permission_type);

        return $permissionArr;
    }
    public function findMemberAddressBook($member_id){
        return Memberaddressbook::model()->find([
            'condition' => 'member_id=:member_id',
            'params' => [
                ':member_id' => $member_id,
            ]
        ]);
    }

    public function create_member_address_book($inputs){
        $member_address_book = new Memberaddressbook;
        $member_address_book->member_id = $inputs['member_id'];
        $member_address_book->mobile = $inputs['mobile'];
        $member_address_book->nationality = $inputs['nationality'];
        $member_address_book->country = $inputs['county'];
        $member_address_book->town = $inputs['town'];
        $member_address_book->codezip = $inputs['zipcode'];
        $member_address_book->address = $inputs['address'];
        $member_address_book->invoice_number = $inputs['invoice_number'];
        $member_address_book->invoice_title = $inputs['invoice_title'];
        $member_address_book->invoice_category = $inputs['invoice_category'];
        if ($member_address_book->save()){
            return true;
        }else{
            return false;
        }
    }
    public function updateMemberAddressBook($member_id,$inputs){
        $member_address_book = Memberaddressbook::model()->findByAttributes([
            'member_id' => $member_id
        ]);
        if($member_address_book){
            $member_address_book->mobile = $inputs['mobile'];
            $member_address_book->nationality = $inputs['nationality'];
            $member_address_book->country = $inputs['county'];
            $member_address_book->town = $inputs['town'];
            $member_address_book->codezip = $inputs['zipcode'];
            $member_address_book->address = $inputs['address'];
            $member_address_book->invoice_number = $inputs['invoice_number'];
            $member_address_book->invoice_title = $inputs['invoice_title'];
            $member_address_book->invoice_category = $inputs['invoice_category'];
            if($member_address_book->save()){
                Yii::log("member_address_book update success member => {$member_id}", CLogger::LEVEL_INFO);
                return true;
            }else{
                Yii::log("member_address_book update fail member => {$member_id}", CLogger::LEVEL_INFO);
                return false;
            }
        }else{
            $create = $this->create_member_address_book($inputs);
            if($create){
                Yii::log("member_address_book create success member => {$member_id}", CLogger::LEVEL_INFO);
                return true;
            }else{
                Yii::log("member_address_book create fail member => {$member_id}", CLogger::LEVEL_INFO);
                return false;
            }
        }
    }

    public function listMemberFavorite($member_id){
        $data = array();
        if( $member_id != '' ){
            $sql = "(SELECT mf.*,s.single_id as image_id FROM `member_favorite` mf INNER JOIN single s on mf.single_id=s.single_id where mf.member_id=".$member_id." and mf.status=1 and search_type=1 order by create_time desc) union 
            (SELECT mf.*,b.single_id as image_id FROM `member_favorite` mf INNER JOIN book b on mf.single_id=b.book_id where mf.member_id=".$member_id." and mf.status=1 and search_type=2 and b.status=1 order by create_time desc) union
            (SELECT mf.*,b.uuid_name as image_id FROM `member_favorite` mf INNER JOIN video b on mf.single_id=b.video_id where mf.member_id=".$member_id." and mf.status=1 and search_type=3 and b.status=1 order by create_time desc)
            ";
            $data = Yii::app()->db->createCommand($sql)->queryAll();
        }
        return $data;
    }

    public function add_favorite($single_id,$member_id,$search_type){
        $data = Memberfavorite::model()->find([
            'condition' => 'member_id=:member_id and single_id=:single_id and search_type=:search_type',
            'params' => [
                ':member_id' => $member_id,
                ':single_id' => $single_id,
                ':search_type' => $search_type,
            ]
        ]);
        if(!$data){
            $model = new Memberfavorite();
            $model->member_id = $member_id;
            $model->single_id = $single_id;
            $model->search_type = $search_type;
            $model->status = 1;
            $model->create_time = date('Y-m-d H:i:s');
            if($model->save()){
                return true;
            }else{
                foreach ($model->getErrors() as $error){
                    Yii::log(date("Y-m-d H:i:s")."member_id =>".$member_id.", add_favorite error：".$error[0],  CLogger::LEVEL_INFO);
                }
                return false;
            }
        }else{
            if($data['status'] == 0){
                $model = Memberfavorite::model()->findByPk($data['member_favorite_id']);
                $model->status = 1;
                $model->update_time = date('Y-m-d H:i:s');
                if($model->save()){
                    return true;
                }else{
                    foreach ($model->getErrors() as $error){
                        Yii::log(date("Y-m-d H:i:s")."member_id =>".$member_id.", remove_favorite error：".$error[0],  CLogger::LEVEL_INFO);
                    }
                    return false;
                }
            }
            return true;
        }
    }
    public function remove_favorite($single_id,$member_id,$search_type){
        $data = Memberfavorite::model()->find([
            'condition' => 'member_id=:member_id and single_id=:single_id and search_type=:search_type',
            'params' => [
                ':member_id' => $member_id,
                ':single_id' => $single_id,
                ':search_type' => $search_type,
            ]
        ]);
        if($data){
            $model = Memberfavorite::model()->findByPk($data['member_favorite_id']);
            $model->status = 0;
            $model->update_time = date('Y-m-d H:i:s');
            if($model->save()){
                return true;
            }else{
                foreach ($model->getErrors() as $error){
                    Yii::log(date("Y-m-d H:i:s")."member_id =>".$member_id.", remove_favorite error：".$error[0],  CLogger::LEVEL_INFO);
                }
                return false;
            }
        }else{
            return true;
        }
    }
}
