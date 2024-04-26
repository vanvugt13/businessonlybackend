<?php
namespace frontend\components;

use Yii;

class Image 
{
    const DEFAULT_HEIGHT = 700;
    public static function resizeImage(string $imagedata) : string{
        $max=   self::DEFAULT_HEIGHT;
/*
here you can insert any specific "if-else",
or "switch" type of detector of what type of picture this is.
in this example i'll use standard JPG
*/

        $src_img=imagecreatefromstring($imagedata);

        $oh = imagesy($src_img);  # original height
        $ow = imagesx($src_img);  # original width

        $new_h = $oh;
        $new_w = $ow;

        if($oh > $max || $ow > $max){
            $r = $oh/$ow;
            $new_h = ($oh > $ow) ? $max : $max*$r;
            $new_w = $new_h/$r;
        }
// note TrueColor does 256 and not.. 8
        $dst_img = ImageCreateTrueColor($new_w,$new_h);
        ImageCopyResized($dst_img, $src_img, 0,0,0,0, $new_w, $new_h, ImageSX($src_img), ImageSY($src_img));
        $tmp_file =Yii::getAlias('@runtime').'/tmpfile_'.time().rand(1,1000000);
        imagepng($dst_img,$tmp_file);
        $content = file_get_contents($tmp_file);
        unlink($tmp_file);
        return $content;



     //   return $imagedata_resized;
    }
}