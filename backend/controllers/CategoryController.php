<?php

namespace backend\controllers;

use api\models\V1\Category;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if(!$model=\Yii::$app->cache->get('sys_cate')){
            $model=Category::find()->orderBy('sort_order asc')->all();
            \Yii::$app->cache->set('sys_cate',$model,3600);
        }
        foreach($model as $value){
            $tree[]=[
                'id'=>$value->category_id,
                'pid'=>$value->parent_id,
                'sort'=>$value->sort_order,
                'content'=>$value->description->name."[".$value['code']."]",
            ];
        }
        $data=\common\component\Helper\Helper::genTree($tree);
        return $this->render('index',['data'=>$data]);
    }

}
