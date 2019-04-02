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


    public function create(array $inputs)
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
        $model->status = $inputs['status'];
        $model->address = $inputs["address"];
        $model->card_number = $inputs["card_number"];
        $model->grp_lv1 = $inputs['grp_lv1'];
        $model->grp_lv2 = $inputs['grp_lv2'];
        $model->user_group = $inputs['level'];
        $model->professor = $inputs['professor'];
        $model->stop_card_datetime = $inputs['stop_card_datetime'];
        $model->stop_card_remark = $inputs['stop_card_remark'];
        $model->stop_card_people = $inputs['stop_card_people'];


        if ($inputs["password_confirm"] === "" || $inputs["password"] !== $inputs["password_confirm"]) {
            $model->addError('password_confirm', '確認密碼錯誤, 請重新輸入');
            return $model;
        }

        $model->password = md5($inputs['password']);
        $model->stop_card_datetime = date('0000-00-00 00:00:00');
        $model->create_date = date('Y-m-d H:i:s');
        $model->edit_date = date('Y-m-d H:i:s');
        //$model->status = 0;

        $model = $this->validate($model);

        if (!$model->save()) {
            $model->addError('update_fail', '新增使用者失敗');
            return $model;
        } else {
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

    private function validate(Membertw $model)
    {
//        if (!$this->memberValidate->validateChineseName($member->mem_cname)) {
//            $member->addError('mem_cname_invalid', '請正確輸入中文姓名');
//        }
//
        if (!$this->memberValidate->validateEnglishName($model->account)) {
            $member->addError('mem_ename_invalid', '請正確輸入帳號名稱');
        }
//
//        if (!$this->memberValidate->validateBirth($member->mem_birthdate)) {
//            $member->addError('mem_birth_invalid', '出生年月日不正確');
//        }
//
//        if (!$this->memberValidate->validatePhone($member->mem_phone)) {
//            $member->addError('mem_phone_invalid', '電話號碼格式不正確');
//        }
//
//        if (!$this->memberValidate->validatePhone($member->mem_mobile)) {
//            $member->addError('mem_mobile_invalid', '手機號碼格式不正確');
//        }
//
//        if (!$this->memberValidate->validateEmail($member->mem_email)) {
//            $member->addError('mem_email_invalid', 'E-Mail格式不正確');
//        }

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
        $model = $service->findByMemId($inputs['id']);

        if ($model === null) {
            $model = new Member();
            $model->addError('pk_not_found', '系統主鍵不存在');
            return $model;
        }

        $model->id = $inputs['id'];
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
        $model->status = $inputs['status'];
        $model->address = $inputs["address"];
        //$model->card_number = $inputs["card_number"];
        $model->grp_lv1 = $inputs['grp_lv1'];
        $model->grp_lv2 = $inputs['grp_lv2'];
        $model->professor = $inputs['professor'];
        $model->stop_card_datetime = $inputs['stop_card_datetime'];
        $model->stop_card_remark = $inputs['stop_card_remark'];
        $model->stop_card_people = $inputs['stop_card_people'];

        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {

            $date = new DateTime();
            $model->edit_date = $date->format('Y-m-d H:i:s');
            $success = $model->update();

        } else {
            return $model;
        }

        if ($success === false) {
            $model->addError('update_fail', '修改失敗');
            return $model;
        }

        return $model;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateMemberPassword(array $inputs)
    {
        $model = Member::model()->findByPk($inputs["id"]);
        $model->password = $inputs["password"];

        if (!$this->validatePassword($model)) {
            return $model;
        }

        if ($inputs["password_confirm"] === "" || $inputs["password"] !== $inputs["password_confirm"]) {
            $model->addError('password_confirm', '確認密碼錯誤, 請重新輸入');
            return $model;
        }

        if (!$model->hasErrors()) {
            $model->password = md5($model->password);
            $model->update();
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
        $model = $this->findByMemId($id);

        if ($model !== null) {
            $success = $model->delete();
        } else {
            return false;
        }

        if ($success === false) {
            return false;
        } else {
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
}
