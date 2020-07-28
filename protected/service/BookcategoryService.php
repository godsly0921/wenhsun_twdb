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
            ORDER BY T2.sort ASC";
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
}
?>