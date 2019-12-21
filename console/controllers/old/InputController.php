<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/20
 * Time: 10:29
 */
namespace console\controllers\old;
use api\models\V1\Coupon;
use api\models\V1\CustomerCoupon;
use common\models\User;

class InputController extends \yii\console\Controller {
	public function actionIndex()
	{
		if ($userA = $this->getUserA()) {
			echo "----------------Page:manager-------------------\n";
			if ($coupon = Coupon::findOne(['code' => 'ECP160906004'])) {
				foreach ($userA as $key => $value) {
					if ($customer = User::findByUsername($value)) {
						if (!$customer_coupon = CustomerCoupon::findOne(['customer_id' => $customer->customer_id, 'coupon_id' => $coupon->coupon_id])) {
							$customer_coupon = new CustomerCoupon();
							$customer_coupon->customer_id = $customer->customer_id;
							$customer_coupon->coupon_id = $coupon->coupon_id;
							$customer_coupon->description = "每日惠购中秋福利";
							$customer_coupon->is_use = 0;
							$customer_coupon->date_added = date('Y-m-d H:i:s', time());
							$customer_coupon->save();
							echo $key . "----------" . $customer->customer_id . "---------" . $customer->telephone . "----------\n";
						}
					}
				}
			}
		}
		if ($userB = $this->getUserB()) {
			echo "----------------Page:user-------------------\n";
			if ($coupon = Coupon::findOne(['code' => 'ECP160906003'])) {
				foreach ($userB as $key => $value) {
					if ($customer = User::findByUsername($value)) {
						if (!$customer_coupon = CustomerCoupon::findOne(['customer_id' => $customer->customer_id, 'coupon_id' => $coupon->coupon_id])) {
							$customer_coupon = new CustomerCoupon();
							$customer_coupon->customer_id = $customer->customer_id;
							$customer_coupon->coupon_id = $coupon->coupon_id;
							$customer_coupon->description = "每日惠购中秋福利";
							$customer_coupon->is_use = 0;
							$customer_coupon->date_added = date('Y-m-d H:i:s', time());
							$customer_coupon->save();
							echo $key . "----------" . $customer->customer_id . "---------" . $customer->telephone . "----------\n";
						}
					}
				}
			}
		}
		echo "----------------Finish-------------------\n";
	}
	public function getUserA()
	{
		return [
			'13356852166',
			'13396398700',
			'15288987869',
			'15863023502',
			'13678865003',
			'18653200150',
			'18660200956',
			'18661663398',
			'15288987637',
			'13606347288',
			'15254251926',
			'13153241819',
			'13808967169'
		];
	}

	public function getUserB()
	{
		return [
			'18561322468',
			'15762298085',
			'13953238954',
			'13969899496',
			'15666520657',
			'15964294096',
			'18561563641',
			'13405324012',
			'15863015480',
			'15863112336',
			'15964994110',
			'13573863530',
			'15053278502',
			'13295420593',
			'13256865932',
			'18753247133',
			'18906397520',
			'13012505611',
			'15964249608',
			'15820026355',
			'18561679125',
			'18661977546',
			'18764200310',
			'15266201596',
			'18669758086',
			'15865583953',
			'15066213262',
			'13697658223',
			'15866898919',
			'13791988678',
			'15908978533',
			'13668862062',
			'13188987726',
			'18305420639',
			'13646483233',
			'13954215556',
			'13853207758',
			'15066203069',
			'13165020378',
			'13608966332',
			'18953287730',
			'18560680857',
			'15776699926',
			'15806518991',
			'13395322089',
			'18353288362',
			'17086201000',
			'13805321831',
			'15153257987',
			'18678988548',
			'15854231340',
			'18669747770'
		];
	}

}