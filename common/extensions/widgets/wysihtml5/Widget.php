<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace common\extensions\widgets\wysihtml5;

use common\extensions\widgets\wysihtml5\assets\Asset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class Widget extends InputWidget
{
    public function init()
    {
        
    }


    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();

        if ($this->hasModel()) {
            $data= Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            $data= Html::textarea($this->name, $this->value, $this->options);
        }
        return $this->render('index',['data'=>$data]);

    }
    /**
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        //$selector = Json::encode($this->selector);
        $asset = Asset::register($view);

        //$view->registerJs("jQuery($selector).redactor($settings);");
        // $view->registerJs("$('#" . $this->options['id'] . "').wysihtml5();");
        $view->registerJs("var editor = new wysihtml5.Editor('editor', {
            toolbar: 'toolbar',
            stylesheets: ['/assets/c90b8496/css/simditor.css'],
            parserRules:  wysihtml5ParserRules // defined in file parser rules javascript
          });");
    }
} 