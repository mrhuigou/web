<?php

namespace common\models;

use common\component\cart\CartPositionInterface;
use common\component\cart\CartPositionTrait;
use Yii;


/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property integer $category_id
 * @property string $code
 * @property string $model
 * @property string $name
 * @property string $description
 * @property string $image
 * @property string $market_price
 * @property string $sell_price
 * @property string $cost_price
 * @property integer $qty
 * @property integer $min_qty
 * @property string $weight
 * @property string $unit
 * @property integer $is_presell
 * @property integer $is_best
 * @property integer $is_shipping
 * @property integer $favorite
 * @property integer $visit
 * @property integer $comments
 * @property integer $sale
 * @property integer $grade
 * @property string $spec_array
 * @property integer $status
 * @property string $up_datetime
 * @property string $down_datetime
 * @property string $creat_datetime
 *
 * @property Category $category
 * @property Brand $brand
 * @property ProductAttribute[] $productAttributes
 * @property ProductImage[] $productImages
 * @property ProductSku[] $productSkus
 */
class Product extends \yii\db\ActiveRecord implements CartPositionInterface

{

    use CartPositionTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'category_id', 'qty', 'min_qty', 'is_presell', 'is_best', 'is_shipping', 'favorite', 'visit', 'comments', 'sale', 'grade', 'status'], 'integer'],
            [['description', 'spec_array'], 'string'],
            [['market_price', 'sell_price', 'cost_price', 'weight'], 'number'],
            [['up_datetime', 'down_datetime', 'creat_datetime'], 'safe'],
            [['code', 'model'], 'string', 'max' => 50],
            [['name', 'image'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '产品ID',
            'brand_id' => '品牌表_品牌ID',
            'category_id' => '类目表_类目ID',
            'code' => '产品编码',
            'model' => '产品模型',
            'name' => '产品名称',
            'description' => '产品描述',
            'image' => '产品图片',
            'market_price' => '市场价格',
            'sell_price' => '会员价',
            'cost_price' => '成本价格',
            'qty' => '数量',
            'min_qty' => '最小购物数量',
            'weight' => '重量',
            'unit' => '计量单位',
            'is_presell' => '是否预售',
            'is_best' => '是否推荐',
            'is_shipping' => '是否配送',
            'favorite' => '收藏次数',
            'visit' => '浏览次数',
            'comments' => '评论次数',
            'sale' => '销量',
            'grade' => '评分总数',
            'spec_array' => '序列化(规格组)',
            'status' => '状态',
            'up_datetime' => '上架时间',
            'down_datetime' => '下架时间',
            'creat_datetime' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSkus()
    {
        return $this->hasMany(ProductSku::className(), ['product_id' => 'id']);
    }
    public function getPrice()
    {
        return $this->vip_price;
    }

    public function getId()
    {
        return $this->product_id;
    }
}
