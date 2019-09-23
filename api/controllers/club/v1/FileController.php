<?php

namespace api\controllers\club\v1;

use common\component\image\Image;

class FileController extends \yii\rest\Controller
{
    //上传图片示例
    public function actionIndex()
    {
        if(isset($_FILES['image'])){
            $uploadfile = $_FILES['image'];

            $filename = basename(html_entity_decode($uploadfile['name'], ENT_QUOTES, 'UTF-8'));

            $type = strrchr($filename, '.');
            $rand = rand(1000, 9999);
            $picname = date("YmdHis") . $rand . $type; //重命名图片
            $upload=array('name'=>$picname,"tmp_name"=>$uploadfile['tmp_name']);

            $json = Image::validateimage($uploadfile,1024*1024*2,650,650);//验证图片
            if($json == "success"){
                $data =  Image::uploadimage($upload);
                $retun_url = array();
                $img = "";
                if(!empty($data)){
                    $img = $data['group']."/".$data["path"];
                }

                $retun_url["url"] = $img;
                return $retun_url;
            }else{
                return $json;
            }
        }

    }

}
