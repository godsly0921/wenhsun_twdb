<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/12
 * Time: 下午 08:29
 */
class ImageResize
{
    public static function resize($from_filename, $save_filename, $in_width = 320, $in_height = 320, $quality = 95)
    {
            $allow_format = ['jpeg', 'png', 'gif', 'pdf'];
            $sub_name = $t = '';
            // Get new dimensions
            $img_info = getimagesize($from_filename);
            $width = $img_info['0'];
            $height = $img_info['1'];
            $imgtype = $img_info['2'];
            $imgtag = $img_info['3'];
            //$bits = $img_info['bits'];
            //$channels = $img_info['channels'];
            $mime = $img_info['mime'];

            list($t, $sub_name) = explode('/', $mime);

            if ($sub_name == 'jpg') {
                $sub_name = 'jpeg';
            }

            if (!in_array($sub_name, $allow_format)) {
                return false;
            }


            $percent =ImageResize::getResizePercent($width, $height, $in_width, $in_height);

            $new_width = $width * $percent;
            $new_height = $height * $percent;

            $image_new = imagecreatetruecolor($new_width, $new_height);

            if($sub_name=="png"){

                $image = imagecreatefrompng($from_filename);
                imagealphablending($image_new, FALSE);
                imagesavealpha($image_new, TRUE);
                imagecopyresampled($image_new, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                return @imagepng($image_new,$save_filename);


            }elseif($sub_name=="gif"){
                $image = imagecreatefromgif($from_filename);
                imagecopyresampled($image_new, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                return @imagegif($image_new, $save_filename, $quality);

            }else{
                $image = imagecreatefromjpeg($from_filename);
                imagecopyresampled($image_new, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                return @imagejpeg($image_new, $save_filename, $quality);
            }


    }

    /**
     * 抓取要縮圖的比例
     * $source_w : 來源圖片寬度
     * $source_h : 來源圖片高度
     * $inside_w : 縮圖預定寬度
     * $inside_h : 縮圖預定高度
     * Test:
     *   $v = (getResizePercent(1024, 768, 400, 300));
     *   echo 1024 * $v . "\n";
     *   echo  768 * $v . "\n";
     */
    public static function getResizePercent($source_w, $source_h, $inside_w, $inside_h) {
        if ($source_w < $inside_w && $source_h < $inside_h) {
            return 1;
            // Percent = 1, 如果都比預計縮圖的小就不用縮
        }
        $w_percent = $inside_w / $source_w;
        $h_percent = $inside_h / $source_h;
        return ($w_percent > $h_percent) ? $h_percent : $w_percent;
    }

}