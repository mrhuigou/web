<?php
/**
 * Created by PhpStorm.
 * User: Arvin
 * Date: 2015/2/3
 * Time: 15:31
 */
namespace common\component\image;
use Yii;
use common\component\Upload\FastDFS\Tracker;
use common\component\Upload\FastDFS\Storage;
use yii\base\ErrorException;

define('HTTP_IMAGE',\Yii::$app->params['HTTP_IMAGE']);
class Image{
    /**
     * @param string $type 9=原大小图
     * @return string
     */
    //const  HTTP_IMAGE ="http://img1.365jiarun.com/";
    const  HTTP_IMAGE =HTTP_IMAGE;
    static function resize($filename, $width=0, $height=0, $type = 1)
    {
        $filename=trim($filename);
        if($filename){
            if(strpos($filename,'http')!==false){
                return $filename;
            }
            if($width==0 && $height==0){
                return self::HTTP_IMAGE.$filename;
            }

            if((int)$type == 9){
                return self::HTTP_IMAGE.$filename;
            }else{
                $extend = strtolower(trim(substr(strrchr($filename, '.'), 1)));
                if($extend){
                    return self::HTTP_IMAGE.$filename."_".$width."x".$height.".".$extend;
                }else{
                    return self::HTTP_IMAGE."none.gif";
                }
            }
        }else{
            return self::HTTP_IMAGE."none.gif";
        }
    }

    static function getImageInfo($img) {
        $imageInfo = getimagesize($img);
        if ($imageInfo !== false) {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
            //$imageSize = filesize($img);
            $info = array(
                "width" => $imageInfo[0],
                "height" => $imageInfo[1],
                "type" => $imageType,
                //"size" => $imageSize,
                "mime" => $imageInfo['mime']
            );
            return $info;
        } else {
            return false;
        }
    }

    //
    static function validateimage($uploadfile ,$maxsize=1048576,$maxwidth=650,$maxheight=650){
       // $filename = basename(html_entity_decode($uploadfile['name'], ENT_QUOTES, 'UTF-8'));

        if(isset($uploadfile['size'])){
            $img_size_k = $uploadfile['size'];
            if($img_size_k > $maxsize){
                $json['error'] = '文件太大，上传失败';
            }else{
                $img_in = self::getImageInfo($uploadfile['tmp_name']);
                if($img_in){
                    if($img_in['width'] > $maxwidth){
                        $json['error'] = '，文件宽度不能超过'.$maxwidth.'像素';
                    }
                    if($img_in['height'] > $maxheight){
                        $json['error'] = '，文件高度不能超过'.$maxheight.'像素';
                    }
                }
            }
        }else{
            $json['error'] = "上传文件出错";
        }

        if (isset($uploadfile) && $uploadfile['tmp_name']) {
            $filename = basename(html_entity_decode($uploadfile['name'], ENT_QUOTES, 'UTF-8'));

            $allowed = array(
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/x-png',
                'application/octet-stream',
                //'image/gif',
                //'application/x-shockwave-flash'

            );
            if (!in_array($uploadfile['type'], $allowed)) {
                $json['error'] ="只能上传jpg,png格式";
            }
            $allowed = array(
                '.jpg',
                '.jpeg',
                //'.gif',
                '.png',
                //'.flv'
            );
            if (!in_array(strtolower(strrchr($filename, '.')), $allowed)) {
                $json['error'] ="只能上传jpg,png格式";
            }

            if(!isset($json['error'])){
                $json = "success";
            }
        }
        return $json;
    }
    static function uploadimage($upload){
        $file_tmp = $upload['tmp_name'];
        $real_name = strtolower($upload['name']);
        $file_name = dirname($file_tmp)."/".$real_name;
        @rename($file_tmp, $file_name);
        $tracker_addr = Yii::$app->params['FDFS']['tracker_addr'];
        $tracker_port = Yii::$app->params['FDFS']['tracker_port'];
        $group_name=Yii::$app->params['FDFS']['group_name'];
        $tracker      = new Tracker($tracker_addr, $tracker_port);
        $storage_info = $tracker->applyStorage($group_name);
        $storage = new Storage($storage_info['storage_addr'], $storage_info['storage_port']);
        $data = $storage->uploadFile($storage_info['storage_index'], $file_name);
        return $data;
    }
    static function base64EncodeImage($image_file) {
	    try{
		    $image_info = getimagesize($image_file);
		    $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
		    $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
	    }catch (ErrorException $e){
		    $base64_image=$e->getMessage();
	    }
	    return $base64_image;
    }
	static function upload($file_name){
		$tracker_addr = Yii::$app->params['FDFS']['tracker_addr'];
		$tracker_port = Yii::$app->params['FDFS']['tracker_port'];
		$group_name=Yii::$app->params['FDFS']['group_name'];
		$tracker      = new Tracker($tracker_addr, $tracker_port);
		$storage_info = $tracker->applyStorage($group_name);
		$storage = new Storage($storage_info['storage_addr'], $storage_info['storage_port']);
		$data = $storage->uploadFile($storage_info['storage_index'], $file_name);
		return $data;
	}
}