<?php
/**
 * @author Neil Kuo
 * @copyright Neil Kuo
 * @since 2015-07-01
 * @return 網站後台-群組(設定登入身份)資料控制與傳遞
 */
class GroupController extends Controller
{
    public $layout = "//layouts/back_end";

	public function actionIndex() {
        #echo Yii::app()->session['group_list_session_jsons'];exit();
        $groupService = new GroupService();
        $groups = $groupService->findGroups();

        $data = ["groups" => $groups];

        $this->render('index', $data);
	}

	public function actionCreate()
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostCreate() : $this->doGetCreate();
	}

    private function doPostCreate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');
        $input['group_name'] = filter_input(INPUT_POST, 'group_name');
        $systemList = filter_input(INPUT_POST, 'system_list', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        //if uncheck any check box, then it will be null
        $groupList = filter_input(INPUT_POST, 'group_list', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        $input['group_list'] = null;
        $input['system_list'] = null;
        $group_list = [];
        $system_list = [];
        if ($groupList !== null) {
            foreach ($groupList as $lists) {
                foreach ($lists as $list) {
                    $group_list[] = $list;
                }
            }
        }
        if ($systemList !== null) {
            foreach ($systemList as $lists) {
                foreach ($lists as $list) {
                    $system_list[] = $list;
                }
            }
        }
        sort($group_list);
        sort($system_list);
        $input['group_list'] = implode(',', $group_list);
        $input['system_list'] = implode(',', $system_list);
        $groupService = new GroupService();
        $groupModel = $groupService->create($input);

        if ($groupModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $groupModel->getErrors();
            $this->redirect('create');
            return;
        }

        $this -> redirect('index');
    }

    private function doGetCreate()
    {
        CsrfProtector::putToken();

        $powers = ExtPower::model()->power_list();
        $systems = ExtSystem::model()->system_list();

        $groupService = new GroupService();
        $checkList = $groupService->getGroupCheckList($systems, $powers);

        $data = ['powers' => $powers, 'systems'=> $systems, 'checkList' => $checkList];

        $this->render('create', $data);
        $this->clearMsg();


    }

    /**
     * 群組刪除
     *
     */
	public function actionDelete($id)
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostDelete($id) : $this->redirect(['index']);
	}

    private function doPostDelete($id)
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $groups = Group::model()->findByPk($id);

        if ($groups !== null) {
            $groups -> delete();
            $this->redirect(['index']);
        }
    }
	

    /**
     * 群組功能更新
     *
     * @param $id
     */
	public function actionUpdate($id = null)
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostUpdate() : $this->doGetUpdate($id);
	}

    private function doPostUpdate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs['group_name'] = filter_input(INPUT_POST, 'group_name');
        $inputs['group_number'] = filter_input(INPUT_POST, 'group_number');
        //if uncheck any check box, then it will be null
        $groupList = filter_input(INPUT_POST, 'group_list', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $systemList = filter_input(INPUT_POST, 'system_list', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        $group_list = [];
        $system_list = [];
        if ($groupList !== null) {
            foreach ($groupList as $lists) {
               if(is_array($lists)) {
                   foreach ($lists as $list) {
                       if (isset($list)) {
                           $group_list[] = $list;
                       }
                   }

               }
            }
        }

        if ($systemList !== null) {
            foreach ($systemList as $lists) {
               if(is_array($lists)) {
                   foreach ($lists as $list) {
                       if (isset($list)) {
                           $system_list[] = $list;
                       }
                   }

               }
            }
        }
        sort($system_list);
        sort($group_list);
        $inputs['group_list'] = implode(',', $group_list);
        $inputs['system_list'] = implode(',', $system_list);
        $groupService = new GroupService();
        $groupModel = $groupService->update($inputs);

        if ($groupModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $groupModel->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '群組修改成功';
        }

        $this->redirect('update/'.$inputs['group_number']);
    }

    private function doGetUpdate($id)
    {
        $systems = ExtSystem::model()->system_list();
        $powers = ExtPower::model()->power_list();
        $groups = ExtGroup::model()->findByPk($id);
        $group_list = explode(",", $groups->group_list);
        $system_list = explode(",", $groups->system_list);

        // $groupService = new GroupService();
        // $system_checked_list = $groupService->getUpdateCheckList($systems, $powers, $group_list);
        foreach ($systems as $system) {//檢查系統是否在權限當中。
            if(in_array($system->system_number, $system_list)){
                $system_checked_type = 'checked';
            }else{
                $system_checked_type = '';
            }
            $system_checked_list[] = array('system_number' => $system->system_number, 'system_name' => $system->system_name, 'system_type' => $system->system_type,'system_checked_type' => $system_checked_type);
        }
        foreach ($powers as $power) {//檢查功能是否在權限當中。
            if(in_array($power->power_number, $group_list)){
                $power_checked_type = 'checked';
            }else{
                $power_checked_type = '';
            }
            $power_checked_list[] = array('power_number' => $power->power_number, 'power_name' => $power->power_name, 'power_master_number' => $power->power_master_number,'power_checked_type' => $power_checked_type);
        }
        $data = [
            'groups' => $groups,
            'system_checked_list'=> $system_checked_list,
            'power_checked_list' => $power_checked_list
        ];

        $this -> render('update', $data);
        $this->clearMsg();
    }

    protected function needLogin(): bool
    {
        return true;
    }
}