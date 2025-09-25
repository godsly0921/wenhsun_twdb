<?php
class TranscoderCommand extends CConsoleCommand{
    public function init(){
        parent::init();
    }

    public function actionIndex()
    {
        set_time_limit(0);
        date_default_timezone_set("Asia/Taipei");

        // $storeFolder='/var/www/html/wenhsun_hr/image_storage/';
        $storeFolder = dirname(Yii::app()->basePath) ."/image_storage/";
        $photographService = new PhotographService();
        $size_bound_settings = Imagemagick::$size_bound_settings;

        while(1) {
            $sql = "SELECT iq.*,s.ext as source_ext,s.dpi,s.color,s.direction FROM `image_queue` iq LEFT JOIN single s on iq.single_id = s.single_id where iq.queue_status = 0 limit 1";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($result as $key => $value) {
                // 檢查原始圖片是否存在
                $sourceImage = $storeFolder .($value['source_ext'] === 'jpg' ? 'source' : 'source_to_jpg') ."/{$value['single_id']}.jpg";
                if (!file_exists($sourceImage)) {
                    continue;
                }

                $update_single_size = array();
                if(
                    ($value['size_type'] == 'source' && $value['source_ext'] != 'jpg')
                    || ($value['source_ext'] == 'jpg' && ($value['dpi'] == '' || $value['color'] == '' || $value['direction'] == ''))
                ) {
                    $single_size = $photographService->getPhotographData($sourceImage);
                    $single = array();
                    $single['dpi'] = $single_size['resolution'];
                    $single['color'] = $single_size['colorspace'];
                    $single['direction'] = $single_size['direction'];
                    $single = $photographService->updateSingle( $value['single_id'], $single );
                    $file_size = filesize($sourceImage);
                    $update_single_size['dpi'] = $single_size['resolution'];
                    $update_single_size['mp'] = $single_size['mp'];
                    $update_single_size['w_h'] = $single_size['w_h'];
                    $update_single_size['print_w_h'] = $single_size['print_w_h'];
                    $update_single_size['file_size'] = $file_size;
                    $update_single_size['ext'] = $value['size_type'] == 'source' ? $value['source_ext']:'jpg';
                    $photographService->updateSingleSize( $value['single_id'], $update_single_size, $value['size_type'] );
                    $photographService->updateImageQueue($value['single_id'],$value['size_type']);
                } else {
                    $targetFile = "{$storeFolder}{$value['size_type']}/{$value['single_id']}.jpg";
                    Imagemagick::PhotographScaleConvert( $sourceImage, $value['single_id'], $value['size_type'] );
                    if (file_exists($targetFile)) {
                        list($width, $height) = getimagesize($targetFile);
                        $getPhotographScale = $width . 'x' . $height;
                        $getPhotographMP = Imagemagick::getPhotographMP($width, $height);
                        $dpi = $size_bound_settings[$value['size_type']]['dpi'];
                        $print_w_h = Imagemagick::get_print_datas($width / $dpi . 'x' . $height / $dpi, $value['dpi']);

                        $file_size = filesize($targetFile);
                        $update_single_size['dpi'] = $dpi;
                        $update_single_size['mp'] = $getPhotographMP;
                        $update_single_size['w_h'] = $getPhotographScale;
                        $update_single_size['print_w_h'] = $print_w_h;
                        $update_single_size['file_size'] = $file_size;
                        $update_single_size['ext'] = 'jpg';
                        $photographService->updateSingleSize($value['single_id'], $update_single_size, $value['size_type']);
                        $photographService->updateImageQueue($value['single_id'], $value['size_type']);
                    }
                }
            }
        }
    }
}

?>