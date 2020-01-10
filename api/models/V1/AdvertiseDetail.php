<?php
namespace api\models\V1;
use common\component\image\Image;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%advertise_detail}}".
 *
 * @property integer $advertise_detail_id
 * @property string $advertise_detail_code
 * @property string $title
 * @property integer $advertise_id
 * @property string $advertise_code
 * @property integer $advertise_position_id
 * @property string $advertise_position_code
 * @property integer $advertise_media_id
 * @property string $advertise_media_code
 * @property string $advertise_media_type
 * @property string $date_start
 * @property string $date_end
 * @property string $link_url
 * @property string $source_url
 * @property string $content
 * @property integer $priority
 * @property integer $main_priority
 * @property integer $position_priority
 * @property integer $category_display_id
 * @property string $group_type
 * @property integer $width
 * @property integer $height
 * @property integer $status
 * @property integer $legal_person_id
 * @property string $legal_person_code
 * @property integer $platform_id
 * @property string $platform_code
 * @property integer $store_id
 * @property string $store_code
 * @property integer $product_id
 * @property string $product_code
 * @property integer $manufacturer_id
 * @property string $manufacturer_code
 * @property integer $sort_order
 * @property string $date_added
 * @property string $date_modified
 */
class AdvertiseDetail extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advertise_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['advertise_detail_code', 'advertise_id', 'advertise_position_id', 'advertise_media_id', 'date_start', 'date_end', 'date_added', 'date_modified'], 'required'],
            [['advertise_id', 'advertise_position_id', 'advertise_media_id', 'priority', 'main_priority', 'position_priority', 'category_display_id', 'width', 'height', 'status', 'legal_person_id', 'platform_id', 'store_id', 'product_id', 'manufacturer_id', 'sort_order'], 'integer'],
            [['date_start', 'date_end', 'date_added', 'date_modified'], 'safe'],
            [['content', 'link_url'], 'string'],
            [['advertise_detail_code', 'advertise_code', 'advertise_position_code', 'advertise_media_code', 'legal_person_code', 'platform_code', 'store_code', 'product_code', 'manufacturer_code'], 'string', 'max' => 40],
            [['title', 'source_url', 'group_type'], 'string', 'max' => 255],
            [['advertise_media_type'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advertise_detail_id' => 'Advertise Detail ID',
            'advertise_detail_code' => '后台明细唯一性标识',
            'title' => '标题',
            'advertise_id' => 'Advertise ID',
            'advertise_code' => 'Advertise Code',
            'advertise_position_id' => '广告位置ID',
            'advertise_position_code' => 'Advertise Position Code',
            'advertise_media_id' => '媒体类型ID',
            'advertise_media_code' => 'Advertise Media Code',
            'advertise_media_type' => 'Advertise Media Type',
            'date_start' => '开始日期',
            'date_end' => '结束日期',
            'link_url' => '链接地址',
            'source_url' => '来源地址',
            'content' => 'Content',
            'priority' => '优先级',
            'main_priority' => '主表优先级',
            'position_priority' => '广告位置优先级',
            'category_display_id' => '显示分类id',
            'group_type' => '广告组（显示分类）类型',
            'width' => '图片宽度',
            'height' => '图片高度',
            'status' => '有效状态，0=无效，1=生效',
            'legal_person_id' => '广告商ID(法人)',
            'legal_person_code' => 'Legal Person Code',
            'platform_id' => 'Platform ID',
            'platform_code' => 'Platform Code',
            'store_id' => '店铺ID',
            'store_code' => 'Store Code',
            'product_id' => '商品ID',
            'product_code' => 'Product Code',
            'manufacturer_id' => '品牌',
            'manufacturer_code' => 'Manufacturer Code',
            'sort_order' => '优先级',
            'date_added' => '创建日期',
            'date_modified' => '修改日期',
        ];
    }

    public function getAdvertiserDetailByPositionCode($code = '')
    {
        $advertise_detail = AdvertiseDetail::find()
            ->joinWith([
                'product'=>function($query){
                    $query ->andFilterWhere(['jr_product.beintoinv'=>1]);//选择已经上架的
                }
            ])
            ->where(["and", "jr_advertise_detail.date_start<'" . date('Y-m-d H:i:s') . "'", "jr_advertise_detail.date_end>'" . date('Y-m-d H:i:s') . "'", 'jr_advertise_detail.status=1'])
            ->andWhere(['jr_advertise_detail.advertise_position_code' => $code])
            ->orderBy("jr_advertise_detail.sort_order ASC, jr_advertise_detail.position_priority ASC")
            ->all();
        return $advertise_detail;
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
    public function getBrand(){
        return $this->hasOne(Manufacturer::className(),['manufacturer_id'=>'manufacturer_id']);
    }
    public function getStore(){
        return $this->hasOne(Store::className(),['store_id'=>'store_id']);
    }

    // $type link image title
    public function getInfo(){
        $image = Image::resize('no_image.jpg', $this->width, $this->height, 9);
        if ($this->advertise_media_type == 'PACK') {
            $prdocut_info = Product::findOne($this->product_id);
            if (!empty($prdocut_info)) {
                if (isset($this->source_url) && !empty($this->source_url)) {
                    $source_url = $this->source_url;
                    $image = Image::resize($source_url, $this->width, $this->height, 9);
                } else {
                    if ($prdocut_info->image) {
                        $image = Image::resize($prdocut_info->image, $this->width, $this->height, 9);
                    } else {
                        $image = Image::resize("no_image_home.jpg", $this->width, $this->height, 9);
                    }
                    $this->source_url = $image;
                }

                if (isset($this->link_url) && !empty($this->link_url)) {
                    $link_url = $this->link_url;
                } else {
                    $link_url = Url::to(["/product/index", 'product_base_code' => $prdocut_info->product_base_code, 'shop_code' => $prdocut_info->store_code]);
                }
                $this->link_url = $link_url;

            }

        } else if ($this->advertise_media_type == 'BRAND') {
            if (isset($this->link_url) && !empty($this->link_url)) {
                $link_url = $this->link_url;
            } else {
                $link_url = Url::to(["/shop/search", "brand_id" => $this->manufacturer_id]);
            }
            if (isset($this->source_url) && !empty($this->source_url)) {
                $source_url = $this->source_url;
                $image = Image::resize($source_url, $this->width, $this->height, 9);
            } else {
                $manufacturer = Manufacturer::find()->where(['code' => $this->manufacturer_code])->one();
                $image = $manufacturer->image;
                $image = Image::resize($image, $this->width, $this->height, 9);
            }
            $this->source_url = $image;
            $this->link_url = $link_url;

        }
        return true;
    }
    public function getAdvertiseInfo($advertise_array)
    {
        $data = [];
        if (!empty($advertise_array)) {
            foreach ($advertise_array as $adv) {
                $image = Image::resize('no_image.jpg', $adv->width, $adv->height, 9);
                if ($adv->advertise_media_type == 'PACK') {
                    $prdocut_info = Product::findOne($adv->product_id);
                    if (!empty($prdocut_info)) {
                        if (isset($adv->source_url) && !empty($adv->source_url)) {
                            $source_url = $adv->source_url;
                            $image = Image::resize($source_url, $adv->width, $adv->height, 9);
                        } else {
                            if ($prdocut_info->image) {
                                $image = Image::resize($prdocut_info->image, $adv->width, $adv->height, 9);
                            } else {
                                $image = Image::resize("no_image_home.jpg", $adv->width, $adv->height, 9);
                            }
                        }
                        if (isset($adv->link_url) && !empty($adv->link_url)) {
                            $link_url = $adv->link_url;
                        } else {
                            $link_url = Url::to(["/product/index", 'product_base_code' => $prdocut_info->product_base_code, 'shop_code' => $prdocut_info->store_code]);
                        }

                        if ($prdocut_info->stockCount > 10) {
                            $stock = '库存充足';
                        } else {
                            $stock = '库存紧张';
                        }
                        $data[] = [
                            'product_id' => $prdocut_info->product_id,
                            'product_base_code' => $prdocut_info->product_base_code,
                            //'store'	=> $store,
                            'product_name' => $prdocut_info->description->name,
                            'description' => $prdocut_info->description->description,
                            //  'sale_count'	=> $sale_count,
                            'meta_keyword' => $prdocut_info->description->meta_keyword,
                            'meta_description' => $prdocut_info->description->meta_description,
                            //'product_options'	=> $product_options,
                            'price' => $prdocut_info->getPrice(),
                            'vip_price' => $prdocut_info->vip_price,
                            'sale_price' => $prdocut_info->price,
                            'stock_count' => $stock,
                            'unit' => $prdocut_info->unit,
                            'image' => $image,
                            'title' => $prdocut_info->description->name,
                            'href' => $link_url,
                            'link' => $link_url,
                            'advertise_media_type' => $adv->advertise_media_type
                        ];
                    }

                } else if ($adv->advertise_media_type == 'BRAND') {
                    if (isset($adv->link_url) && !empty($adv->link_url)) {
                        $link_url = $adv->link_url;
                    } else {
                        $link_url = Url::to(["/category/index", "brand_id" => $adv->manufacturer_id]);
                    }
                    if (isset($adv->source_url) && !empty($adv->source_url)) {
                        $source_url = $adv->source_url;
                        $image = Image::resize($source_url, $adv->width, $adv->height, 9);
                    } else {
                        $manufacturer = Manufacturer::find()->where(['code' => $adv->manufacturer_code])->one();
                        $image = $manufacturer->image;
                        $image = Image::resize($image, $adv->width, $adv->height, 9);
                    }
                    $data[] = [
                        'image' => $image,
                        'link' => $link_url,
                        'title' => $adv->title,
                        'advertise_media_type' => $adv->advertise_media_type
                    ];

                } else if ($adv->advertise_media_type == 'SHOP') {
                    if (isset($adv->link_url) && !empty($adv->link_url)) {
                        $link_url = $adv->link_url;
                    } else {
                        //新的店铺链接还未定
                        $link_url = "javascript:void(0)";
                    }
                    if (isset($adv->source_url) && !empty($adv->source_url)) {
                        $source_url = $adv->source_url;
                        $image = Image::resize($source_url, $adv->width, $adv->height, 9);
                    } else {
                        $store_info = Store::findOne($adv->store_id);
                        $image = $store_info->logo;
                        $image = Image::resize($image, $adv->width, $adv->height, 9);
                    }
                    $data[] = [
                        'image' => $image,
                        'link' => $link_url,
                        'title' => $adv->title,
                        'advertise_media_type' => $adv->advertise_media_type
                    ];
                } else if ($adv->advertise_media_type == 'IMAGE') {
                    if (isset($adv->link_url) && !empty($adv->link_url)) {
                        $link_url = $adv->link_url;
                    } else {
                        $link_url = 'javascript:void(0)';
                    }
                    if (isset($adv->source_url) && !empty($adv->source_url)) {
                        $source_url = $adv->source_url;
                        $image = Image::resize($source_url, $adv->width, $adv->height, 9);
                    }
                    $data[] = [
                        'image' => $image,
                        'link' => $link_url,
                        'title' => $adv->title,
                        'advertise_media_type' => $adv->advertise_media_type
                    ];
                } else if ($adv->advertise_media_type == 'TEXT') {
                    if (isset($adv->link_url) && !empty($adv->link_url)) {
                        $link_url = $adv->link_url;
                    } else {
                        $link_url = 'javascript:void(0)';
                    }
                    if (isset($adv->content)) {
                        $text = $adv->content;
                    }
                    $data[] = [
                        'image' => $text,
                        'link' => $link_url,
                        'title' => $adv->title,
                        'advertise_media_type' => $adv->advertise_media_type
                    ];
                }

            }
        }
        return $data;
    }

}
