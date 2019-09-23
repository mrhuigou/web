<?php

namespace common\extensions\widgets\fileapi\actions;

use common\component\Upload\FastDFS\Storage;
use common\component\Upload\FastDFS\Tracker;
use common\extensions\widgets\fileapi\Widget;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use Yii;

/**
 * UploadAction for images and files.
 *
 * Usage:
 * ```php
 * public function actions()
 * {
 *     return [
 *         'upload' => [
 *             'class' => 'common\extensions\widgets\fileapi\actions\UploadAction',
 *             'path' => '@path/to/files',
 *             'uploadOnlyImage' => false
 *         ]
 *     ];
 * }
 * ```
 */
class UploadAction extends Action
{
    /**
     * @var string Path to directory where files will be uploaded
     */
    public $path;

    /**
     * @var string Validator name
     */
    public $uploadOnlyImage = true;

    /**
     * @var string The parameter name for the file form data (the request argument name).
     */
    public $paramName = 'file';

    /**
     * @var boolean If `true` unique filename will be generated automatically
     */
    public $unique = true;

    /**
     * @var array Model validator options
     */
    public $validatorOptions = [];

    /**
     * @var string Model validator name
     */
    private $_validator = 'image';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" attribute must be set.');
        } else {
            $this->path = FileHelper::normalizePath(Yii::getAlias($this->path)) . DIRECTORY_SEPARATOR;

            if (!FileHelper::createDirectory($this->path)) {
                throw new InvalidCallException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
            }
        }
        if ($this->uploadOnlyImage !== true) {
            $this->_validator = 'file';
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isPost) {


            $file = UploadedFile::getInstanceByName($this->paramName);

            $model = new DynamicModel(compact('file'));

            $model->addRule('file', $this->_validator, $this->validatorOptions)->validate();

            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            } else {
                if ($this->unique === true && $model->file->extension) {
                    $model->file->name = uniqid() . '.' . $model->file->extension;
                }
                $file_name = dirname($model->file->tempName)."/".$model->file->name;
                @rename($model->file->tempName, $file_name);
                $tracker_addr = Yii::$app->params['FDFS']['tracker_addr'];
                $tracker_port = Yii::$app->params['FDFS']['tracker_port'];
                $group_name=Yii::$app->params['FDFS']['group_name'];
                $tracker      = new Tracker($tracker_addr, $tracker_port);
                $storage_info = $tracker->applyStorage($group_name);
                $storage = new Storage($storage_info['storage_addr'], $storage_info['storage_port']);
                if ($data = $storage->uploadFile($storage_info['storage_index'], $file_name)) {
                    $result = ['name' => $data['group']."/".$data['path']];
                } else {
                    $result = ['error' => Widget::t('fileapi', 'ERROR_CAN_NOT_UPLOAD_FILE')];
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $result;
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }
}
