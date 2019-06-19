<?php
/**
 * imagemagick套件組合使用工具
 */
class Imagemagick {
	/**
	 * return array('w_h','direction','resolution','print_w_h','colorspace')
	 */
	public static $authorize_map = array(
		0=>'無',
		1=>'RF',
		2=>'RM',
	);
	
	public static $mode_map = array(
		0=>'無',
		1=>'有',
	);
	
	public static $direction_map = array(
		0=>'',
		1=>'v',
		2=>'h',
		3=>'s',
	);
	
	public static $direction_desc_map = array(
		0=>'無',
		1=>'垂直',
		2=>'水平',
		3=>'正方',
	);
	
	public static $color_map = array(
		0=>'',
		1=>'bw',
		3=>'rgb',
		4=>'cmyk',
		5=>'srgb',
	);

	public static $color_desc_map = array(
		0=>'無',
		1=>'黑白',
		3=>'RGB',
		4=>'CMYK',
		5=>'SRGB',
	);

	public static $size_desc_map = array(
		'source'=>'原始檔',
		'XL'=>'最大檔',
		'L'=>'高解析度',
		'M'=>'中解析度',
		'S'=>'低解析度',
		'O'=>'浮水印縮圖',
		'P'=>'略縮圖',
	);
	public static $size_bound_settings = array(
		'XL' => array( 'dpi' => '300', '>' => 3500 ),
		'L' => array( 'dpi' => '150', 'bound' => 3500, '>' => 2000, '<=' => 3500 ),
		'M' => array( 'dpi' => '96', 'bound' => 2000, '>' => 1200, '<=' => 2000 ),
		'S' => array( 'dpi' => '72', 'bound' => 1200, '>' => 600, '<=' => 1200 ),
	);

	//計算各個尺寸的長與寬
	public static function getPhotographscale( $width, $height, $size_type ) {
		$pre_compare = 0;
		$output_width = 0;
		$output_height = 0;
		$bound = Imagemagick::$size_bound_settings[$size_type]['bound'];
		if ($width > $height) {
			$output_width = $bound;
			$output_height = intval(($bound / $width) * $height);
		} else if ($width < $height) {
			$output_width = intval(($bound / $height) * $width);
			$output_height = $bound;
		} else {
			$output_width = $bound;
			$output_height = $bound;
		}
		return $output_width . 'x' . $output_height;
	}
	//計算各個尺寸的像素尺寸
	public static function getPhotographMP( $width, $height ){
		$getPhotographMP = $width * $height;
		$result = '';
        if ($getPhotographMP > 1000000) {
            $result = round($getPhotographMP / 1024 / 1024, 2) . 'M';
        } else {
            $result = round($getPhotographMP / 1024, 2) . 'K';
        }
		return $result;
	}

	//取得圖片可切圖的最大尺寸
	public static function getPhotographMaxSize( $width, $height ){
		$max_width_height = max( array( $width, $height ) );
		//找出最大尺寸應在範圍
		$max_size_data = array();
		foreach (Imagemagick::$size_bound_settings as $size => $datas) {
			$in_checked = false;
			if (isset($datas['>']) && isset($datas['<='])) {
				if ($datas['>'] < $max_width_height && $max_width_height <= $datas['<=']) {
					$in_checked = true;
				}
			} else {
				if (isset($datas['>']) && $datas['>'] < $max_width_height) {
					$in_checked = true;
				}
				if (isset($datas['<=']) && $max_width_height <= $datas['<=']) {
					$in_checked = true;
				}
			}
			if ($in_checked && $max_size_data == '') {
				$max_size_data[] = $size;
			}
		}
		if(count($max_size_data) == 0 ) $max_size_data = array_keys(Imagemagick::$size_bound_settings);
		return $max_size_data;
	}

	// 非 jpg 的原始檔做轉檔的 function，轉好的檔案存放於 image_storage/source_to_jpg
	// @in $filename 檔名
	//     $ext 副檔名
	public static function SourcePhotographToJpgConvert( $filename, $ext ){
		$ds          = DIRECTORY_SEPARATOR;
        $storeFolder = PHOTOGRAPH_STORAGE_DIR;
        $sourcePath = $storeFolder . 'source' . $ds . $filename . "." . $ext;
        $jpgPath = $storeFolder . 'source_to_jpg' . $ds . $filename . ".jpg";
        
		if($ext==='gif' || $ext==='psd'){
			$exec = "convert " . $sourcePath. "[0]  -units PixelsPerInch " . $jpgPath;	
			exec($exec);
		}elseif ($ext==='eps'){
			$exec = "convert " . $sourcePath . "  -units PixelsPerInch " . $storeFolder . 'source_to_jpg' . $ds . $filename . ".png";	
	 		exec($exec);
	 		$exec = "convert " . $storeFolder . 'source_to_jpg' . $ds . $filename . ".png  -units PixelsPerInch " . $jpgPath;	
			exec($exec);
			unlink($storeFolder . 'source_to_jpg' . $ds . $filename . ".png");
		}elseif($ext==='ai'){
			$exec = "convert " . $sourcePath . " " . $storeFolder . 'source_to_jpg' . $ds . $filename . ".png";	
	 		exec($exec);
	 		$exec = "convert " . $storeFolder . 'source_to_jpg' . $ds . $filename . ".png  " . $jpgPath;	
			exec($exec);
			unlink($storeFolder . 'source_to_jpg' . $ds . $filename . ".png");
		}elseif($ext ==='tiff' || $ext==='tif'){
			$exec = "convert " . $sourcePath . "[0]  -units PixelsPerInch " . $jpgPath;
			exec($exec);
		}else{
			$exec = "convert " . $sourcePath . "[0]  -units PixelsPerInch " . $jpgPath;
			exec($exec);
		}
		return;
	}

	// 建立五種 ( XL、L、M、S ) 尺寸檔案
	// @in $file_path 檔案路徑 ex. /var/www/html/wenhsun_hr/source/ or /var/www/html/wenhsun_hr/source_to_jpg/
	//	   $single_id 圖檔編號
	//     $size_type 尺寸格式 ex. XL、L、M、S
	public static function PhotographScaleConvert( $file_path, $single_id, $size_type ) {
		$ds          = DIRECTORY_SEPARATOR;
        $storeFolder = PHOTOGRAPH_STORAGE_DIR;
        $filename = $single_id . '.jpg';
		$target_path = $storeFolder . $size_type . $ds;//切圖完後存放路徑
		//$file_path = $storeFolder . 'source_to_jpg' . '/' . $filename;
		$dpi = Imagemagick::$size_bound_settings[$size_type]['dpi'];
		switch ($size_type) {
			case "XL" :
				exec('convert -strip -density ' . $dpi . ' "' . $file_path . '" "' . $target_path . $filename . '"');
				break;
				
			case "L" :
				exec('convert -strip -density ' . $dpi . ' -geometry 2000x2000 "' . $file_path . '" "' . $target_path . $filename . '"');
				break;

			case "M" :
				exec('convert -strip -density ' . $dpi . ' -geometry 1200x1200 "' . $file_path . '" "' . $target_path . $filename . '"');
				break;

			case "S" :
				exec('convert -strip -density ' . $dpi . ' -geometry 600x600 "' . $file_path . '" "' . $target_path . $filename . '"');
				break;
			default :
				break;
		}

	}

	public static function get_graph_data($file_path) {	
		$graph_results;
		$file_name = basename($file_path);
		$ex_file_name = explode('.', $file_name);
		$results = array('w_h' => '0x0', 'direction' => '', 'resolution' => 0, 'colorspace' => '', 'print_w_h' => '0.0x0.0');
		$accept_array = array('jpg', 'jpeg', 'tiff', 'tif','BMP', 'png');
		if (count($ex_file_name) >= 2 && in_array($ex_file_name[count($ex_file_name) - 1], $accept_array)) {
			exec('identify -verbose "' . $file_path . '"', $graph_results,$outdata);
			$outputs = array('geometry', 'resolution', 'printsize', 'colorspace');
			$type = '';
			foreach ($graph_results as $graph_result) {
				$temp_result = strtolower(str_replace(' ', '', $graph_result));
				$key_value = explode(':', $temp_result);
				if (count($key_value) > 1) {
					$data_key = $key_value[0];
					$data_value = $key_value[1];
					if (in_array($data_key, $outputs)) {
						if ($data_key == 'geometry') {
							$get_geo_data = Imagemagick::get_geometry_datas(str_replace('+0', '', $data_value));
							$results['w_h'] = $get_geo_data['w_h'];
							$results['direction'] = $get_geo_data['direction'];
						} else if ($data_key == 'resolution') {
							$explode_dpi = explode('x', $data_value);
							$results[$data_key] = $explode_dpi[0];
						} else if ($data_key == 'printsize') {
							$results['print_w_h'] = Imagemagick::get_print_datas($data_value,$results['resolution']);
						} else {
							$results[$data_key] = $data_value;
						}
					}
					if ($data_key == 'type') {
						$type = $data_value;
					}
				}
			}
			if ($type == 'grayscale') {
				$results['colorspace'] = 'bw';
			}
		}
		return $results;
	}

	/**
	 * return array('p_path'=>target_p_path,'o_path'=>target_o_path,'file'=>file_rename)
	 */

	public static function build_o_p( $source_folder, $single_id ) {
		$ds          = DIRECTORY_SEPARATOR;
        $storeFolder = PHOTOGRAPH_STORAGE_DIR;
        $filename = $single_id . '.jpg';
		//p
		$target_p_path = $storeFolder . 'P' . $ds;
		exec('convert -strip -density 72 -geometry 150x150 "' . $source_folder . $filename . '" "' . $target_p_path . $filename . '" &');
		//o
		$target_o_path = $storeFolder . 'O' . $ds;
		exec('convert -strip -density 72 -geometry 500x500 "' . $source_folder . $filename . '" "' . $target_o_path . $filename . '" &');
		exec('composite -dissolve 25 -tile "' . WATERMARK . '" "' . $target_o_path .$filename . '" "' . $target_o_path . $filename . '" &');
		return array('p_path' => $target_p_path, 'o_path' => $target_o_path);
	}

	/**
	 * return array('w_h'=>寬x高,'direction'=>方向)
	 */
	private static function get_geometry_datas($w_h) {
		$ex_w_h = explode('x', $w_h);
		$direction = '';
		if (count($ex_w_h) > 1) {
			$w = $ex_w_h[0];
			$h = $ex_w_h[1];
			if ($w > $h) {
				$direction = 2;
			}
			if ($w < $h) {
				$direction = 1;
			}
			if ($w == $h) {
				$direction = 3;
			}
		}
		return array('w_h' => $w . 'x' . $h, 'direction' => $direction);
	}

	/**
	 * return array('print_w_h'=>輸出寬x輸出高)，計算後結果顯示到小數點後1位
	 */
	public static function get_print_datas($print_size, $dpi) {
		$explode_print_size = explode('x', $print_size);
		$print_w = 0.0;
		$print_h = 0.0;
		if (count($explode_print_size) > 1) {
			$print_w = number_format($explode_print_size[0] * 2.54, 2);
			$print_h = number_format($explode_print_size[1] * 2.54, 2);
		}
		return $print_w . 'x' . $print_h;
	}

	/**
	 * return mp
	 */
	public static function get_pixel_sizes($w_h, $color = 'rgb') {
		$ex_w_h = explode('x', $w_h);
		$w = (int)$ex_w_h[0];
		$h = (int)$ex_w_h[1];
		$count_times = 0;
		$flip_colors = array_flip(Imagemagick::$color_map);
		$count_times = $flip_colors[$color];
		$counting = $w * $h * $count_times;
		$result = '';
		if ($counting > 1000000) {
			$result = round($counting / 1024 / 1024, 2) . 'M';
		} else {
			$result = round($counting / 1024, 2) . 'K';
		}
		return $result;
	}

	/**
	 * return array('size'=>array('mp','dpi','w_h'))
	 */
	public static function get_size_datas($w_h, $ori_dpi, $color) {
		$ex_w_h = explode('x', $w_h);
		$width = $ex_w_h[0];
		$height = $ex_w_h[1];
		$get_bounds = array();
		$pre_compare = 0;
		if ($width >= $height) {
			$pre_compare = $width;
		} else {
			$pre_compare = $height;
		}
		//找出最大尺寸應在範圍
		$max_size_data = array();
		foreach (Imagemagick::$size_bound_settings as $size => $datas) {
			$in_checked = false;
			if (isset($datas['>']) && isset($datas['<='])) {
				if ($datas['>'] < $pre_compare && $pre_compare <= $datas['<=']) {
					$in_checked = true;
				}
			} else {
				if (isset($datas['>']) && $datas['>'] < $pre_compare) {
					$in_checked = true;
				}
				if (isset($datas['<=']) && $pre_compare <= $datas['<=']) {
					$in_checked = true;
				}
			}
			if ($in_checked && count($max_size_data) == 0) {
				$max_size_data = $datas;
			}
		}
		//如果max_size_data沒有bound，表示檔案是最大格式
		if (count($max_size_data) != 0) {
			if (!isset($max_size_data['bound'])) {
				foreach (Imagemagick::$size_bound_settings as $size => $datas) {
					//將建立資料中沒有bound，表示為最大JPG檔，w_h，dpi都記錄為原始資料
					if (!isset($datas['bound'])) {
						$get_bounds[$size]['w_h'] = $w_h;
						$get_bounds[$size]['mp'] = Imagemagick::get_pixel_sizes($w_h, $color);
						$get_bounds[$size]['dpi'] = $datas['dpi'];
					} else {
						$adj_w_h = Imagemagick::get_adjust_geometry($w_h, $datas['bound']);
						$get_bounds[$size]['w_h'] = $adj_w_h;
						$get_bounds[$size]['mp'] = Imagemagick::get_pixel_sizes($adj_w_h, $color);
						$get_bounds[$size]['dpi'] = $datas['dpi'];
					}
				}
				//如果max_size_data有bound，進行條件判斷
			} else {
				foreach (Imagemagick::$size_bound_settings as $size => $datas) {
					if (isset($datas['bound'])) {
						if ($datas['bound'] < $max_size_data['bound']) {
							$adj_w_h = Imagemagick::get_adjust_geometry($w_h, $datas['bound']);
							$get_bounds[$size]['w_h'] = $adj_w_h;
							$get_bounds[$size]['mp'] = Imagemagick::get_pixel_sizes($adj_w_h, $color);
							$get_bounds[$size]['dpi'] = $datas['dpi'];
						} else if ($datas['bound'] == $max_size_data['bound']) {
							$get_bounds[$size]['w_h'] = $w_h;
							$get_bounds[$size]['mp'] = Imagemagick::get_pixel_sizes($w_h, $color);
							$get_bounds[$size]['dpi'] = $datas['dpi'];
						}
					}
				}
			}
		}
		return $get_bounds;
	}

	/**
	 * return array('w_h')
	 */
	private static function get_adjust_geometry($w_h, $bound) {
		$ex_w_h = explode('x', $w_h);
		$width = $ex_w_h[0];
		$height = $ex_w_h[1];
		$pre_compare = 0;
		$output_width = 0;
		$output_height = 0;
		if ($width > $height) {
			$output_width = $bound;
			$output_height = intval(($bound / $width) * $height);
		} else if ($width < $height) {
			$output_width = intval(($bound / $height) * $width);
			$output_height = $bound;
		} else {
			$output_width = $bound;
			$output_height = $bound;
		}
		return $output_width . 'x' . $output_height;
	}

	/**
	 * return array('path'=>target_path,'file'=>file_rename)
	 */
	private static function build_graph_sizes($file_path, $file_name, $size, $file_rename) {
		$geometry = $bound . 'x' . $bound;
		$target_path = STORAGE_DIR . '/' . $size;
		exec('convert -geometry ' . $geometry . ' "' . $file_path . '/' . $file_name . '" "' . $target_path . '/' . $file_rename . '"');
		return array('path' => $target_path, 'file' => $file_rename);
	}

	/**
	 * 作者:neil_kuo
	 * 版權:ImageDJ Corporation by neil_kuo
	 * 修改日期:2014-07-31
	 * 程式功能:建立五種(h、m、l、w、b)尺寸檔案
	 */
	public static function buildFiveKindsSizes($file_name, $file_rename, $graph_type, $dpi) {
		$ds          = DIRECTORY_SEPARATOR;
        $storeFolder = PHOTOGRAPH_STORAGE_DIR;
		$target_path = $storeFolder . $graph_type;//切圖完後存放路徑
		$file_path = $storeFolder . 'source_to_jpg' . '/' . $file_name ;
		switch ($graph_type) {
			case "XL" :
				exec('convert -strip -density ' . $dpi . ' "' . $file_path . '" "' . $target_path . '/' . $file_rename . '"');
				break;
				
			case "L" :
				exec('convert -strip -density ' . $dpi . ' -geometry 2000x2000 "' . $file_path . '" "' . $target_path . '/' . $file_rename . '"');
				break;

			case "M" :
				exec('convert -strip -density ' . $dpi . ' -geometry 1200x1200 "' . $file_path . '" "' . $target_path . '/' . $file_rename . '"');
				break;

			case "S" :
				exec('convert -strip -density ' . $dpi . ' -geometry 600x600 "' . $file_path . '" "' . $target_path . '/' . $file_rename . '"');
				break;
			default :
				break;
		}

	}
	/**
	 * 作者:neil_kuo
	 * 版權:ImageDJ Corporation by neil_kuo
	 * 建立日期:2014-07-31
	 * 修改日期:2014-08-01
	 * 程式功能:找最小切割檔案如果沒有則使用S來源進行切割
	 */
	private function orderCatFile($small_cat_file_path,$file_path) {
		if(file_exists($small_cat_file_path)==true){//預設最小尺寸如果有圖片則採用此路徑盡些圖檔切割
			 $file_path = $small_cat_file_path;   
		}else{
			 $file_path = $file_path; //
		}
		return $file_path;
	}
}
?>
