<?php
class SystemService
{

    public function findSystems()
    {
        $result = System::model()->findAll();

        return $result;
    }

    public function create(array $inputs)
    {
        $system = new System();
        $system->system_number = $inputs['system_number'];
        $system->system_name = $inputs['system_name'];
        $system->system_controller = $inputs['system_controller'];
        $system->system_range = $inputs['system_range'];
        $system->system_type = $inputs['system_type'];

        if (!$system->validate()) {
            return $system;
        }

        if ($this->systemNumberExist($system->system_number)) {
            $system->addError('system_exist', '系統編號已存在');
            return $system;
        }

        if (!$system->hasErrors()) {
            $success = $system->save();
        }

        if ($success === false) {
            $system->addError('save_fail', '新增系統編號/名稱失敗');
            return $system;
        }

        return $system;
    }

    /**
     * @param $systemNumber
     * @return bool
     */
    public function systemNumberExist($systemNumber)
    {
        $systemExist = System::model()->findAll([
            'select' => 'system_number',
            'condition'=>'system_number=:system_number',
            'params'=> [
                ':system_number' => $systemNumber,
            ]
        ]);

        return $systemExist == false ? false : true;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord|System
     */
    public function update(array $inputs)
    {
        $system = System::model()->findByPk($inputs['system_id']);

        if ($system === null) {
            $system = new System();
            $system->addError('pk_not_found', '系統主鍵不存在');
            return $system;
        }

        $system->system_number = $inputs['system_number'];
        $system->system_name = $inputs['system_name'];
        $system->system_controller = $inputs['system_controller'];
        $system->system_range = $inputs['system_range'];
        $system->system_type = $inputs['system_type'];

        if ($system->validate()) {
            $success = $system->update();
        }

        if ($success === false) {
            $system->addError('update_failed', '系統更新失敗');
            return $system;
        }

        return $system;
    }
}