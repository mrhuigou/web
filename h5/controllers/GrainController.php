<?php
namespace h5\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use api\models\V1\AdvertiseDetail;


/**
 * Site controller
 */
class GrainController extends Controller
{
    public function actionIndex(){
	    $data = [];
	    $advertise = new AdvertiseDetail();
	    $focus_position = ['H5-2LLY-SLIDE'];
	    $data['swiper'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-DES5'];
	    $data['ad_5'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-NAV'];
	    $data['nav'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-BRAND'];
	    $data['brand'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-DES1'];
	    $data['ad_1'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-DES2'];
	    $data['ad_2'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-DES3'];
	    $data['ad_3'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-DES4'];
	    $data['ad_4'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-1F01'];
	    $data['floor_1_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-2F01'];
	    $data['floor_2_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-3F01'];
	    $data['floor_3_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-4F01'];
	    $data['floor_4_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-5F01'];
	    $data['floor_5_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-6F01'];
	    $data['floor_6_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-7F01'];
	    $data['floor_7_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-8F01'];
	    $data['floor_8_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LLY-9F01'];
	    $data['floor_9_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    return $this->render("index",$data);
    }
}