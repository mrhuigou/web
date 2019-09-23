<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/21
 * Time: 17:55
 */
namespace h5\widgets\Wx;
use common\component\image\Image;
use common\component\Upload\FastDFS\Storage;
use common\component\Upload\FastDFS\Tracker;
use common\component\Wx\WxSdk;
use yii\base\Action;
use Yii;
class ImageAction extends Action
{   public $media_id=0;
    public function init()
    {
        $this->media_id=Yii::$app->request->get('media_id');
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $appid=Yii::$app->params['weixin']['appid'];
        $appsecret=Yii::$app->params['weixin']['appsecret'];
        $wechat=new WxSdk($appid,$appsecret);
        $result="";
        if($file_name=$wechat->DownloadFile($this->media_id)){
            $tracker_addr = Yii::$app->params['FDFS']['tracker_addr'];
            $tracker_port = Yii::$app->params['FDFS']['tracker_port'];
            $group_name=Yii::$app->params['FDFS']['group_name'];
            $tracker      = new Tracker($tracker_addr, $tracker_port);
            $storage_info = $tracker->applyStorage($group_name);
            $storage = new Storage($storage_info['storage_addr'], $storage_info['storage_port']);
            if ($data = $storage->uploadFile($storage_info['storage_index'], $file_name)) {
                $result = Image::resize($data['group']."/".$data['path'],40,40,9);
               // unlink($file_name);
            }
        }
        return $result;
    }
}
