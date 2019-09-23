<?php
namespace frontend\models;

use api\models\V1\Review;
use api\models\V1\Order;
use api\models\V1\Product;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class ReviewForm extends Model
{
    public $author=1;
    public $order_product;
    public $product_rating=5;
    public $service_rating=5;
    public $delivery_rating=5;
    public $comment;
    public $images;
    public function __construct($data, $config = [])
    {
        if (isset($data['order_product'])) {
            $this->order_product = $data['order_product'];
        } else {
            throw new InvalidParamException("数据错误");
        }
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['comment', 'filter', 'filter' => 'trim'],
            [['comment'], 'required'],
            ['comment', 'string','min'=>2],
            ['author', 'string'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function submit()
    {
        if ($this->validate()) {
            $model = new Review();
            $model->customer_id = \Yii::$app->user->getId();
            $model->author = $this->author?'匿名':\Yii::$app->user->identity->nickname;
            $model->text = htmlspecialchars($this->comment);
            $model->rating = $this->product_rating;
            $model->service = $this->service_rating;
            $model->delivery = $this->delivery_rating;
            $model->status = 1;
            $model->date_added = date("Y-m-d H:i:s");
            $model->date_modified = date("Y-m-d H:i:s");
            $model->product_id = $this->order_product->product_id;
            $model->product_base_id = $this->order_product->product_base_id;
            $model->order_product_id = $this->order_product->order_product_id;
            $model->store_id = $this->order_product->order->store_id;
            $model->order_id = $this->order_product->order_id;
            $model->save();
            return true;
        }
        return false;
    }
    public function attributeLabels(){
        return [
            'author' => '匿名',
            'product_rating'=>'商品质量',
            'service_rating'=>'服务质量',
            'delivery_rating'=>'配送速度',
            'comment'=>'购物心得',
            'images' => '商品图片'
        ];
    }
}
