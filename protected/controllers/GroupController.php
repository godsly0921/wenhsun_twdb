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

        $input['group_number'] = filter_input(INPUT_POST, 'group_number');
        $input['group_name'] = filter_input(INPUT_POST, 'group_name');

        //if uncheck any check box, then it will be null
        $groupList = filter_input(INPUT_POST, 'group_list', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        $input['group_list'] = null;
        $tempLists = [];
        if ($groupList !== null) {
            foreach ($groupList as $lists) {
                foreach ($lists as $list) {
                    $tempLists[] = $list;
                }
            }
        }

        sort($tempLists);
        $input['group_list'] = implode(',', $tempLists);

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

        $inputs['group_id'] = filter_input(INPUT_POST, 'group_id');
        $inputs['group_name'] = filter_input(INPUT_POST, 'group_name');
        $inputs['group_number'] = filter_input(INPUT_POST, 'group_number');

        //if uncheck any check box, then it will be null
        $groupList = filter_input(INPUT_POST, 'group_list', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        $tempLists = [];
        if ($groupList !== null) {
            foreach ($groupList as $lists) {
                foreach ($lists as $list) {
                    $tempLists[] = $list;
                }
            }
        }

        sort($tempLists);
        $inputs['group_list'] = implode(',', $tempLists);

        $groupService = new GroupService();
        $groupModel = $groupService->update($inputs);

        if ($groupModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $groupModel->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '群組修改成功';
        }

        $this->redirect('update/'.$inputs['group_id']);
    }

    private function doGetUpdate($id)
    {
        $systems = ExtSystem::model()->system_list();
        $powers = ExtPower::model()->power_list();
        $groups = ExtGroup::model()->findByPk($id);

        $group_list = explode(",", $groups->group_list);

        $groupService = new GroupService();
        $system_checked_list = $groupService->getUpdateCheckList($systems, $powers, $group_list);

        $data = [
            'groups' => $groups,
            'system_checked_list'=> $system_checked_list,
        ];

        $this -> render('update', $data);
        $this->clearMsg();
    }

    protected function needLogin(): bool
    {
        return true;
    }
}