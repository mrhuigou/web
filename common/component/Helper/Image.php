<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/8/25
 * Time: 10:05
 */
namespace common\component\Helper;
use Yii;
use common\component\Upload\FastDFS\Storage;
use common\component\Upload\FastDFS\Tracker;
class Image {
	public static function UploadFormUrl($url, $saveName=null)
	{
		if(!$saveName && preg_match('/\/([^\/]+\.[a-z]{3,4})$/i',$url,$matches)){
			$saveName = strToLower($matches[1]);
		}
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		// 运行cURL，请求网页
		$file = curl_exec($ch);
		// 关闭URL请求
		curl_close($ch);
		// 将文件写入获得的数据
		$filename = "/tmp/" . $saveName;
		$fp = fopen($filename, 'w');
		fwrite($fp, $file);
		fclose($fp);
		$tracker_addr = Yii::$app->params['FDFS']['tracker_addr'];
		$tracker_port = Yii::$app->params['FDFS']['tracker_port'];
		$group_name=Yii::$app->params['FDFS']['group_name'];
		$tracker      = new Tracker($tracker_addr, $tracker_port);
		$storage_info = $tracker->applyStorage($group_name);
		$storage = new Storage($storage_info['storage_addr'], $storage_info['storage_port']);
		if ($data = $storage->uploadFile($storage_info['storage_index'], $filename)) {
			$result =$data['group']."/".$data['path'];
		} else {
			$result = '';
		}
		@unlink($filename);
		return $result;
	}
}