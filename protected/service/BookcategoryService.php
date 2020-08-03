<?php
class BookcategoryService
{
	public function findParents($parents_id){
        $sql = "SELECT T2.category_id,T2.name,T2.parents,T2.sort,T2.status
            FROM ( 
                SELECT 
                    @r AS _id, 
                    (SELECT @r := parents FROM book_category WHERE category_id = _id) AS unit, 
                    @l := @l + 1 AS lvl,
                    sort,
                    status
                FROM 
                    (SELECT @r := " . $parents_id . ", @l := 0) vars, 
                    book_category h 
                WHERE @r <> 0) T1 
            JOIN book_category T2 
            ON T1._id = T2.category_id 
            ORDER BY T2.sort,T2.parents ASC";
        $root_name = Yii::app()->db->createCommand($sql)->queryAll();
        $category_name = $category_id = $category_statu = array();
        $data = array();
        if($root_name){
            foreach ($root_name as $key => $value) {
                $category_name[] = $value['name'];
                $category_id[] = $value['category_id'];
                $category_status[] = $value['status'];
            }
            $data = array('category_name' => implode(">", $category_name), 'category_id'=>implode(">", $category_id),'category_status'=>in_array("0", $category_status)?0:1);
        }
        return $data;    
    }
    public function findAllDetailCategory(){
		$all_data = BookCategory::model()->findAll(array('order'=>'sort ASC'));
		$categoryService = new CategoryService();
        $accountService = new AccountService();
		$category_data = array();

		foreach ($all_data as $key => $value) {
			$account = $accountService -> findAccountData($value->last_updated_user);
			$root ='';
            $root_category_id = "";

            if($value->isroot == 0){

                $root_name = $this->findParents($value->parents);      

                $root_category_id = $root_name?$root_name["category_id"]:"";
                $root_name = $root_name?$root_name["category_name"]:"";
                $explode_root_name = explode(">", $root_name);
                if(count($explode_root_name)>1){
                    unset($explode_root_name[count($explode_root_name)-1]);
                }
                $root = $root_name?implode(">", $explode_root_name):'';
            }else{
                $root_name = $value->name;
                $root = $value->name;
            }
            $category_data[] = array(
                'category_id' => $value->category_id,
                'main_category' => $root, // a>b
                'category_tree' => $root_name, // a>b>c
                'main_category_id' => $root_category_id, //1>2>3
                'sub_category' => $value->isroot == 1 ?'':$value->name,
                'last_updated_user' => $account->account_name,
                'create_at' => $value->create_at,
                'update_at' => $value->update_at,
                'status' => $value->status,
                'sort' => $value->sort,
            );
		}
        return $category_data;
    }
    public function findAllCategory(){
        $data = array();
        $data = BookCategory::model()->findAll(array(
            'condition'=>'status=1',
            'order'=>'sort ASC'
        ));
        return $data;
    }

    public function findAllRootCategory(){
        $data = array();
        $data = BookCategory::model()->findAll(array(
            'condition'=>'status=1 AND isroot=1',
            'order'=>'sort ASC'
        ));
        return $data;
    }

    public function get_Allcategory_data(){
        ini_set('memory_limit','1024M');
        $category_data = array();
        $all_data = $this->findAllCategory();
        foreach ($all_data as $key => $value) {
            $parents = $this->findParents($value['category_id']);
            if(!empty($parents) && $parents['category_status'] !=0){
                $category_data[$value['category_id']] = $parents['category_name'];
            }
        }
        return $category_data;
    }

    public function findCategoryTreeString($category_id = null){
        ini_set('memory_limit','1024M');
        $category_tree = array();
        $all_data = $this->findAllCategory();
        if($category_id){
            $category_id = str_replace("'", "", $category_id);
            $category_id = explode(",", $category_id);
            foreach ($category_id as $key => $value) {

                $category_id[$key] = (int)$value;
            }
        }else{
            $category_id = array();
        }
        foreach ($all_data as $key => $value) {
            $category_tree[] = array(
                'category_id' => $value['category_id'],
                'parents' => $value['parents'],
                'text' => $value['name'],
                'multiSelect' => true,
                'checkable' => true,
                'checkedIcon' => '',
                'selectable' => true,
                'hideCheckbox' => false,
                'state' => array('checked'=>in_array($value['category_id'], $category_id)?true:false,'disabled'=>false,'expanded'=>true,'selected'=>in_array($value['category_id'], $category_id)?true:false),
                'dataAttr' => array('target'=>'#tree'),
            );
        }
        $category_html_ul = "";
        if($category_tree){
            $category_tree = $this->treeview($category_tree);
        }

        $category_tree = json_encode($category_tree);
        return $category_tree;
    }
    
    public function treeview($tree, $rootId = 0) {
        $return = array();
        foreach($tree as $leaf) {
            if($leaf['parents'] == $rootId) {
                foreach($tree as $subleaf) {
                    if($subleaf['parents'] == $leaf['category_id']) {
                        $leaf['nodes'] = $this->treeview($tree, $leaf['category_id']);
                        break;
                    }
                }
                $return[] = $leaf;
            }
        }
        return $return;
    }
}
?>