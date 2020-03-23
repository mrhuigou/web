<?php
namespace affiliate\models;
use api\models\V1\Invoice;
use Yii;
class AffiliateOrderForm extends Invoice {
    public $address_id;
    public function __construct($invoice_id=0, $config = [])
    {
        $this->type_invoice = 2;
        $this->address_id = 248998;
        parent::__construct($config);
    }

}