<?php
class Exceler{
	/**
	 * Excel檔案驗證
	 * check_required_cols：依輸入array驗證第幾欄位應有值
	 * check_col_name_row：標記第幾列為標題列(-1即不檢查)
	 * check_col_names：依輸入array驗證標題列欄位名(輸入為空陣列即不檢查)
	 */
	public static function check_excel($excel, $check_required_cols=array(), $check_col_name_row=-1, $check_col_names=array()){
		$result = '';
		if(!file_exists($excel)){
			$result = '檔案不存在';
		}else{
			$ex_name_ext = explode('.', $excel);
			if($ex_name_ext[count($ex_name_ext)-1]!='xls'){
				if($ex_name_ext[count($ex_name_ext)-1]!='xlsx'){
					$result = '非Excel檔案';
				}else{
					$result = '不支援新式Excel檔案格式';
				}
			}else{
				$excel_datas = new Spreadsheet_Excel_Reader;
				$excel_datas->setOutputEncoding('UTF-8');
				$excel_datas->read($excel);
				if(isset($excel_datas->sheets[0])){
					$sheet = $excel_datas->sheets[0];
					//如果有輸入必填陣列，進行必填欄位資料有無驗證
					if(count($check_required_cols)>0){
						$check_data_message = '';
						//檢查必填列資料
						for($i = $check_col_name_row+2 ; $i < count($sheet['cells']) && $check_data_message=='' ; $i++){
							$row = $sheet['cells'][$i];
							for($j = 0 ; $j < count($check_required_cols) && $check_data_message=='' ; $j++){
								$col_data = isset($row[$check_required_cols[$j]+1])?$row[$check_required_cols[$j]+1]:'';
								if($col_data==''){
									$check_data_message = '資料：第'.($check_required_cols[$j]+1).'欄，第'.$i.'列，內容不得為空。';
								}
							}
						}
						if($check_data_message!=''){
							$result = $check_data_message;
						}
					}
					//如果有輸入標題列，有輸入標題資料，則進行標題驗證
					if($check_col_name_row!=-1 && count($check_col_names)!=0){
						if($sheet['numRows']>1){
							$check = true;
							//取得第一列資料
							$row1 = $sheet['cells'][$check_col_name_row+1];
							//檢查第一列資料，是否符合檢查列內容
							for($i = 0 ; $i < count($check_col_names) ; $i++){
								if(!isset($check_col_names[$i]) || !isset($row1[$i+1])){
									$check = false;
								}else{
									if($check_col_names[$i] != $row1[$i+1]){
										$check = false;
									}
								}
							}
							if(!$check){
								$result = '第一列內容不符合輸入規定';
							}
						}
					}
				}else{
					$result = '第一分頁無資料';
				}
			}
		}
		return $result;
	}
	
	/**
	 * Excel檔案輸出資料，data_row：資料開始列(預設為1，即第二列開始為資料列)
	 */
	public static function read_excel($excel, $data_row=0){
		$excel_datas = new Spreadsheet_Excel_Reader;
		$excel_datas->setOutputEncoding('UTF-8');
		$excel_datas->read($excel);
		if(isset($excel_datas->sheets[0])){
			$sheet = $excel_datas->sheets[0];
			$results = array();
			for($i = $data_row+1 ; $i <= count($sheet['cells']) ; $i++){
				$row_datas = array();
				for($j = 1 ; $j <= count($sheet['cells'][1]) ; $j++){
					if(isset($sheet['cells'][$i][$j])){
						$row_datas[] = $sheet['cells'][$i][$j];
					}else{
						$row_datas[] = '';
					}
				}
				$results[] = $row_datas;
			}
		}
		return $results;
	}
}
?>