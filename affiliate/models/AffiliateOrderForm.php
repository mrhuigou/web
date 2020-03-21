<?php
namespace affiliate\models;
use api\models\V1\Invoice;
use Yii;
class AffiliateOrderForm extends Invoice {
    public function __construct($invoice_id=0, $config = [])
    {
        $this->type_invoice = 2;
        parent::__construct($config);
    }

}