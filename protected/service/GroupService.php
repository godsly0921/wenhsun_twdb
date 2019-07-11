<?php
class GroupService
{
    public function findGroups()
    {
        $result = Group::model()->findAll();

        return $result;
    }

    /**
     * @param $systems
     * @param $powers
     * @return array
     */
    public function getGroupCheckList($systems, $powers)
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

    public function getUpdateCheckList($systems, $powers, array $groupList)
    {
        //檢查系統是否在權限當中
        $system_checked_list = [];
        foreach ($systems as $index => $system) {

            if (in_array($system->system_number, $groupList)) {
                $system_checked_type = 'checked';
            } else {
                $system_checked_type = '';
            }

            $system_checked_list[$index] = [
                'system_number' => $system->system_number,
                'system_name' => $system->system_name,
                'system_type' => $system->system_type,
                'system_checked_type' => $system_checked_type
            ];

            //檢查功能是否在權限當中
            $power_checked_list = [];
            foreach ($powers as $power) {

                if (in_array($power->power_number, $groupList)) {
                    $power_checked_type = 'checked';
                } else {
                    $power_checked_type = '';
                }

                if ($power->power_master_number === $system->system_number) {

                    $system_checked_list[$index]['powers'][] = [
                        'power_number' => $power->power_number,
                        'power_name' => $power->power_name,
                        'power_master_number' => $power->power_master_number,
                        'power_checked_type' => $power_checked_type
                    ];
                }
            }
        }

        return $system_checked_list;
    }

    /**
     * @param array $input
     * @return Group
     */
    public function create(array $inputs)
    {
        $group = new Group();
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
    public function groupNumberExist($id)
    {
        $result = Group::model()->find([
            'select' => 'id',
            'condition'=>'id=:id',
            'params'=> [
                ':id' => $id,
            ]
        ]);

        return ($result == false) ? false : true;
    }

    public function groupById($id)
    {
        $result = Group::model()->find([
            'select' => 'group_number',
            'condition'=>'id=:id',
            'params'=> [
                ':id' => $id,
            ]
        ]);

        return ($result == false) ? 9999 : $result;
    }

    public function update(array $inputs)
    {
        $group = Group::model()->findByPk($inputs["group_id"]);

        $group->group_number = $inputs["group_number"];
        $group->group_name = $inputs["group_name"];
        $group->group_list = $inputs["group_list"];

        if ($group->validate()) {
            $group->update();
        }

        return $group;
    }

    public function getGroupWithAccountList()
    {
        $accounts = ExtGroup::model()->getGroupJoinAccountOnGroupNum();

        $groups = [];
        foreach ($accounts as $account) {
            $groups[$account['group_number']]['group_name'] = $account['group_name'];
            $groups[$account['group_number']]['account_info'][] = $account;
        }
        return $groups;

    }

    public function getGroupWithAdviserList()
    {
        $advisers = ExtGroup::model()->getGroupJoinAdviserOnGroupNum();

        $groups = [];
        foreach ($advisers as $adviser) {
            $groups[$adviser['group_number']]['group_name'] = $adviser['group_name'];
            $groups[$adviser['group_number']]['account_info'][] = $adviser;
        }

        return $groups;

    }


    public function getGroupWithMemberList()
    {
        $members = ExtGroup::model()->getGroupJoinMemberOnGroupNum();

        $groups = [];
        foreach ($members as $member) {
            $groups[$member['group_number']]['group_name'] = $member['group_name'];
            $groups[$member['group_number']]['account_info'][] = $member;
        }

        return $groups;

    }

}