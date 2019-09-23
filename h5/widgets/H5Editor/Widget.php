<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace h5\widgets\H5Editor;

use h5\widgets\H5Editor\assets\Asset;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii\web\View;
class Widget extends InputWidget
{
    public function init()
    {
        parent::init();
    }


    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();

        if ($this->hasModel()) {
            $data= Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            $data= Html::Input($this->name, $this->value, $this->options);
        }
        return $this->render('index',['data'=>$data]);

    }
    /**
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $asset = Asset::register($view);
        $view->registerJs(" var editor = new H5Editor(document.getElementById('richEditor'),document.getElementById('".$this->options['id']."'), '100%', '250px');",View::POS_READY);
    }
} 