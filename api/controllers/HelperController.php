<?php

namespace app\controllers;

class HelperController extends \yii\rest\Controller
{
    public function actionIndex()

    {
        return $this->render('index');
    }


    public function actionResize($filename, $width=0, $height=0,$type="1") {
        if(strpos($filename,'http')!==false){
            return $filename;
        }
        if($type!='4'){
            if($filename){
                $extend = pathinfo($filename);
                $extend = isset($extend["extension"]) ? strtolower($extend["extension"]):"";
                return HTTP_IMAGE."thumbs/".$filename."_".$width."x".$height."_".$type.".".$extend;
            }else{
                return HTTP_IMAGE."image/no_image.jpg";
            }
        }else{
            return HTTP_IMAGE.$filename;
        }

    }
    /**/
}
