<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/1
 * Time: 16:58
 */
namespace api\controllers\club\v2;
use api\models\V1\ClubUserFile;
use api\models\V1\Image;
use \yii\rest\Controller;
use common\component\response\Result;
use api\controllers\club\v2\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\component\Upload\FastDFS\Storage;
use common\component\Upload\FastDFS\Tracker;
use yii\base\DynamicModel;
use yii\web\UploadedFile;
use Yii;
class FileController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => AccessControl::className()
            ],
        ]);
    }
    public function actionUpload(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        $file = UploadedFile::getInstanceByName("file");
        $model = new DynamicModel(compact('file'));
        $file_name = dirname($model->file->tempName)."/".$model->file->name;
        @rename($model->file->tempName, $file_name);
        $tracker_addr = Yii::$app->params['FDFS']['tracker_addr'];
        $tracker_port = Yii::$app->params['FDFS']['tracker_port'];
        $group_name=Yii::$app->params['FDFS']['group_name'];
        $tracker      = new Tracker($tracker_addr, $tracker_port);
        $storage_info = $tracker->applyStorage($group_name);
        $storage = new Storage($storage_info['storage_addr'], $storage_info['storage_port']);
        if ($data = $storage->uploadFile($storage_info['storage_index'], $file_name)) {
            $path = $data['group']."/".$data['path'];
        }else{
            Result::Error('上传失败');
        }
        $ClubUserFile=new ClubUserFile();
        $ClubUserFile->customer_id=Yii::$app->user->getId();
        $ClubUserFile->filename=$model->file->name;
        $ClubUserFile->filetype=$model->file->type;
        $ClubUserFile->filesize=$model->file->size;
        $ClubUserFile->path=$path;
        $ClubUserFile->creat_at=date('Y-m-d H:i:s',time());
        $ClubUserFile->save();
        return Result::OK(['path'=>\common\component\image\Image::resize($path,100,100,9)]);
    }
}