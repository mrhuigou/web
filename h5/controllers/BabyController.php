<?php
namespace h5\controllers;


use api\models\V1\AdvertiseDetail;
use yii\web\NotFoundHttpException;

class BabyController extends \yii\web\Controller
{
    public function actionIndex()
    {
        throw new NotFoundHttpException();
        return false;
            $all_show = 0;
            $all_show = \Yii::$app->request->get('all_show');
            if(time() >= strtotime('2017-10-10 00:00:00')){
                $all_show = 1;
            }

            $data = [];
            $advertise = new AdvertiseDetail();
//		/*获取滚动banner*/
            $focus_position = ['H5-2LMY-SLIDE'];//母婴轮播
            $data['slide'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

            $focus_position = ['H5-2LMY-NAV'];//母婴图标导航
            $data['nav'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);


            $focus_position = 'H5-2LMY-BRAND';//母婴图标导航
            $data['brand'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
            $focus_position = ['H5-2LMY-DES1'];//母婴2列大
            $data['des1'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
            $focus_position = ['H5-2LMY-DES2'];//母婴2列小
            $data['des2'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);


            $focus_position = ['H5-2LMY-DES3'];//母婴商品1
            $data['des3'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
            $focus_position = ['H5-2LMY-DES4'];//母婴通栏1
            $data['des4'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

            $focus_position = ['H5-2LMY-1F01'];//母婴1楼活动
            $data['f11'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
            $focus_position = ['H5-2LMY-1F02'];//母婴1楼商品
            $data['f12'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

            $focus_position = ['H5-2LMY-2F01'];//母婴2楼活动
            $data['f21'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
            $focus_position = ['H5-2LMY-2F02'];//母婴2楼商品
            $data['f22'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

            $focus_position = ['H5-2LMY-3F01'];//母婴3楼活动
            $data['f31'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
            $focus_position = ['H5-2LMY-3F02'];//母婴3楼商品
            $data['f32'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

            $focus_position = ['H5-2LMY-4F01'];//母婴4楼活动
            $data['f41'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
            $focus_position = ['H5-2LMY-4F02'];//母婴4楼商品
            $data['f42'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

            $focus_position = ['H5-2LMY-5F01'];//母婴5楼活动
            $data['f51'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
            $focus_position = ['H5-2LMY-5F02'];//母婴5楼商品
            $data['f52'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

            $focus_position = ['H5-2LMY-6F01'];//母婴6楼活动
            $data['f61'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
            $focus_position = ['H5-2LMY-6F02'];//母婴6楼商品
            $data['f62'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

            $focus_position = ['H5-2LMY-7F01'];//母婴6楼活动
            $data['f71'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
            $focus_position = ['H5-2LMY-7F02'];//母婴6楼商品
            $data['f72'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);


        $focus_position = ['H5-2LMY-DES5'];//10.10日前 母婴通栏广告位
        $data['des5'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-2LMY-DES6'];//10.10日前 母婴爆品广告位，只弹层，不链接
        $data['des6'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);



            $data['all_show'] = $all_show;
            return $this->render('index', $data);

    }
    public function actionBrands(){
        $data = [];
        $advertise = new AdvertiseDetail();
//		/*获取滚动banner*/
        $focus_position = ['H5-MYPP-1F01'];//奶粉辅食
        $data['f11'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

        $focus_position = ['H5-MYPP-1F02'];//奶粉辅食
        $data['f12'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-1F03'];//奶粉辅食
        $data['f13'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-1F04'];//奶粉辅食
        $data['f14'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);


        $focus_position = ['H5-MYPP-2F01'];//尿裤湿巾
        $data['f21'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-2F02'];//尿裤湿巾
        $data['f22'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-2F03'];//尿裤湿巾
        $data['f23'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

        $focus_position = ['H5-MYPP-3F01'];//用品洗护
        $data['f31'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-3F02'];//用品洗护
        $data['f32'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-3F03'];//用品洗护
        $data['f33'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-3F04'];//用品洗护
        $data['f34'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

        $focus_position = ['H5-MYPP-4F01'];//妈妈专区
        $data['f41'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-4F02'];//妈妈专区
        $data['f42'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-4F03'];//妈妈专区
        $data['f43'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-4F04'];//妈妈专区
        $data['f44'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-4F05'];//妈妈专区
        $data['f45'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-MYPP-4F06'];//妈妈专区
        $data['f46'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

        return $this->render('brands', $data);

    }
}