<?php
class DoorgrouppermissionService
{
    public function findDoorgrouppermissions()
    {
        $result = Doorgrouppermission::model()->findAll();

        return $result;
    }

    /**
     * @param $systems
     * @param $powers
     * @return array
     */
    public function getDoorgrouppermissionCheckList($systems, $powers)
    {
        $checkList = [];
        foreach ($systems as $index => $system) {
            $checkList[$index]['system_number'] = $system->system_number;
            $checkList[$index]['system_name'] = $system->system_name;
            $checkList[$index]['system_type'] = $system->system_type;
            $checkList[$index]['system_range'] = $system->system_range;
            $checkList[$index]['powers'] = [];

            foreach($powers as $power) {
                if ($power->power_master_number === $system->system_number) {
                    $checkList[$index]['powers'][] = $power;
                }
            }
        }

        return $checkList;
    }

    public function getUpdateCheckList($doors, array $groupList)
    {
        //檢查門是否在門組當中
        $doors_checked_list = [];
        foreach ($doors as $index => $value) {

            if (in_array($value['station'], $groupList)) {
                $door_checked_type = 'checked';
            } else {
                $door_checked_type = '';
            }

            $doors_checked_list[$index] = [
                'door_number' => $value['station'],
                'door_name' => $value['name'],
                'door_type' => $value['status'],
                'door_checked_type' => $door_checked_type
            ];
        }

        return $doors_checked_list;
    }

    /**
     * @param array $input
     * @return Doorgrouppermission
     */
    public function create(array $inputs)
    {
        $group = new Doorgrouppermission();
        $group->group_name = $inputs['group_name'];
        $group->group_number = $inputs['group_number'];
        $group->group_list = $inputs['group_list'];

        if (!$group->validate()) {
            return $group;
        }

        if ($this->groupNumberExist($group->group_number)) {
            $group->addError('group_exist', '新增群組功能失敗，功能編號已經存在');
            return $group;
        }

        if (!$group->hasErrors()) {
            $success = $group->save();
        }

        if ($success === false) {
            $group->addError('save_fail', '新增群組功能失敗');
            return $group;
        }

        return $group;
    }

    /**
     * @param $group_number
     * @return bool
     */
    public function groupNumberExist($door_group_permission_number)
    {
        $result = Doorgrouppermission::model()->find([
            'select' => 'door_group_permission_number',
            'condition'=>'door_group_permission_number=:door_group_permission_number',
            'params'=> [
                ':door_group_permission_number' => $door_group_permission_number,
            ]
        ]);

        return ($result == false) ? false : true;
    }

    public function doorGroupNumberExist($door_group_permission_number)
    {
        $result = Doorgrouppermission::model()->find([
            'select' => '*',
            'condition'=>'door_group_permission_number=:door_group_permission_number',
            'params'=> [
                ':door_group_permission_number' => $door_group_permission_number,
            ]
        ]);

        return ($result == false) ? false : $result;
    }

    public function update(array $inputs)
    {
        $model = Doorgrouppermission::model()->findByPk($inputs["id"]);

        $model->door_group_permission_number = $inputs["door_group_permission_number"];
        $model->door_group_permission_name = $inputs["door_group_permission_name"];
        $model->door_group_permission_list = $inputs["door_group_permission_list"];

        if ($model->validate()) {
            $model->update();
        }

        return $model;
    }

    public function getDoorgrouppermissionWithAccountList()
    {
        $accounts = ExtDoorgrouppermission::model()->getDoorgrouppermissionJoinAccountOnDoorgrouppermissionNum();

        $groups = [];
        foreach ($accounts as $account) {
            $groups[$account['group_number']]['group_name'] = $account['group_name'];
            $groups[$account['group_number']]['account_info'][] = $account;
        }
        return $groups;

    }

    public function getDoorgrouppermissionWithAdviserList()
    {
        $advisers = ExtDoorgrouppermission::model()->getDoorgrouppermissionJoinAdviserOnDoorgrouppermissionNum();

        $groups = [];
        foreach ($advisers as $adviser) {
            $groups[$adviser['group_number']]['group_name'] = $adviser['group_name'];
            $groups[$adviser['group_number']]['account_info'][] = $adviser;
        }

        return $groups;

    }


    public function getDoorgrouppermissionWithMemberList()
    {
        $members = ExtDoorgrouppermission::model()->getDoorgrouppermissionJoinMemberOnDoorgrouppermissionNum();

        $groups = [];
        foreach ($members as $member) {
            $groups[$member['group_number']]['group_name'] = $member['group_name'];
            $groups[$member['group_number']]['account_info'][] = $member;
        }

        return $groups;

    }

}