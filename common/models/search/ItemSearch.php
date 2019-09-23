<?php
namespace common\models\search;
use api\models\V1\ProductBase;
use Yii;

/**
 * @property integer $id
 * @property string $code
 * @property string $store_code,
 * @property string $item_name
 * @property string $item_description
 * @property string $model
 * @property integer $brand_id
 * @property string $brand_code
 * @property integer $category
 * @property integer $store_category
 * @property string $attribute
 * @property float $price
 * @property integer $record
 * @property integer $review
 * @property integer $favourite
 * @property integer $be_gift
 * @property integer $status
 *
 */
class ItemSearch extends \yii\elasticsearch\ActiveRecord {
	public static function index()
	{
		return 'item-index';
	}

	public static function type()
	{
		return 'item-type';
	}
	/**
	 * @inheritdoc
	 */
	/**
	 * @return array the list of attributes for this record
	 */
	public function attributes()
	{
		return ['id', 'code', 'store_code', 'item_name', 'model', 'brand_id','brand_code', 'category', 'store_category', 'attribute', 'price', 'record', 'review', 'favourite','be_gift', 'status'];
	}

	public function getProductBase(){
		return $this->hasOne(ProductBase::className(),['product_base_id'=>'id']);
	}


	/**
	 * @return array This model's mapping
	 */
	public static function mapping()
	{
		return [
			static::type() => [
				'properties' => [
					'id' => ["type" => "integer"],
					'code' => ["type" => "string", "index" => "not_analyzed"],
					'model' => ["type" => "string", "index" => "not_analyzed"],
					'price' => ["type" => "long"],
					'item_name' => [
						"analyzer" => "custom_pinyin_analyzer",
						"term_vector" => "with_positions_offsets",
						"boost" => 10,
						'type'=>'string',
						"fields" => [
							"cname" => ["type" => "string",
								"analyzer" => "ik_syno",
								"term_vector" => "with_positions_offsets",
								"boost" => 5
							]
						]
					],
					'brand_id' => ["type" => "integer"],
					'brand_code' => ["type" => "string"],
					'attribute' => ["type" => "string", "index" => "not_analyzed"],
					'store_code' => ["type" => "string", "index" => "not_analyzed"],
					'store_category' => ["type" => "string", "index" => "not_analyzed"],
					'record' => ["type" => "integer"],
					'review' => ["type" => "integer"],
					'favourite' => ["type" => "integer"],
					'be_gift' => ["type" => "integer"],
					'status' => ["type" => "integer"],
				]
			],
		];
	}
	/**
	 * Set (update) mappings for this model
	 */
	public static function updateMapping()
	{
		$db = static::getDb();
		$command = $db->createCommand();
		$command->setMapping(static::index(), static::type(), static::mapping());
	}

	/**
	 * Create this model's index
	 */
	public static function createIndex()
	{
		$db = static::getDb();
		$command = $db->createCommand();
		$command->createIndex(static::index(), [
			'settings' => [ /* ... */ ],
			'mappings' => static::mapping(),
			//'warmers' => [ /* ... */ ],
			//'aliases' => [ /* ... */ ],
			//'creation_date' => '...'
		]);
	}

	/**
	 * Delete this model's index
	 */
	public static function deleteIndex()
	{
		$db = static::getDb();
		$command = $db->createCommand();
		$command->deleteIndex(static::index(), static::type());
	}
}