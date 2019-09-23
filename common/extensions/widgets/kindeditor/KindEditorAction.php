<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KindEditorAction
 *
 * @author Qinn Pan <Pan JingKui, pjkui@qq.com>
 * @link http://www.pjkui.com
 * @QQ 714428042
 * @date 2015-3-4

 */

namespace common\extensions\widgets\kindeditor;

use api\models\V1\Image;
use api\models\V1\ImageCategory;
use common\component\Upload\FastDFS\Storage;
use common\component\Upload\FastDFS\Tracker;
use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use common\extensions\widgets\kindeditor\Services_JSON;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class KindEditorAction extends Action {

    /**
     *
     * @var Array 保存配置的数组
     */
    public $config;
    public $php_path;
    public $php_url;
    public $root_path;
    public $root_url;
    public $save_path;
    public $save_url;
    public $max_size;

    //public $save_path;
    public function init() {
        //close csrf
        Yii::$app->request->enableCsrfValidation = false;
        //默认设置
//根目录路径，可以指定绝对路径，比如 /var/www/attached/
        $this->root_path ='';
//根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
        $this->root_url = "/";
//图片扩展名
//            $ext_arr = ['gif', 'jpg', 'jpeg', 'png', 'bmp'],
//定义允许上传的文件扩展名
//            $ext_arr = array(
//                'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
//                'flash' => array('swf', 'flv'),
//                'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
//                'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
//            ),
//最大文件大小
        $this->max_size = 1000000;


        //load config file

        parent::init();
    }

    public function run() {
        $this->handAction();
    }

    /**
     * 处理动作
     */
    public function handAction() {
        //获得action 动作
        $action = Yii::$app->request->get('action');
        switch ($action) {
            case 'fileManagerJson':

                $this->fileManagerJsonAction();
                break;
            case 'uploadJson':

                $this->UploadJosnAction();
                break;

            default:
                break;
        }
    }

      //排序
      public function cmp_func($a, $b) {
        global $order;
        if ($a['is_dir'] && !$b['is_dir']) {
            return -1;
        } else if (!$a['is_dir'] && $b['is_dir']) {
            return 1;
        } else {
            if ($order == 'size') {
                if ($a['filesize'] > $b['filesize']) {
                    return 1;
                } else if ($a['filesize'] < $b['filesize']) {
                    return -1;
                } else {
                    return 0;
                }
            } else if ($order == 'type') {
                return strcmp($a['filetype'], $b['filetype']);
            } else {
                return strcmp($a['filename'], $b['filename']);
            }
        }
    }

    /**
     * 文件管理操作
     * @author ${author}
     */
    public function fileManagerJsonAction() {
//图片扩展名
        $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
//目录名
        $dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
        if (!in_array($dir_name, array('', 'image', 'flash', 'media', 'file'))) {
            echo "Invalid Directory name.";
            exit;
        }
        if ($dir_name !== '') {
            $root_url=$this->root_url .$dir_name . "/";
        }

//根据path参数，设置各路径和URL
        if (empty($_GET['path'])) {
            $current_path = "default/";
            $current_dir_path = '';
            $moveup_dir_path = '';
        } else {
            $current_path =$_GET['path'];
            $current_dir_path = $_GET['path'];
            $moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }
        $path=explode("/",$current_path);
        array_pop($path);
        $model=ImageCategory::findOne(['name'=>end($path)?end($path):'default']);

//排序形式，name or size or type
        $order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

//遍历目录取得文件信息
        $file_list = array();
        if ($handle = $model) {
            $i = 0;
            if($handle->image){
                foreach($handle->image as $image){
                    $file_list[$i]['is_dir'] = false;
                    $file_list[$i]['has_file'] = false;
                    $file_list[$i]['filesize'] = $image->file_size;
                    $file_list[$i]['dir_path'] = '';
                    //获得文件扩展名
                    $temp_arr = explode(".", $image->real_name);
                    $file_ext = array_pop($temp_arr);
                    $file_ext = trim($file_ext);
                    $file_ext = strtolower($file_ext);
                    $file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
                    $file_list[$i]['filetype'] = $file_ext;
                    $file_list[$i]['filename'] =  Yii::$app->params['HTTP_IMAGE']."group1/".$image->file_id; //文件名，包含扩展名
                    $file_list[$i]['datetime'] = $image->date_added; //文件最后修改时间
                    $i++;
                }
            }
            if($handle->children){
                foreach($handle->children as $children){
                    $file_list[$i]['is_dir'] = true; //是否文件夹
                    $file_list[$i]['has_file'] = $children->image?true:false; //文件夹是否包含文件
                    $file_list[$i]['filesize'] = 0; //文件大小
                    $file_list[$i]['is_photo'] = false; //是否图片
                    $file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
                    $file_list[$i]['filename'] = $children->name; //文件名，包含扩展名
                    $file_list[$i]['datetime'] = ''; //文件最后修改时间
                    $i++;
                }
            }
        }



        usort($file_list, [$this,'cmp_func']);

        $result = array();
//相对于根目录的上一级目录
        $result['moveup_dir_path'] = $moveup_dir_path;
//相对于根目录的当前目录
        $result['current_dir_path'] = $current_dir_path;
//当前目录的URL
        $result['current_url'] ="";
//文件数
        $result['total_count'] = count($file_list);
//文件列表数组
        $result['file_list'] = $file_list;

//输出JSON字符串
        header('Content-type: application/json; charset=UTF-8');
        $json = new Services_JSON();
        echo $json->encode($result);
    }

    public function UploadJosnAction() {
//定义允许上传的文件扩展名
        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );


//PHP上传失败
        if (!empty($_FILES['imgFile']['error'])) {
            switch ($_FILES['imgFile']['error']) {
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
            $this->alert($error);
        }

//有上传文件时
        if (empty($_FILES) === false) {
            //原文件名
            $file_name = $_FILES['imgFile']['name'];
            //服务器上临时文件名
            $tmp_name = $_FILES['imgFile']['tmp_name'];
            //文件大小
            $file_size = $_FILES['imgFile']['size'];
            //检查文件名
            if (!$file_name) {
                $this->alert("请选择文件。");
            }
            //检查是否已上传
            if (@is_uploaded_file($tmp_name) === false) {
                $this->alert("上传失败。");
            }
            //检查文件大小
            if ($file_size > $this->max_size) {
                $this->alert("上传文件大小超过限制。");
            }
            //检查目录名
            $dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
            if (empty($ext_arr[$dir_name])) {
                $this->alert("目录名不正确。");
            }
            if(!$cat_model=ImageCategory::findOne(['name'=>$dir_name])){
                $cat_model= new ImageCategory();
                $cat_model->parent_id=1;
                $cat_model->name=$dir_name;
                $cat_model->status=1;
                $cat_model->platform_id=1;
                $cat_model->save();
            }

            //获得文件扩展名
            $temp_arr = explode(".", $file_name);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);
            //检查扩展名
            if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
                $this->alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
            }
            $file = UploadedFile::getInstanceByName('imgFile');
            $model = new DynamicModel(compact('file'));
            $file_new_name = dirname($model->file->tempName)."/".$model->file->name;
            @rename($model->file->tempName, $file_new_name);
            $tracker_addr = Yii::$app->params['FDFS']['tracker_addr'];
            $tracker_port = Yii::$app->params['FDFS']['tracker_port'];
            $group_name=Yii::$app->params['FDFS']['group_name'];
            $tracker      = new Tracker($tracker_addr, $tracker_port);
            $storage_info = $tracker->applyStorage($group_name);
            $storage = new Storage($storage_info['storage_addr'], $storage_info['storage_port']);
            if ($data = $storage->uploadFile($storage_info['storage_index'], $file_new_name)) {
                $imageModel=new Image();
                $imageModel->category_id=$cat_model->category_id;
                $imageModel->file_id=$data['path'];
                $imageModel->file_size=$file_size;
                $imageModel->real_name=$file_name;
                $imageModel->file_type=1;
                $imageModel->group_id=$data['group'];
                $imageModel->status=1;
                $imageModel->storage_ip=Yii::$app->request->getUserIP();
                $imageModel->date_added=time();
                if(!$imageModel->save(false)){
                    throw new BadRequestHttpException(Json::encode($imageModel->errors));
                }
                $file_url= Yii::$app->params['HTTP_IMAGE'].$data['group']."/".$data['path'];
                header('Content-type: text/html; charset=UTF-8');
                $json = new Services_JSON();
                echo $json->encode(array('error' => 0, 'url' => $file_url));
                exit;
            } else {
                $this->alert("上传失败，服务器异常");
            }
        }

      

    }
    public   function alert($msg) {
            header('Content-type: text/html; charset=UTF-8');
            $json = new Services_JSON();
            echo $json->encode(array('error' => 1, 'message' => $msg));
            exit;
        }

}

?>
