<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace common\extensions\widgets\ad;

use common\extensions\widgets\ad\assets\Asset;
use yii\helpers\Json;
use yii\base\Widget;
use api\models\V1\Advertise;

class Ad extends Widget
{
  public $position_code;
  public $model;
  public $template = "<a alt='{title}' href='{url}'><img src='{src}'></a>";
  public $parts = [];
  public $width = 100;
  public $height = 100;

    public function init()
    {

    }


    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();

        $ad = Advertise::find()->where(['advertise_position_code'=>$this->position_code,'status'=>1])->andWhere('date_start<="'.date("Y-m-d H:i:s").'"')->andWhere('date_end>="'.date("Y-m-d H:i:s").'"')->one();
        if($ad && $ad->advertiseDetials){
            $this->model = $ad->advertiseDetials;
            return $this->temp_render();
        }else{
            return false;
        }
    }

    public function temp_render($content = null)
    {
        if ($content === null) {
            foreach ($this->model as $key => $value) {
                $this->parts = [];
                if (!isset($this->parts['{src}'])) {
                    $this->parts['{src}'] = \common\component\image\Image::resize($value->source_url,$this->width,$this->height);
                }
                if (!isset($this->parts['{url}'])) {
                    $this->parts['{url}'] = $value->link_url;
                }
                if (!isset($this->parts['{title}'])) {
                    $this->parts['{title}'] = $value->title;
                }
                if (!isset($this->parts['{content}'])) {
                    $this->parts['{content}'] = $value->content;
                }
                $content .= strtr($this->template, $this->parts);
            }
        } elseif (!is_string($content)) {
            $content = call_user_func($content, $this);
        }

        return $content;
    }


    /**
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
      //  $settings = Json::encode($this->settings);
        Asset::register($view);
       // $view->registerJs("$('#".$this->id."').more(".$settings.")");
    }
} 