<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 10:11
 */
namespace frontend\widgets;
use api\models\V1\CmsType;
use api\models\V1\Information;

class Footer extends \yii\bootstrap\Widget{
    public function init()
    {
        parent::init();
    }
    public function run(){
        $informations = array();
        $cms_types = CmsType::find()->orderBy('weight ASC')->limit(4)->all();
        foreach($cms_types as $cms_type){

            $datas = Information::find()->leftJoin("jr_information_description","jr_information_description.information_id=jr_information.information_id")
                ->where(['jr_information_description.type'=>$cms_type->cms_type_id,'jr_information.status'=>1])
                ->orderBy('sort_order ASC')->all();
            $informations[] = array(
                'name' => $cms_type,
                'value' => $datas
            );
        }

        return $this->render('footer',['informations'=>$informations]);
    }
}