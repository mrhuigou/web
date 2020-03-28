<?php
namespace fx\models;
use api\models\V1\Address;
use api\models\V1\Affiliate;
use api\models\V1\Invoice;
use api\models\V1\Order;
use api\models\V1\User;
use Yii;
class AffiliateOrderForm extends Affiliate {
    public $distribution_type = 1; //配送方式默认为2
    public $address_id;
    public $province;
    public $city;
    public $district;
    public $address = [];
    public function __construct($affiliate_id=0,$fx_user_info)
    {
        parent::__construct();
    }

}