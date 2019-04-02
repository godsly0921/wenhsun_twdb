<?php
class _R_cus_api{

  // 檢測所有需要的key是否存在
  public function array_keys_exists(array $keys, array $arr) {
    
    return !array_diff_key(array_flip($keys), $arr);
  }

  public function array_keys_exists_retrun(array $keys, array $arr) {
    
    if( !array_diff_key(array_flip($keys), $arr) ){

    	return !array_diff_key(array_flip($keys), $arr);
    }else{
        return array_diff_key(array_flip($keys), $arr);
    } 

  }  

  // 錯誤回報
  public function json_err($num,$val1="",$val2=""){
    switch ($num) {
      case 0:
        echo json_encode(['result'=>false,
                          'msg'=>'post_only'
                         ]);
        break;      
      case 1:
        echo json_encode(['result'=>false,
                         'msg'=>'be short of params'
                         ]);
        break;
      case 2:
        echo json_encode(['result'=>false,
                         'msg'=>"$val1 not equal to $val2"
                         ]);
        break;
      case 3:
        echo json_encode(['result'=>false,
                         'msg'=>"process error"
                         ]);
        break;
      case 4:
        echo json_encode(['result'=>false,
            'msg'=>"Currently for visitors"
        ]);
        break;
    }
  }

}
?>