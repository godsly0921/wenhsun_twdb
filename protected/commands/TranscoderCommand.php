<?php
class TranscoderCommand extends CConsoleCommand{
    public function init(){
        parent::init();
    }

    public function actionIndex(){
        set_time_limit(0);
        date_default_timezone_set("Asia/Taipei");
        while(1) {
            $storeFolder='/var/www/html/wenhsun_hr/image_storage/';
            $photographService = new photographService();
            $sql = "SELECT iq.*,s.ext as source_ext,s.dpi,s.color,s.direction FROM `image_queue` iq LEFT JOIN single s on iq.single_id = s.single_id where iq.queue_status = 0 limit 1";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $single_id = '';
            $ds          = DIRECTORY_SEPARATOR;
            $size_bound_settings = Imagemagick::$size_bound_settings;

            foreach ($result as $key => $value) {
                $update_single_size = array();       
                $source_targetFile = '';

                if($value['source_ext'] != 'jpg'){
                    $source_targetFile = $storeFolder . 'source_to_jpg' . $ds . $value['single_id'] . ".jpg";
                }else{
                    $source_targetFile = $storeFolder . 'source' . $ds . $value['single_id'] . "." . $value['source_ext'];
                }     
                if(($value['size_type'] == 'source' && $value['source_ext'] != 'jpg') || ($value['source_ext'] == 'jpg' && ($value['dpi'] == '' || $value['color'] == '' || $value['direction'] == ''))){ 
                    if(file_exists($source_targetFile)){
                        $single_size = $photographService->getPhotographData($source_targetFile);
                        $single = array();
                        $single['dpi'] = $single_size['resolution'];
                        $single['color'] = $single_size['colorspace'];
                        $single['direction'] = $single_size['direction'];
                        $single = $photographService->updateSingle( $value['single_id'], $single );
                        $file_size = filesize($source_targetFile);
                        $update_single_size['dpi'] = $single_size['resolution'];
                        $update_single_size['mp'] = $single_size['mp'];
                        $update_single_size['w_h'] = $single_size['w_h'];
                        $update_single_size['print_w_h'] = $single_size['print_w_h'];
                        $update_single_size['file_size'] = $file_size;
                        $update_single_size['ext'] = $value['size_type'] == 'source' ? $value['source_ext']:'jpg';
                        $photographService->updateSingleSize( $value['single_id'], $update_single_size, $value['size_type'] );
                        $photographService->updateImageQueue($value['single_id'],$value['size_type']);
                    }  
                }else{
                    $targetPath = $storeFolder . $value['size_type'] . $ds;
                    $targetFile = $targetPath . $value['single_id'] . ".jpg";
                    if(file_exists($source_targetFile)){
                        if($value['size_type'] != 'source'){
                            Imagemagick::PhotographScaleConvert( $source_targetFile, $value['single_id'], $value['size_type'] );
                        }
                        list($width, $height) = getimagesize($targetFile);
                        $getPhotographScale = $width . 'x' . $height;
                        $getPhotographMP = Imagemagick::getPhotographMP( $width, $height );
                        $dpi = $size_bound_settings[$value['size_type']]['dpi'];
                        $print_w_h = Imagemagick::get_print_datas( $width/$dpi . 'x' . $height/$dpi, $value['dpi'] );
                        
                        $file_size = filesize($targetFile);
                        $update_single_size['dpi'] = $dpi;
                        $update_single_size['mp'] = $getPhotographMP;
                        $update_single_size['w_h'] = $getPhotographScale;
                        $update_single_size['print_w_h'] = $print_w_h;
                        $update_single_size['file_size'] = $file_size;
                        $update_single_size['ext'] = 'jpg';
                        $photographService->updateSingleSize( $value['single_id'], $update_single_size, $value['size_type'] );
                        $photographService->updateImageQueue( $value['single_id'],$value['size_type'] );
                    }
                }                        
            }
        }
    }
}

?>