<?php
namespace frontend\components;

use common\models\User;
use frontend\models\Company;
use frontend\models\Post;
use ParagonIE\Sodium\Compat;
use Yii;
use yii\helpers\Url;

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

    private static function generateUniqueId(User|Company|Post $model){
        if($model instanceof Company and !empty($model->companyImage->logo) ){
                $model->unique_id = uniqid('CP');
        }
        if($model instanceof User AND !empty($model->userImage->image)){
                $model->unique_id = uniqid('US');
        }
        if($model instanceof Post AND !empty($model->postImage->image) ){
                $model->unique_id = uniqid('PS');
        } 
    }

    private static function getAttribute(User|Company|Post $model){
       if($model instanceof User )
        return $model->userImage->image;
        if( $model instanceof Post)
        return $model->postImage->image;
        if($model instanceof Company )
        return $model->companyImage->logo;
    }

    private static function generateFile(User|Company|Post $model,string $absoluteimagepath){
        $blob = self::getAttribute($model);
        file_put_contents($absoluteimagepath,base64_decode((string)$blob));
    }

    private static function getFilename(User|Company|Post $model){
        if(empty($model->unique_id)){
            return '/assets/images/profiel.png'; //null;
        }
        $filename = $model->unique_id.'.png';
        $absoluteimagepath = Yii::getAlias('@webroot').'/images/'.$filename;
        if(!file_exists($absoluteimagepath)){
            self::generateFile($model,$absoluteimagepath);
        }
        return Url::base(true).'/images/'.$filename;
    }
    public static function getImageUrl(User|Company|Post $model){
        if(empty($model->unique_id)){
           self::generateUniqueId($model);
            $model->save(true,['unique_id']);

        }
        return self::getFilename($model);
    }
}